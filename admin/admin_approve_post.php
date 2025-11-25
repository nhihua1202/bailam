<?php
session_start();
require __DIR__ . '/../db.php';
require __DIR__ . '/../functions.php';

// Kiểm tra quyền admin
if (!isAdmin()) {
    http_response_code(403);
    echo 'Bạn không có quyền truy cập.';
    exit;
}

$post_id = intval($_GET['id'] ?? 0);
$status  = $_GET['status'] ?? '';

// Validate
if (!$post_id || !in_array($status, ['accepted','rejected'])) {
    echo "Tham số không hợp lệ.";
    exit;
}

// Cập nhật bài đăng
$stmt = $pdo->prepare("
    UPDATE posts 
    SET 
        status = ?, 
        reviewed_by = ?, 
        reviewed_at = NOW() 
    WHERE id = ?
");

$stmt->execute([
    $status,
    $_SESSION['user']['id'],
    $post_id
]);

// CHUYỂN HƯỚNG ĐÚNG — KHÔNG DÙNG /admin/
header('Location: posts.php?msg=ok');
exit;
