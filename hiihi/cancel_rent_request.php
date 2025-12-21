<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$requestId = intval($_GET['id'] ?? 0);

if ($requestId <= 0) {
    die("ID đơn đăng ký không hợp lệ.");
}

/*
  Chỉ cho phép:
  - Xóa đơn của CHÍNH MÌNH
  - Chỉ khi đơn đang PENDING
*/
$stmt = $pdo->prepare("
    DELETE FROM rental_requests
    WHERE id = :id
      AND user_id = :uid
      AND status = 'pending'
");

$stmt->execute([
    'id'  => $requestId,
    'uid' => $userId
]);

if ($stmt->rowCount() > 0) {
    // ✅ Xóa thành công → đơn biến mất
    header("Location: rent_requests.php?msg=delete_success");
} else {
    // ❌ Không có quyền hoặc đơn không còn pending
    header("Location: rent_requests.php?msg=delete_failed");
}
exit;
