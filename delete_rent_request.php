<?php
require 'db.php';
session_start();

/* ===============================
   KIỂM TRA ĐĂNG NHẬP
================================ */
if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit;
}

$uid = $_SESSION['user']['id'];
$id  = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("
        DELETE FROM rental_requests 
        WHERE id = :id AND user_id = :uid
    ");
    $stmt->execute([
        'id'  => $id,
        'uid' => $uid
    ]);
}

/* ===============================
   QUAY LẠI TRANG TRƯỚC
================================ */
$back = $_SERVER['HTTP_REFERER'] ?? '/hiihi/';
header("Location: $back");
exit;
