<?php
require 'db.php';
require 'functions.php';

if (!isOwner()) {
    echo "Bạn không có quyền duyệt yêu cầu này!";
    exit;
}

if (!isset($_GET['id'])) {
    echo "Thiếu ID yêu cầu!";
    exit;
}

$request_id = intval($_GET['id']);

/* --- LẤY THÔNG TIN YÊU CẦU --- */
$stmt = $pdo->prepare("SELECT * FROM rental_requests WHERE id = :id");
$stmt->execute(['id' => $request_id]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
    echo "Không tìm thấy yêu cầu!";
    exit;
}

$post_id = $request['post_id'];
$tenant_id = $request['tenant_id'];

/* --- CẬP NHẬT TRẠNG THÁI YÊU CẦU: ACCEPTED --- */
$update = $pdo->prepare("
    UPDATE rental_requests
    SET status = 'accepted'
    WHERE id = :id
");
$update->execute(['id' => $request_id]);

/* --- ĐÁNH DẤU PHÒNG ĐANG ĐƯỢC THUÊ --- */
$update_post = $pdo->prepare("
    UPDATE posts
    SET status_rent = 1
    WHERE id = :pid
");
$update_post->execute(['pid' => $post_id]);

/* --- GỬI THÔNG BÁO --- */
$notify = $pdo->prepare("
    INSERT INTO notifications (user_id, content, link)
    VALUES (:uid, :content, :link)
");
$notify->execute([
    'uid' => $tenant_id,
    'content' => "Yêu cầu thuê phòng của bạn đã được chấp nhận!",
    'link' => "rent_current.php"
]);

header("Location: manage_requests.php?success=1");
exit;
?>
