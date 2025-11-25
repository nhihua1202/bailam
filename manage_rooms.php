<?php
// manage_rooms.php
// Full file - copy & paste vào project của bạn.
// Requirements:
// - header.php: phải start session và đặt $_SESSION['user']
// - db.php: phải tạo PDO $pdo
// - Thư mục uploads/ chứa hình (hoặc DB lưu full URL)
// - assets/default.png: ảnh mặc định

if (session_status() === PHP_SESSION_NONE) session_start();

require 'header.php'; // phải khởi session, user info
require 'db.php';     // phải khai $pdo

// --- quyền: chỉ landlord được xem (thay đổi theo hệ thống bạn) ---
if (empty($_SESSION['user']['id'])) {
    header("Location: auth.php?mode=login");
    exit;
}
$u = $_SESSION['user'];
if (($u['role'] ?? '') !== 'landlord') {
    echo '<main style="max-width:900px;margin:40px auto;padding:20px;font-family:system-ui,Arial,Helvetica,sans-serif;">\n            <p style="color:#c53030;font-weight:600;">Bạn không có quyền truy cập.</p>\n          </main>';
    exit;
}
$landlord_id = (int)$u['id'];

/* -------------------- helpers -------------------- */
if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

/**
 * Resolve thumbnail DB value to web URL.
 * - full URL -> keep
 * - starting with / -> keep
 * - file in uploads/ -> map to web path relative to DOCUMENT_ROOT
 * - else -> assets/default.png
 */
function resolve_thumb_url($thumbVal) {
    $thumbVal = trim((string)$thumbVal);
    if ($thumbVal === '') return 'assets/default.png';
    if (preg_match('#^https?://#i', $thumbVal)) return $thumbVal;
    if (strpos($thumbVal, '/') === 0) return $thumbVal;

    // local uploads (filename only)
    $local = __DIR__ . '/uploads/' . ltrim($thumbVal, '/');
    if (is_file($local)) {
        // ensure leading slash for web path
        return '/uploads/' . ltrim($thumbVal, '/');
    }

    // maybe already contains uploads/... relative path
    if (strpos($thumbVal, 'uploads/') === 0) {
        $path = '/' . ltrim($thumbVal, '/');
        if (is_file($_SERVER['DOCUMENT_ROOT'] . $path)) return $path;
        return $path; // still return it, may be served by web
    }

    // fallback
    return 'assets/default.png';
}

function table_exists(PDO $pdo, $table) {
    try {
        $st = $pdo->prepare("SHOW TABLES LIKE ?");
        $st->execute([$table]);
        return (bool)$st->fetchColumn();
    } catch (Exception $ex) { return false; }
}

/* -------------------- Detect requests table -------------------- */
$requests_table = null;
if (table_exists($pdo, 'rental_requests')) $requests_table = 'rental_requests';
elseif (table_exists($pdo, 'rent_requests')) $requests_table = 'rent_requests';

/* -------------------- Stats -------------------- */
try {
    $st = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE user_id = ?");
    $st->execute([$landlord_id]);
    $total_rooms = (int)$st->fetchColumn();
} catch (Exception $ex) { $total_rooms = 0; }

$pending = 0;
if ($requests_table) {
    try {
        $st = $pdo->prepare("\n            SELECT COUNT(*) FROM {$requests_table} rr\n            JOIN posts p ON rr.post_id = p.id\n            WHERE p.user_id = ? AND (LOWER(rr.status) IN ('pending','waiting','new') OR rr.status IN ('0'))\n        ");
        $st->execute([$landlord_id]);
        $pending = (int)$st->fetchColumn();
    } catch (Exception $ex) { $pending = 0; }
}

try {
    $st = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE user_id = ? AND status_rent = 1");
    $st->execute([$landlord_id]);
    $rented = (int)$st->fetchColumn();
} catch (Exception $ex) { $rented = 0; }

/* -------------------- Fetch posts with thumbnail (single optimized query) -------------------- */
try {
    $sql = "\n        SELECT p.*,\n          (\n            SELECT COALESCE(NULLIF(filename,''), NULLIF(file_name,''), NULLIF(image,''), NULLIF(image_path,''), NULLIF(path,''), NULLIF(filepath,'')) \n            FROM post_images WHERE post_id = p.id ORDER BY id ASC LIMIT 1\n          ) AS thumbnail\n        FROM posts p\n        WHERE p.user_id = ?\n        ORDER BY p.created_at DESC\n    ";
    $posts_stmt = $pdo->prepare($sql);
    $posts_stmt->execute([$landlord_id]);
    $posts = $posts_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $ex) {
    // fallback: only posts
    try {
        $st = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
        $st->execute([$landlord_id]);
        $posts = $st->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $ex2) { $posts = []; }
}

