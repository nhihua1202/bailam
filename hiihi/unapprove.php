<?php
require 'db.php';
session_start();

// --- Kiểm tra quyền admin ---
if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

// --- Lấy ID bài viết cần duyệt ---
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// --- Nếu ID hợp lệ thì cập nhật trạng thái ---
if ($id > 0) {
    $stmt = $pdo->prepare("UPDATE posts SET status = 'approved' WHERE id = ?");
    $stmt->execute([$id]);
}

// --- Quay lại trang quản lý tin ---
header('Location: admin.php');
exit;
?>
