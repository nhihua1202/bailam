<?php
require 'db.php';
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: auth.php?mode=login");
    exit;
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("Yêu cầu không hợp lệ.");
}

// Lấy dữ liệu yêu cầu từ bảng rental_requests
$st = $pdo->prepare("
    SELECT rr.*, p.title AS room_title
    FROM rental_requests rr
    LEFT JOIN posts p ON rr.post_id = p.id
    WHERE rr.id = ?
    LIMIT 1
");
$st->execute([$id]);
$req = $st->fetch();

if (!$req) {
    die("Không tìm thấy yêu cầu.");
}

// Hàm xử lý giá trị rỗng
function val($x) {
    return $x ? htmlspecialchars($x) : "Chưa cung cấp";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết yêu cầu thuê</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5;
            padding: 30px; 
        }
        .card {
            width: 500px;
            padding: 25px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin: auto;
        }
        h2 { 
            text-align: center;
            margin-bottom: 25px;
        }
        p { 
            margin: 8px 0; 
            font-size: 15px;
        }
        strong {
            color: #222;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #3498db;
            font-size: 16px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Chi tiết yêu cầu thuê</h2>

<div class="card">
    <p><strong>Phòng:</strong> <?= val($req['room_title']) ?></p>

    <p><strong>Họ tên:</strong> <?= val($req['fullname']) ?></p>
    <p><strong>Email:</strong> <?= val($req['gmail']) ?></p>
    <p><strong>Số điện thoại:</strong> <?= val($req['phone']) ?></p>

    <p><strong>Địa chỉ cư trú:</strong> <?= val($req['address']) ?></p>
    <p><strong>Ngày sinh:</strong> <?= val($req['birthday']) ?></p>
    <p><strong>CCCD:</strong> <?= val($req['cccd']) ?></p>

    <p><strong>Trạng thái:</strong> <?= val($req['status']) ?></p>
    <p><strong>Ngày gửi:</strong> <?= val($req['created_at']) ?></p>
</div>

<a href="manage_rooms.php">← Quay lại</a>

</body>
</html>