/* ensure we have request container for each post to avoid undefined */
$requests_by_post = [];
foreach ($posts as $pp) $requests_by_post[(int)$pp['id']] = ['pending'=>[], 'approved'=>[], 'all'=>[]];

/* -------------------- Fetch requests for these posts (single query) -------------------- */
if ($requests_table && !empty($posts)) {
    $post_ids = array_map(function($x){ return (int)$x['id']; }, $posts);
    $placeholders = implode(',', array_fill(0, count($post_ids), '?'));
    try {
        $sql = "SELECT * FROM {$requests_table} WHERE post_id IN ({$placeholders}) ORDER BY created_at DESC";
        $q = $pdo->prepare($sql);
        $q->execute($post_ids);
        $allReqs = $q->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $ex) { $allReqs = []; }

    foreach ($allReqs as $r) {
        $pid = (int)($r['post_id'] ?? 0);
        if ($pid <= 0) continue;
        if (!isset($requests_by_post[$pid])) $requests_by_post[$pid] = ['pending'=>[], 'approved'=>[], 'all'=>[]];

        $fullname = $r['fullname'] ?? ($r['name'] ?? '—');
        $phone = $r['phone'] ?? ($r['phone_number'] ?? ($r['mobile'] ?? '—'));
        $statusRaw = strtolower(trim((string)($r['status'] ?? '')));
        $status = $statusRaw;
        if ($statusRaw === '0') $status = 'pending';
        if ($statusRaw === '1') $status = 'approved';

        $entry = $r;
        $entry['_display_fullname'] = $fullname;
        $entry['_display_phone'] = $phone;
        $entry['_status_norm'] = $status;

        $requests_by_post[$pid]['all'][] = $entry;
        if (in_array($status, ['pending','waiting','new'])) $requests_by_post[$pid]['pending'][] = $entry;
        if (in_array($status, ['approved','accepted'])) $requests_by_post[$pid]['approved'][] = $entry;
    }
}

/* -------------------- Optional: fetch tenant users if posts.tenant_id exists -------------------- */
$tenant_user_cache = [];
if (!empty($posts)) {
    try {
        $st = $pdo->prepare("\n            SELECT COUNT(*) FROM information_schema.columns \n            WHERE table_schema = DATABASE() AND table_name = 'posts' AND column_name = 'tenant_id'\n        ");
        $st->execute();
        $has_tenant_col = (bool)$st->fetchColumn();
    } catch (Exception $ex) { $has_tenant_col = false; }

    if ($has_tenant_col) {
        $tids = [];
        foreach ($posts as $pp) {
            $tid = (int)($pp['tenant_id'] ?? 0);
            if ($tid > 0) $tids[$tid] = $tid;
        }
        if (!empty($tids) && table_exists($pdo, 'users')) {
            $place = implode(',', array_fill(0, count($tids), '?'));
            try {
                $st = $pdo->prepare("SELECT id, COALESCE(fullname,name) AS name, COALESCE(phone,phone_number) AS phone FROM users WHERE id IN ({$place})");
                $st->execute(array_values($tids));
                $rows = $st->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $r) $tenant_user_cache[(int)$r['id']] = $r;
            } catch (Exception $ex) { /* ignore */ }
        }
    }
}

