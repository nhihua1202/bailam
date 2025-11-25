<?php
require 'db.php';
require 'functions.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit;
}

$user = $_SESSION['user'];

if ($user['role'] !== 'owner') {
    die("Bạn không có quyền xóa yêu cầu.");
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) die("ID yêu cầu không hợp lệ.");

$stmt = $conn->prepare("DELETE FROM rent_requests WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: manage_rent.php?msg=deleted");
exit;
?>
