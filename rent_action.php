<?php
session_start();
require 'db.php';

/* ================= KIỂM TRA ĐĂNG NHẬP ================= */
if (!isset($_SESSION['user']['id'])) {
    header("Location: auth.php?mode=login");
    exit;
}

/* ================= LẤY THAM SỐ ================= */
$action  = $_GET['action'] ?? '';
$post_id = intval($_GET['id'] ?? 0);
$req_id  = intval($_GET['req_id'] ?? 0);

if ($post_id <= 0) {
    die("ID phòng không hợp lệ.");
}

/* ================= LẤY YÊU CẦU THUÊ ================= */
if ($action !== 'reset') {
    $stmt = $pdo->prepare("SELECT * FROM rental_requests WHERE id = ?");
    $stmt->execute([$req_id]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        die("Không tìm thấy yêu cầu.");
    }
}

/* ================= DUYỆT YÊU CẦU ================= */
if ($action === 'approve') {

    // Yêu cầu này → approved
    $pdo->prepare("
        UPDATE rental_requests 
        SET status = 'approved' 
        WHERE id = ?
    ")->execute([$req_id]);

    // Các yêu cầu khác → rejected
    $pdo->prepare("
        UPDATE rental_requests 
        SET status = 'rejected' 
        WHERE post_id = ? AND id != ?
    ")->execute([$post_id, $req_id]);

    // Phòng → đang thuê
    $pdo->prepare("
        UPDATE posts 
        SET status_rent = 1 
        WHERE id = ?
    ")->execute([$post_id]);

    header("Location: manage_rooms.php");
    exit;
}

/* ================= TỪ CHỐI ================= */
if ($action === 'reject') {

    $pdo->prepare("
        UPDATE rental_requests 
        SET status = 'rejected' 
        WHERE id = ?
    ")->execute([$req_id]);

    header("Location: manage_rooms.php");
    exit;
}

/* ================= TRẢ PHÒNG ================= */
if ($action === 'reset') {

    // Tất cả yêu cầu → rejected
    $pdo->prepare("
        UPDATE rental_requests 
        SET status = 'rejected' 
        WHERE post_id = ?
    ")->execute([$post_id]);

    // Phòng trống lại
    $pdo->prepare("
        UPDATE posts 
        SET status_rent = 0 
        WHERE id = ?
    ")->execute([$post_id]);

    header("Location: manage_rooms.php");
    exit;
}
