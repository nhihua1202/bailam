<?php
session_start();
require __DIR__ . '/../db.php';
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}
$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) { echo 'Invalid id'; exit; }
$upd = $pdo->prepare("UPDATE listings SET status='approved' WHERE id = ?");
$upd->execute([$id]);
header('Location: dashboard.php');
exit;
