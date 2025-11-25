<?php
require 'header.php';
require 'db.php';

if (!isset($_SESSION['user']['id'])) {
    header("Location: auth.php?mode=login");
    exit;
}

$action = $_GET['action'] ?? '';
$post_id = intval($_GET['id'] ?? 0);
$req_id = intval($_GET['req_id'] ?? 0);

if ($post_id <= 0) {
    die("ID phòng không hợp lệ.");
}

/* ----- Lấy thông tin yêu cầu thuê ----- */
$stmt = $pdo->prepare("SELECT * FROM rental_requests WHERE id = ?");
$stmt->execute([$req_id]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

/* Nếu không tồn tại yêu cầu */
if (!$request && $action !== 'reset') {
    die("Không tìm thấy yêu cầu.");
}

/* ----------------------------------------
   DUYỆT YÊU CẦU
---------------------------------------- */
if ($action === 'approve') {
    // Đổi trạng thái yêu cầu này → approved
    $pdo->prepare("UPDATE rental_requests SET status = 'approved' WHERE id = ?")
        ->execute([$req_id]);

    // Các yêu cầu khác → rejected
    $pdo->prepare("UPDATE rental_requests SET status = 'rejected' WHERE post_id = ? AND id != ?")
        ->execute([$post_id, $req_id]);

    // Phòng → đang thuê (status_rent = 1)
    $pdo->prepare("UPDATE posts SET status_rent = 1 WHERE id = ?")
        ->execute([$post_id]);

    header("Location: manage_rooms.php");
    exit;
}

/* ----------------------------------------
   TỪ CHỐI
---------------------------------------- */
if ($action === 'reject') {
    $pdo->prepare("UPDATE rental_requests SET status = 'rejected' WHERE id = ?")
        ->execute([$req_id]);

    header("Location: manage_rooms.php");
    exit;
}

/* ----------------------------------------
   TRẢ PHÒNG
---------------------------------------- */
if ($action === 'reset') {
    // Tất cả yêu cầu → rejected
    $pdo->prepare("UPDATE rental_requests SET status = 'rejected' WHERE post_id = ?")
        ->execute([$post_id]);

    // Phòng trống lại
    $pdo->prepare("UPDATE posts SET status_rent = 0 WHERE id = ?")
        ->execute([$post_id]);

    header("Location: manage_rooms.php");
    exit;
}

?>
