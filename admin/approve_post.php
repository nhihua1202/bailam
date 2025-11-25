<?php
require __DIR__ . '/../db.php';
session_start();

// Kiểm tra xem người dùng có phải admin không
if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'] ?? 0;
$action = $_GET['action'] ?? '';

if ($id && in_array($action, ['approve', 'reject'])) {
    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE posts SET status='approved', reviewed_by=?, reviewed_at=NOW() WHERE id=?");
    } else {
        $stmt = $pdo->prepare("UPDATE posts SET status='rejected', reviewed_by=?, reviewed_at=NOW() WHERE id=?");
    }
    $stmt->execute([$_SESSION['user']['id'],$id]);
}

// Sau khi duyệt hoặc từ chối xong, quay lại trang quản lý tin
header('Location: manage_posts.php');
exit;
?>
