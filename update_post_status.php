<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

if (!isset($_POST['post_id'], $_POST['action'])) {
    die("Missing data");
}

$post_id = intval($_POST['post_id']);
$action  = trim($_POST['action']);

$status_map = [
    "approve"    => "approved",   // admin duyệt bài đăng
    "reject"     => "rejected",   // admin từ chối bài đăng
    "unapprove"  => "pending",    // admin chuyển lại pending

    // Chủ phòng sử dụng:
    "rent"       => "rented",     // khi chủ phòng duyệt yêu cầu thuê
    "available"  => "available"   // khi phòng hết hạn & đăng lại
];

if (!isset($status_map[$action])) {
    die("Invalid action");
}

$new_status = $status_map[$action];

$stmt = $pdo->prepare("UPDATE posts SET status=? WHERE id=?");
$stmt->execute([$new_status, $post_id]);

echo "ok";
exit;
?>
