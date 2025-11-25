<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'db.php';
if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$id = (int)($_GET['id'] ?? 0);
$userId = (int)$_SESSION['user_id'];
$del = $pdo->prepare("DELETE FROM listings WHERE id = ? AND user_id = ?");
$del->execute([$id, $userId]);
header('Location: dashboard.php');
exit;
