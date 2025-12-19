<?php
// manage_rooms.php
if (session_status() === PHP_SESSION_NONE) session_start();

require 'header.php'; 
require 'db.php';     

// 1. Kiểm tra quyền Landlord
if (empty($_SESSION['user']['id']) || ($_SESSION['user']['role'] ?? '') !== 'landlord') {
    echo '<p style="padding:20px; color:red; text-align:center;">Bạn không có quyền truy cập.</p>';
    exit;
}
$landlord_id = (int)$_SESSION['user']['id'];

/* -------------------- Helpers -------------------- */
if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

function resolve_thumb_url($thumbVal) {
    $thumbVal = trim((string)$thumbVal);
    $default = 'assets/default.png';
    if ($thumbVal === '') return $default;
    if (preg_match('#^https?://#i', $thumbVal)) return $thumbVal;
    $paths = ['uploads/'.ltrim($thumbVal, '/'), ltrim($thumbVal, '/'), '../uploads/'.ltrim($thumbVal, '/')];
    foreach ($paths as $path) { if (file_exists($path)) return $path; }
    return $default;
}

function fmt_price_million($raw) {
    if ($raw === null || $raw === '') return 'Thỏa thuận';
    $clean = str_replace([',','VNĐ','vnd','đ'], '', (string)$raw);
    if (is_numeric($clean)) {
        $num = (float)$clean;
        $million = ($num > 1000) ? $num / 1000000.0 : $num;
        return rtrim(rtrim(number_format($million, 1, '.', ''), '0'), '.') . ' triệu';
    }
    return $raw;
}

/* -------------------- Data Fetching -------------------- */
$sql = "SELECT p.*, (SELECT filename FROM post_images WHERE post_id = p.id ORDER BY id ASC LIMIT 1) AS thumbnail
        FROM posts p WHERE p.user_id = ? ORDER BY p.created_at DESC";
$st = $pdo->prepare($sql);
$st->execute([$landlord_id]);
$posts = $st->fetchAll(PDO::FETCH_ASSOC);

$requests_by_post = [];
foreach ($posts as $pp) $requests_by_post[(int)$pp['id']] = ['pending'=>[], 'approved'=>[], 'all'=>[]];

if (!empty($posts)) {
    $pids = array_column($posts, 'id');
    $placeholders = implode(',', array_fill(0, count($pids), '?'));
    $q = $pdo->prepare("SELECT * FROM rental_requests WHERE post_id IN ($placeholders) ORDER BY created_at DESC");
    $q->execute($pids);
    
    while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
        $pid = (int)$r['post_id'];
        $status = strtolower(trim((string)$r['status']));
        $r['_name'] = $r['fullname'] ?? ($r['name'] ?? 'Khách lạ');
        $r['_phone'] = $r['phone'] ?? ($r['phone_number'] ?? 'N/A');

        $requests_by_post[$pid]['all'][] = $r;
        if (in_array($status, ['pending', '0', 'waiting', 'new'])) $requests_by_post[$pid]['pending'][] = $r;
        elseif (in_array($status, ['approved', '1', 'accepted'])) $requests_by_post[$pid]['approved'][] = $r;
    }

    usort($posts, function($a, $b) use ($requests_by_post) {
        $countA = count($requests_by_post[(int)$a['id']]['pending'] ?? []);
        $countB = count($requests_by_post[(int)$b['id']]['pending'] ?? []);
        if ($countB > 0 && $countA == 0) return 1;
        if ($countA > 0 && $countB == 0) return -1;
        return strtotime($b['created_at']) <=> strtotime($a['created_at']);
    });
}

$total_rooms = count($posts);
$rented_count = count(array_filter($posts, fn($p) => ($p['status_rent'] ?? 0) == 1));
$pending_count = 0;
foreach($requests_by_post as $rp) $pending_count += count($rp['pending']);
?>

