<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

// escape
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo '<main class="max-w-3xl mx-auto p-4"><p>❌ ID bài không hợp lệ.</p></main>';
    exit;
}

$u = $_SESSION['user'] ?? null;

/* ================= LẤY BÀI ================= */

// Lấy bài KHÔNG giới hạn status — để kiểm tra chủ bài/admin
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo '<main class="max-w-3xl mx-auto p-4"><p>❌ Không tìm thấy bài đăng.</p></main>';
    exit;
}

$isOwner = ($u && $u['id'] == $post['user_id']);
$isAdmin = (function_exists('isAdmin') && isAdmin());

// Nếu bài chưa duyệt → chỉ cho admin hoặc chủ xem
if ($post['status'] !== 'approved' && !$isOwner && !$isAdmin) {
    echo '<main class="max-w-3xl mx-auto p-4"><p>❌ Không tìm thấy bài hoặc bạn không có quyền xem bài này.</p></main>';
    exit;
}

/* ================= LẤY ẢNH ================= */

$imgsStmt = $pdo->prepare("SELECT filename FROM post_images WHERE post_id = ?");
$imgsStmt->execute([$id]);
$rows = $imgsStmt->fetchAll(PDO::FETCH_COLUMN);

$basePublic = '/hiihi';
$uploadsPublic = rtrim($basePublic, '/') . '/uploads/';
$defaultImg = rtrim($basePublic, '/') . '/assets/default.png';

$images = [];
foreach ($rows as $fn) {
    $clean = basename($fn);
    $disk = __DIR__ . '/uploads/' . $clean;

    if (is_file($disk))
        $images[] = $uploadsPublic . rawurlencode($clean);
    else
        $images[] = $defaultImg;
}
if (empty($images)) $images[] = $defaultImg;

require_once __DIR__ . '/header.php';
?>

<main class="max-w-4xl mx-auto p-4">
    <div class="bg-white p-4 rounded-xl shadow">

        <h2 class="text-2xl font-bold mb-1"><?= h($post['title']) ?></h2>
        <p class="text-gray-600 text-sm mb-3">
            <?= h($post['khu_vuc']) ?> — 
            <b><?= number_format($post['price'], 2) ?> triệu</b>
        </p>

        <!-- Ảnh -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 mb-4">
            <?php foreach ($images as $img): ?>
                <img src="<?= h($img) ?>" class="w-full h-40 object-cover rounded-lg border bg-gray-100">
            <?php endforeach; ?>
        </div>

        <!-- Mô tả -->
        <div class="text-gray-800 whitespace-pre-line mb-4">
            <?= nl2br(h($post['description'])) ?>
        </div>

        <!-- Liên hệ -->
        <div class="mb-4">
            <p>SĐT:
                <a href="tel:<?= h($post['phone']) ?>" class="text-blue-600 font-semibold">
                    <?= h($post['phone']) ?>
                </a>
            </p>
            <?php if (!empty($post['zalo'])): ?>
                <p>Zalo:
                    <a href="<?= h($post['zalo']) ?>" target="_blank" class="text-blue-600">Mở Zalo</a>
                </p>
            <?php endif; ?>
        </div>

        <!-- Nút Admin (ĐÃ XÓA NÚT TỪ CHỐI) -->
        <?php if ($isAdmin): ?>
            <div class="flex space-x-3 mb-4">
                <?php if ($post['status'] !== 'approved'): ?>
                    <a href="<?= $basePublic ?>/admin/approve_post.php?id=<?= $post['id'] ?>&action=approve"
                       class="px-4 py-2 bg-green-600 text-white rounded-lg">✔ Duyệt bài</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Nút thuê -->
        <div class="text-center mt-6">
            <?php if (!$u): ?>
                <a href="<?= $basePublic ?>/auth.php?mode=login"
                   class="px-4 py-2 bg-blue-600 text-white rounded">Đăng nhập để thuê phòng</a>

            <?php elseif ($isOwner): ?>
                <p class="text-yellow-600 font-bold">Bạn là chủ bài đăng này.</p>

            <?php else: ?>
                <a href="<?= $basePublic ?>/rent_submit.php?post_id=<?= $post['id'] ?>"
                   class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                   Thuê phòng
                </a>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
