<?php
require __DIR__ . '/../db.php';
require __DIR__ . '/../functions.php';
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit;
}

$user = $_SESSION['user'];

// Chỉ chủ phòng được duyệt yêu cầu
if ($user['role'] !== 'owner') {
    die("Bạn không có quyền duyệt yêu cầu.");
}

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ID yêu cầu không hợp lệ.");
}

// Cập nhật trạng thái yêu cầu
$stmt = $conn->prepare("UPDATE rent_requests SET status = 'approved' WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: manage_rent.php?msg=approved");
exit;
?>
