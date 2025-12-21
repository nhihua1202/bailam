<?php
require 'header.php';
require 'db.php';
require 'functions.php';

if (!isset($_SESSION['user'])) {
    header("Location: auth.php?mode=login");
    exit;
}

$post_id = intval($_GET['id'] ?? 0);

/* =====================================================
   LẤY THÔNG TIN BÀI ĐĂNG
===================================================== */
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    echo "<main class='max-w-4xl mx-auto p-4'>Không tìm thấy phòng.</main>";
    exit;
}

/* =====================================================
   LẤY DANH SÁCH YÊU CẦU THUÊ
===================================================== */

$req = $pdo->prepare("
    SELECT rr.*, u.name AS tenant_name, u.phone AS tenant_phone, u.email AS tenant_email
    FROM rental_requests rr
    JOIN users u ON rr.tenant_id = u.id
    WHERE rr.post_id = ?
    ORDER BY rr.id DESC
");
$req->execute([$post_id]);
$requests = $req->fetchAll();

/* =====================================================
   LẤY NGƯỜI ĐANG THUÊ (nếu có)
===================================================== */
$current = $pdo->prepare("
    SELECT rr.*, u.name AS tenant_name, u.phone AS tenant_phone 
    FROM rental_requests rr
    JOIN users u ON rr.tenant_id = u.id
    WHERE rr.post_id = ? AND rr.status = 'approved'
    LIMIT 1
");
$current->execute([$post_id]);
$current_renter = $current->fetch();
?>

<main class="max-w-4xl mx-auto p-4">

    <h2 class="text-2xl font-bold mb-4"><?= esc($post['title']) ?></h2>

    <!-- THÔNG TIN PHÒNG -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <p><strong>Giá:</strong> <?= number_format($post['price'], 1) ?> triệu</p>
        <p><strong>Khu vực:</strong> <?= esc($post['khu_vuc']) ?></p>
        <p><strong>Mô tả:</strong><br><?= nl2br(esc($post['description'])) ?></p>
    </div>

    <!-- NGƯỜI ĐANG THUÊ -->
    <h3 class="text-xl font-semibold mb-2">Người đang thuê</h3>
    <?php if ($current_renter): ?>
        <div class="p-3 border rounded bg-green-50 mb-6">
            <p><strong>Họ tên:</strong> <?= esc($current_renter['tenant_name']) ?></p>
            <p><strong>SĐT:</strong> <?= esc($current_renter['tenant_phone']) ?></p>
            <p><strong>Email:</strong> <?= esc($current_renter['gmail']) ?></p>
            <p><strong>CCCD:</strong> <?= esc($current_renter['cccd']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= esc($current_renter['address']) ?></p>
            <p><strong>Bắt đầu thuê:</strong> <?= esc($current_renter['created_at']) ?></p>
        </div>
    <?php else: ?>
        <p class="text-gray-600 mb-6">Phòng chưa có người thuê.</p>
    <?php endif; ?>

    <!-- DANH SÁCH YÊU CẦU -->
    <h3 class="text-xl font-semibold mb-3">Danh sách yêu cầu thuê</h3>

    <?php if (empty($requests)): ?>
        <p class="text-gray-600">Chưa có yêu cầu thuê.</p>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($requests as $r): ?>
                <div class="p-3 border rounded bg-gray-50">
                    <p><strong>Người gửi:</strong> <?= esc($r['tenant_name']) ?></p>
                    <p><strong>SĐT:</strong> <?= esc($r['tenant_phone']) ?></p>
                    <p><strong>Email:</strong> <?= esc($r['gmail']) ?></p>
                    <p><strong>CCCD:</strong> <?= esc($r['cccd']) ?></p>
                    <p><strong>Địa chỉ:</strong> <?= esc($r['address']) ?></p>
                    <p><strong>Trạng thái:</strong> 
                        <?php if ($r['status'] === 'pending'): ?>
                            <span class="text-yellow-600 font-semibold">Chờ duyệt</span>
                        <?php elseif ($r['status'] === 'approved'): ?>
                            <span class="text-green-600 font-semibold">Đã duyệt</span>
                        <?php else: ?>
                            <span class="text-red-600 font-semibold">Từ chối</span>
                        <?php endif; ?>
                    </p>
                    <p><strong>Gửi lúc:</strong> <?= esc($r['created_at']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
