<?php
require 'header.php';
require 'db.php';
require 'functions.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: auth.php?mode=login");
    exit;
}

$u = $_SESSION['user'];

// Chỉ dành cho renter
if (($u['role'] ?? '') !== 'renter') {
    echo '<main class="max-w-3xl mx-auto p-4"><p class="text-red-600">Bạn không có quyền truy cập.</p></main>';
    exit;
}

// Lấy tất cả yêu cầu thuê của renter
$stmt = $pdo->prepare("
    SELECT r.*, 
           p.title AS post_title,
           p.price AS post_price,
           p.khu_vuc AS post_area
    FROM rent_requests r
    JOIN posts p ON r.post_id = p.id
    WHERE r.tenant_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$u['id']]);
$requests = $stmt->fetchAll();
?>

<main class="max-w-4xl mx-auto p-4">

<h2 class="text-2xl font-semibold mb-4">📌 Lịch sử thuê phòng của bạn</h2>

<?php if (empty($requests)): ?>
    <p class="p-4 bg-white rounded shadow">Bạn chưa gửi yêu cầu thuê phòng nào.</p>
<?php else: ?>

<div class="space-y-4">

<?php foreach ($requests as $r): ?>
    <div class="bg-white shadow rounded p-4">

        <!-- Tiêu đề trở thành nút xem chi tiết -->
        <h3 class="text-lg font-semibold mb-1">
            <a href="rent_request_detail.php?id=<?= $r['id'] ?>" 
               class="text-blue-600 hover:underline flex items-center">
               🏠 <?= esc($r['post_title']) ?>
            </a>
        </h3>

        <p class="text-gray-700">
            <strong>Khu vực:</strong> <?= esc($r['post_area']) ?><br>
            <strong>Giá:</strong> <?= esc($r['post_price']) ?> / tháng
        </p>

        <p class="mt-2 text-sm text-gray-600">
            <strong>Ngày gửi:</strong> <?= esc($r['created_at']) ?>
        </p>

        <p class="mt-2">
            <strong>Trạng thái:</strong>
            <?php if ($r['status'] === 'pending'): ?>
                <span class="text-yellow-600 font-semibold">⏳ Đang chờ duyệt</span>
            <?php elseif ($r['status'] === 'approved'): ?>
                <span class="text-green-600 font-semibold">✔️ Đã được duyệt</span>
            <?php else: ?>
                <span class="text-red-600 font-semibold">❌ Bị từ chối</span>
            <?php endif; ?>
        </p>

        <?php if ($r['status'] === 'approved'): ?>
            <p class="mt-2 text-green-700 font-semibold">🎉 Bạn đang thuê phòng này!</p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

</div>

<?php endif; ?>

</main>

</body>
</html>
