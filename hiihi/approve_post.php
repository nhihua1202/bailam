<?php
require "../db.php";
require "../functions.php";

if (!isAdmin()) exit;

$id = $_GET['id'] ?? 0;

$pdo->prepare("UPDATE posts SET status='approved' WHERE id=?")->execute([$id]);

header("Location: manage_posts.php");
exit;
