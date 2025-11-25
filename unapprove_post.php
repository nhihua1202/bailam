<?php
require_once "../db.php";
require_once "../functions.php";

if (!isAdmin()) {
    die("NO ACCESS");
}

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("UPDATE posts SET status = 'rejected' WHERE id = ?");
$stmt->execute([$id]);

header("Location: manage_posts.php?msg=rejected");
exit;
