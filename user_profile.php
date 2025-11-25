<?php
require 'db.php';
session_start();

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("ID không hợp lệ.");
}

// Lấy thông tin từ bảng users
$st = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$st->execute([$id]);
$user = $st->fetch();

// Lấy thông tin thuê mới nhất từ rental_requests
$rt = $pdo->prepare("
    SELECT *
    FROM rental_requests
    WHERE tenant_id = ?
    ORDER BY created_at DESC
    LIMIT 1
");
$rt->execute([$id]);
$rent = $rt->fetch();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin người thuê</title>
</head>
<body>

<h2>Thông tin người thuê</h2>

<p><strong>Tên:</strong> <?= htmlspecialchars($user['name']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
<p><strong>Số điện thoại:</strong> <?= htmlspecialchars($user['phone']) ?></p>

<hr>

<h3>Thông tin đăng ký thuê</h3>

<?php if ($rent): ?>
    <p><strong>Họ tên đầy đủ:</strong> <?= htmlspecialchars($rent['fullname']) ?></p>
    <p><strong>Ngày sinh:</strong> <?= htmlspecialchars($rent['birthday']) ?></p>
    <p><strong>Gmail:</strong> <?= htmlspecialchars($rent['gmail']) ?></p>
    <p><strong>CCCD:</strong> <?= htmlspecialchars($rent['cccd']) ?></p>
    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($rent['address']) ?></p>
    <p><strong>Ngày tạo yêu cầu:</strong> <?= htmlspecialchars($rent['created_at']) ?></p>
    <p><strong>Trạng thái:</strong> <?= htmlspecialchars($rent['status']) ?></p>
<?php else: ?>
    <p>Người này chưa gửi yêu cầu thuê.</p>
<?php endif; ?>

<p><a href="manage_rooms.php">Quay lại</a></p>

</body>
</html>
