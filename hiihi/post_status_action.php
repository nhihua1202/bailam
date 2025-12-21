<?php
require 'db.php';
session_start();

/* Kiểm tra quyền admin */
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    die("Bạn không có quyền thực hiện thao tác này");
}

/* Chỉ chấp nhận POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$post_id = $_POST['post_id'] ?? null;
$action  = $_POST['action'] ?? null;

if (!$post_id || !$action) {
    die("Dữ liệu không hợp lệ");
}

switch ($action) {
    case 'approve':
        $status = 'approved';
        break;
    case 'reject':
        $status = 'rejected';
        break;
    case 'unapprove':
        $status = 'pending';
        break;
    default:
        die("Invalid action");
}

$stmt = $pdo->prepare("UPDATE posts SET status=? WHERE id=? LIMIT 1");
$stmt->execute([$status, $post_id]);

header("Location: manage_posts.php?msg=updated");
exit;
