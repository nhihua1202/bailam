<?php
require_once "db.php";
require_once "functions.php";

$u = currentUser();
if (!$u || !isRenter()) {
    echo "Bạn không có quyền truy cập";
    exit;
}

$uid = $u['id'];

/* LẤY CÁC ĐƠN THUÊ PHÒNG */
$stmt = $pdo->prepare("
    SELECT r.*, p.title, p.address, p.price 
    FROM rent_requests r
    JOIN posts p ON p.id = r.post_id
    WHERE r.user_id = ?
    ORDER BY r.created_at DESC
");
$stmt->execute([$uid]);
$requests = $stmt->fetchAll();

/* LẤY PHÒNG ĐANG THUÊ (nếu có) */
$stmt = $pdo->prepare("
    SELECT t.*, p.title, p.address, p.price, p.id AS post_id
    FROM tenants t
    JOIN posts p ON p.id = t.post_id
    WHERE t.user_id = ? AND t.is_active = 1
");
$stmt->execute([$uid]);
$rented = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
<title>Quản lý phòng</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
<?php include "header.php"; ?>

<div class="max-w-4xl mx-auto mt-6">

<h2 class="text-2xl font-bold mb-4">Quản lý phòng</h2>

<!-- PHÒNG ĐANG THUÊ -->
<h3 class="text-xl font-semibold mt-4 mb-2">Phòng đang thuê</h3>

<?php if ($rented): ?>
<div class="p-4 bg-white rounded-lg shadow">
    <p class="font-bold text-lg"><?= esc($rented['title']) ?></p>
    <p>Địa chỉ: <?= esc($rented['address']) ?></p>
    <p>Giá thuê: <?= number_format($rented['price'], 0) ?> triệu</p>
    <p>Bắt đầu thuê: <?= $rented['start_date'] ?></p>
    <p>Hết hạn: <?= $rented['end_date'] ?></p>
</div>
<?php else: ?>
<p class="text-gray-500">Bạn chưa thuê phòng nào.</p>
<?php endif; ?>

<!-- ĐƠN ĐĂNG KÝ THUÊ -->
<h3 class="text-xl font-semibold mt-6 mb-2">Đơn đăng ký đã gửi</h3>

<?php foreach ($requests as $r): ?>
<div class="p-4 bg-white rounded-lg shadow mb-3">
  <p class="font-bold"><?= esc($r['title']) ?></p>
  <p>Địa chỉ: <?= esc($r['address']) ?></p>
  <p>Giá: <?= number_format($r['price'], 0) ?> triệu</p>

  <p class="mt-1">
    Trạng thái: 
    <?php if ($r['status'] == 'pending'): ?>
       <span class="text-yellow-600 font-semibold">Đang chờ duyệt</span>
    <?php elseif ($r['status'] == 'approved'): ?>
       <span class="text-green-600 font-semibold">Được chấp nhận</span>
    <?php else: ?>
       <span class="text-red-600 font-semibold">Bị từ chối</span>
    <?php endif; ?>
  </p>
</div>
<?php endforeach; ?>

</div>

</body>
</html>