/* -------------------- Price helper -------------------- */
function fmt_price_million($raw) {
    if ($raw === null || $raw === '') return '';
    $clean = str_replace([',','VNĐ','vnd','đ'], ['', '', '', ''], (string)$raw);
    $clean = trim($clean);
    // nếu lưu dạng số thập phân như 3.5 hoặc 3500000
    if (is_numeric($clean)) {
        $num = (float)$clean;
        // nếu số lớn hơn 1000 -> coi là VND
        if ($num > 1000) $million = $num / 1000000.0;
        else $million = $num; // đã là triệu
        $s = number_format($million, 1, '.', '');
        $s = rtrim(rtrim($s, '0'), '.');
        return $s . ' triệu';
    }
    // nếu dạng chữ có số: '3.4 triệu'
    if (preg_match('/([0-9]+\.?[0-9]*)/', $raw, $m)) {
        $million = (float)$m[1];
        $s = number_format($million, 1, '.', '');
        $s = rtrim(rtrim($s, '0'), '.');
        return $s . ' triệu';
    }
    return e($raw);
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<title>Quản lý phòng</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
:root{--accent:#2563eb;--muted:#6b7280;--bg:#f8f9fb;--card:#fff;--border:#e6e8eb;--text:#111827}
*{box-sizing:border-box}
body{margin:0;font-family:Inter,ui-sans-serif,system-ui,Arial,Helvetica,sans-serif;background:var(--bg);color:var(--text)}
.wrap{max-width:1000px;margin:28px auto;padding:18px}
.header-row{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px}
.h-title{font-size:22px;font-weight:700;margin:0}
.btn-new{display:inline-block;padding:8px 12px;border-radius:8px;background:var(--accent);color:#fff;text-decoration:none;font-weight:600}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px}
.stat{background:var(--card);border:1px solid var(--border);padding:12px;border-radius:10px}
.stat .label{font-size:13px;color:var(--muted);margin-bottom:6px}
.stat .value{font-size:18px;font-weight:700;color:var(--accent)}
.list{display:flex;flex-direction:column;gap:12px}
.room{display:grid;grid-template-columns:110px 1fr 130px 120px;gap:12px;background:var(--card);border:1px solid var(--border);border-radius:12px;padding:12px;align-items:center;transition:box-shadow .12s}
.room:hover{box-shadow:0 8px 24px rgba(2,6,23,0.06)}
@media(max-width:820px){.room{grid-template-columns:96px 1fr}.room>.col-price,.room>.col-actions{grid-column:1/-1;display:flex;justify-content:flex-end;margin-top:10px}}
.thumb{width:110px;height:86px;object-fit:cover;border-radius:8px;border:1px solid var(--border);background:#f3f4f6}
.title{font-size:15px;font-weight:700;margin:0;color:var(--text)}
.meta{font-size:13px;color:var(--muted);margin-top:6px}
.col-price{text-align:right}
.price{font-weight:800;color:var(--accent);font-size:16px}
.price-label{font-size:12px;color:var(--muted);margin-top:6px}
.badge{display:inline-block;padding:6px 10px;border-radius:999px;font-weight:700;font-size:12px}
.badge-available{background:#eef2ff;color:var(--accent);border:1px solid #e6eeff}
.badge-rented{background:#ecfdf5;color:#15803d;border:1px solid #d0f0d8}
.col-actions{display:flex;flex-direction:column;align-items:flex-end;gap:8px}
.link{font-size:13px;color:var(--accent);text-decoration:none;padding:8px 12px;border-radius:10px;border:1px solid transparent}
.link-muted{color:var(--muted);border:1px solid var(--border);padding:8px 12px;border-radius:10px;background:transparent}
.req-box{display:none;margin-top:12px;border-top:1px dashed var(--border);padding-top:12px}
.req-item{display:flex;justify-content:space-between;gap:10px;padding:10px 0;border-bottom:1px solid #f1f3f5}
.req-name{font-weight:700;font-size:14px}
.req-meta{font-size:13px;color:var(--muted);margin-top:4px}
.small{font-size:13px;color:var(--muted)}
.action-btn{display:inline-block;padding:8px 10px;border-radius:8px;color:#fff;text-decoration:none;font-weight:700;font-size:13px}
.approve{background:var(--accent)}.reject{background:#d62b2b}.view{background:#374151}
.tenant-box{margin-top:8px;padding:8px;border-radius:8px;background:#fbfeff;border:1px solid #eef9ff;font-size:13px}
.footer-gap{height:36px}
.notice{padding:14px;border:1px solid #f1f3f5;border-radius:12px;background:#fff;color:var(--muted);text-align:center}
.toggle-btn{display:inline-block;padding:8px 10px;border-radius:10px;background:#fff;border:1px solid var(--border);font-weight:600;cursor:pointer}
</style>
</head>
<body>
<div class="wrap">
  <div class="header-row">
    <h1 class="h-title">Quản lý phòng</h1>
    <div><a href="post_new.php" class="btn-new">Đăng tin mới</a></div>
  </div>

  <div class="stats" role="status" aria-label="Thống kê">
    <div class="stat"><div class="label">Tổng phòng</div><div class="value"><?= (int)$total_rooms ?></div></div>
    <div class="stat"><div class="label">Chờ duyệt</div><div class="value"><?= (int)$pending ?></div></div>
    <div class="stat"><div class="label">Đang thuê</div><div class="value"><?= (int)$rented ?></div></div>
    <div class="stat"><div class="label">Phòng trống</div><div class="value"><?= max(0, $total_rooms - $rented) ?></div></div>
  </div>

  <?php if (empty($posts)): ?>
    <div class="notice">
      <?php if ($total_rooms > 0): ?>
        Có tổng <strong><?= (int)$total_rooms ?></strong> phòng trong cơ sở dữ liệu nhưng không thể tải chi tiết phòng ở trang này.
      <?php else: ?>
        Bạn chưa có phòng nào.
      <?php endif; ?>
    </div>
  <?php else: ?>
    <div class="list" aria-live="polite">
      <?php foreach ($posts as $p):
        $postId = (int)$p['id'];
        $thumbVal = $p['thumbnail'] ?? ($p['image'] ?? ($p['image_path'] ?? ''));
        $thumb = resolve_thumb_url($thumbVal);

        $is_rented = ((int)($p['status_rent'] ?? 0)) === 1;

        $reqs_pending = $requests_by_post[$postId]['pending'] ?? [];
        $reqs_approved = $requests_by_post[$postId]['approved'] ?? [];
        $reqs_all = $requests_by_post[$postId]['all'] ?? [];

        // tenant display: prefer posts.tenant_id, else first approved request
        $tenant_display = null;
        $tid = intval($p['tenant_id'] ?? 0);
        if ($tid > 0 && isset($tenant_user_cache[$tid])) {
            $tu = $tenant_user_cache[$tid];
            $tenant_display = ['name'=>$tu['name'] ?? '—','phone'=>$tu['phone'] ?? '—'];
        } elseif (!empty($reqs_approved)) {
            $first = $reqs_approved[0];
            $tenant_display = ['name'=>$first['_display_fullname'] ?? '—','phone'=>$first['_display_phone'] ?? '—','created_at'=>$first['created_at'] ?? ''];
        }
      ?>
      <div class="room" aria-labelledby="room-<?= $postId ?>" id="room-row-<?= $postId ?>">
        <div>
          <img src="<?= e($thumb) ?>" alt="<?= e($p['title'] ?? '') ?>" class="thumb" onerror="this.src='assets/default.png'">
          <?php if ($is_rented && $tenant_display): ?>
            <div class="tenant-box">
              <div style="font-weight:700">Người thuê: <?= e($tenant_display['name']) ?></div>
              <div class="small"><?= e($tenant_display['phone']) ?> <?= !empty($tenant_display['created_at']) ? '· ' . e(date('d/m/Y', strtotime($tenant_display['created_at']))) : '' ?></div>
            </div>
          <?php endif; ?>
        </div>

        <div>
          <div class="title" data-toggle="req-<?= $postId ?>"><?= e($p['title'] ?? '—') ?></div>
          <div class="meta"><?= e($p['khu_vuc'] ?? '') ?> • <?= e(date('d/m/Y', strtotime($p['created_at'] ?? 'now'))) ?></div>

          <div class="small" style="margin-top:8px">
            <?php if (!empty($reqs_pending)): ?>
              <span style="color:var(--accent);font-weight:700"><?= count($reqs_pending) ?> yêu cầu chờ</span>
            <?php elseif (!empty($reqs_approved)): ?>
              <span style="color:#15803d;font-weight:700">Người thuê (đã duyệt): <?= e($tenant_display['name'] ?? '—') ?></span>
            <?php else: ?>
              <span class="small">Chưa có yêu cầu</span>
            <?php endif; ?>
          </div>

          <div id="req-<?= $postId ?>" class="req-box" aria-hidden="true">
            <div style="margin-bottom:8px;font-weight:700">Yêu cầu chờ</div>
            <?php if (!empty($reqs_pending)): ?>
              <?php foreach ($reqs_pending as $rq): ?>
                <div class="req-item">
                  <div>
                    <div class="req-name"><?= e($rq['_display_fullname']) ?></div>
                    <div class="req-meta"><?= e($rq['_display_phone']) ?> · <?= e($rq['created_at'] ?? '') ?></div>
                  </div>
                  <div style="display:flex;gap:8px;align-items:center">
                    <a href="rent_action.php?action=approve&id=<?= $postId ?>&req_id=<?= intval($rq['id']) ?>" class="action-btn approve">Duyệt</a>
                    <a href="rent_action.php?action=reject&id=<?= $postId ?>&req_id=<?= intval($rq['id']) ?>" class="action-btn reject">Từ chối</a>
                    <a href="view_request.php?id=<?= intval($rq['id']) ?>" class="action-btn view">Xem</a>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="small">Không có yêu cầu chờ.</div>
            <?php endif; ?>

            <div style="margin-top:12px;font-weight:700">Đã duyệt</div>
            <?php if (!empty($reqs_approved)): ?>
              <?php foreach ($reqs_approved as $ar): ?>
                <div class="req-item">
                  <div>
                    <div class="req-name"><?= e($ar['_display_fullname']) ?></div>
                    <div class="req-meta"><?= e($ar['_display_phone']) ?> · <?= e($ar['created_at'] ?? '') ?></div>
                  </div>
                  <div style="display:flex;gap:8px;align-items:center">
                    <span class="small" style="padding:6px 10px;border-radius:8px;background:#ecfdf5;color:#15803d;font-weight:700">Đã duyệt</span>
                    <a href="view_request.php?id=<?= intval($ar['id']) ?>" class="action-btn view">Xem</a>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="small">Không có yêu cầu nào đã duyệt.</div>
            <?php endif; ?>

            <div style="margin-top:12px;font-weight:700">Lịch sử đăng ký</div>
            <?php if (!empty($reqs_all)): ?>
              <?php foreach ($reqs_all as $h): ?>
                <div class="req-item">
                  <div>
                    <div class="req-name"><?= e($h['_display_fullname']) ?> <span style="font-weight:600;color:var(--muted);font-size:12px"> (<?= e($h['_status_norm']) ?>)</span></div>
                    <div class="req-meta"><?= e($h['_display_phone']) ?> · <?= e($h['created_at'] ?? '') ?></div>
                  </div>
                  <div style="display:flex;align-items:center;gap:8px">
                    <a href="view_request.php?id=<?= intval($h['id']) ?>" class="action-btn view">Xem</a>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="small">Không có lịch sử đăng ký.</div>
            <?php endif; ?>
          </div>
        </div>

        <div class="col-price">
          <div class="price"><?= e(fmt_price_million($p['price'] ?? '')) ?></div>
          <div class="price-label">Giá</div>
        </div>

        <div class="col-actions">
          <?php if ($is_rented): ?>
            <span class="badge badge-rented">Đang thuê</span>
            <div style="margin-top:8px;display:flex;gap:8px;flex-direction:column;align-items:flex-end">
              <a href="rent_action.php?action=reset&id=<?= $postId ?>" class="link-muted">Trả phòng</a>
              <button class="toggle-btn" data-toggle="req-<?= $postId ?>">Xem yêu cầu</button>
            </div>
          <?php else: ?>
            <span class="badge badge-available">Phòng trống</span>
            <div style="margin-top:8px;display:flex;gap:8px;flex-direction:column;align-items:flex-end">
              <div style="display:flex;gap:8px">
                <a href="edit_post.php?id=<?= $postId ?>" class="link">Sửa</a>
                <a href="delete_post.php?id=<?= $postId ?>" onclick="return confirm('Xóa phòng này?')" class="link link-muted">Xóa</a>
              </div>
              <button class="toggle-btn" data-toggle="req-<?= $postId ?>">Xem yêu cầu</button>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="footer-gap"></div>
</div>

<script>
// Toggle request box: robust
document.addEventListener('click', function(e){
  const t = e.target.closest('[data-toggle]');
  if(!t) return;
  const id = t.getAttribute('data-toggle');
  const box = document.getElementById(id);
  if(!box) return;

  // close others
  document.querySelectorAll('.req-box').forEach(function(b){
    if (b !== box) {
      b.style.display = 'none';
      b.setAttribute('aria-hidden', 'true');
    }
  });

  const comp = window.getComputedStyle(box);
  const shown = comp && comp.display && comp.display !== 'none';
  if (shown) {
    box.style.display = 'none';
    box.setAttribute('aria-hidden', 'true');
  } else {
    box.style.display = 'block';
    box.setAttribute('aria-hidden', 'false');
    setTimeout(function(){ box.scrollIntoView({behavior:'smooth', block:'center'}); }, 120);
  }
});
</script>
</body>
</html>