<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Quản lý phòng trọ</title>
<style>
    :root{--accent:#2563eb;--danger:#dc2626;--success:#16a34a;--bg:#f3f4f6;--border:#e5e7eb;--text:#1f2937}
    body{font-family:system-ui,-apple-system,sans-serif;background:var(--bg);margin:0;padding:20px;color:var(--text)}
    .container{max-width:1000px;margin:0 auto}
    .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:15px;margin-bottom:25px}
    .stat-card{background:#fff;padding:15px;border-radius:12px;border:1px solid var(--border);text-align:center}
    .stat-card b{font-size:20px;color:var(--accent);display:block}
    .tab-nav{display:flex;gap:10px;margin-bottom:20px;border-bottom:2px solid var(--border)}
    .tab-btn{padding:12px 20px;cursor:pointer;font-weight:700;color:#6b7280;border-bottom:3px solid transparent;background:none;border:none;font-size:15px}
    .tab-btn.active{color:var(--accent);border-bottom-color:var(--accent)}
    .room-item{background:#fff;border-radius:15px;padding:15px;margin-bottom:15px;display:flex;gap:20px;border:1px solid var(--border);position:relative}
    .room-thumb{width:140px;height:100px;border-radius:10px;object-fit:cover;background:#eee;flex-shrink:0}
    .room-info{flex:1}
    .room-info h3{margin:0 0 5px 0;font-size:17px}
    .room-info h3 a{text-decoration:none;color:#111827}
    .price{font-weight:800;color:var(--accent);font-size:18px}
    .btn-group{margin-top:12px;display:flex;gap:8px;flex-wrap:wrap}
    .btn{padding:7px 14px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;cursor:pointer;border:1px solid #ddd;background:#fff;color:#333;display:inline-flex;align-items:center;gap:5px}
    .btn-primary{background:var(--accent);color:#fff;border:none}
    .btn-danger{color:var(--danger);border-color:var(--danger)}
    .btn-waiting{background:#eff6ff;color:var(--accent);border-color:#bfdbfe}
    .req-box{display:none;margin-top:15px;padding-top:15px;border-top:1px dashed #ddd}
    .req-card{background:#f9fafb;padding:10px;border-radius:8px;display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;border:1px solid #eee}
    .badge-new{background:var(--danger);color:#fff;padding:2px 6px;border-radius:10px;font-size:10px}
    .tab-content{display:none}.tab-content.active{display:block}
    .tenant-info{background:#f0fdf4;padding:10px;border-radius:8px;border-left:4px solid var(--success);margin-top:8px;font-size:13px}
</style>
</head>
<body>

<div class="container">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
        <h1 style="font-size:24px">Quản lý nhà cho thuê</h1>
        <a href="post_new.php" class="btn btn-primary" style="padding:10px 20px">+ Đăng tin mới</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">Tổng phòng <b><?= $total_rooms ?></b></div>
        <div class="stat-card">Đang thuê <b><?= $rented_count ?></b></div>
        <div class="stat-card">Đơn mới <b><?= $pending_count ?></b></div>
        <div class="stat-card">Phòng trống <b><?= $total_rooms - $rented_count ?></b></div>
    </div>

    <div class="tab-nav">
        <button class="tab-btn active" onclick="switchTab(event, 'available')">🔵 Phòng trống</button>
        <button class="tab-btn" onclick="switchTab(event, 'rented')">🟢 Đang thuê</button>
    </div>

    <?php foreach(['available' => 0, 'rented' => 1] as $tabId => $statusRent): ?>
    <div id="<?= $tabId ?>" class="tab-content <?= $tabId === 'available' ? 'active' : '' ?>">
        <?php 
        $count = 0;
        foreach ($posts as $p): 
            if ((int)($p['status_rent'] ?? 0) !== $statusRent) continue;
            $count++;
            $pid = (int)$p['id'];
            $pendings = $requests_by_post[$pid]['pending'] ?? [];
            $approveds = $requests_by_post[$pid]['approved'] ?? [];
        ?>
            <div class="room-item" style="<?= !empty($pendings) ? 'border-left:5px solid var(--accent)' : '' ?>">
                <img src="<?= resolve_thumb_url($p['thumbnail'] ?? '') ?>" class="room-thumb" onerror="this.src='assets/default.png'">
                <div class="room-info">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start">
                        <h3><a href="post_detail.php?id=<?= $pid ?>"><?= e($p['title']) ?></a></h3>
                        <span class="price"><?= fmt_price_million($p['price']) ?></span>
                    </div>
                    <div style="font-size:13px; color:#6b7280">📍 <?= e($p['khu_vuc'] ?? 'Chưa rõ địa chỉ') ?></div>

                    <?php if ($statusRent === 1 && !empty($approveds)): $t = $approveds[0]; ?>
                        <div class="tenant-info">
                            <b>Khách thuê:</b> <?= e($t['_name']) ?> — <?= e($t['_phone']) ?>
                        </div>
                    <?php endif; ?>

                    <div class="btn-group">
                        <?php if ($statusRent === 1): ?>
                            <a href="rent_action.php?action=reset&id=<?= $pid ?>" class="btn btn-danger" onclick="return confirm('Khách trả phòng?')">Trả phòng (Làm trống)</a>
                        <?php else: ?>
                            <a href="edit_post.php?id=<?= $pid ?>" class="btn">Sửa tin</a>
                            <a href="delete_post.php?id=<?= $pid ?>" class="btn btn-danger" onclick="return confirm('Xóa phòng này?')">Xóa</a>
                        <?php endif; ?>
                        <button class="btn <?= !empty($pendings) ? 'btn-waiting' : '' ?>" onclick="toggleReq(<?= $pid ?>)">
                            Yêu cầu <?= !empty($pendings) ? '<span class="badge-new">'.count($pendings).'</span>' : '(0)' ?>
                        </button>
                    </div>

                    <div id="req-box-<?= $pid ?>" class="req-box">
                        <h4 style="margin:0 0 10px 0; font-size:14px">Đơn đăng ký:</h4>
                        <?php if (empty($requests_by_post[$pid]['all'])): ?>
                            <p style="font-size:12px; color:#999">Chưa có ai đăng ký.</p>
                        <?php else: foreach ($requests_by_post[$pid]['all'] as $rq): 
                            $is_approved = in_array($rq['status'], ['approved','1']);
                            $is_rejected = in_array($rq['status'], ['rejected','cancel','2']);
                        ?>
                            <div class="req-card" style="<?= $is_rejected ? 'opacity:0.6' : '' ?>">
                                <div>
                                    <div style="font-weight:700; font-size:14px; <?= $is_approved?'color:green':($is_rejected?'color:red':'') ?>">
                                        <?= e($rq['_name']) ?> 
                                        <?= $is_rejected ? '(Đã từ chối)' : '' ?>
                                    </div>
                                    <div style="font-size:12px; color:#666"><?= e($rq['_phone']) ?></div>
                                </div>
                                <div style="display:flex; gap:5px">
                                    <a href="view_request.php?id=<?= $rq['id'] ?>" class="btn" style="padding:4px 8px; font-size:11px">Xem đơn</a>
                                    <?php if (!$is_approved && !$is_rejected): ?>
                                        <a href="rent_action.php?action=approve&id=<?= $pid ?>&req_id=<?= $rq['id'] ?>" class="btn btn-primary" style="padding:4px 8px; font-size:11px">Duyệt</a>
                                        <a href="rent_action.php?action=reject&id=<?= $pid ?>&req_id=<?= $rq['id'] ?>" class="btn btn-danger" style="padding:4px 8px; font-size:11px" onclick="return confirm('Từ chối khách này?')">X</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; 
        if ($count === 0) echo '<p style="text-align:center; color:#999; padding:40px;">Không có phòng nào trong mục này.</p>';
        ?>
    </div>
    <?php endforeach; ?>
</div>

<script>
function switchTab(evt, tabId) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    evt.currentTarget.classList.add('active');
}
function toggleReq(pid) {
    const box = document.getElementById('req-box-' + pid);
    box.style.display = (box.style.display === 'block') ? 'none' : 'block';
}
</script>
</body>
</html>