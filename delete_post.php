<?php require 'db.php'; session_start();
$id = intval($_GET['id'] ?? 0);
if ($id) {
    // delete images
    $stmt = $pdo->prepare('SELECT filename FROM post_images WHERE post_id=?');
    $stmt->execute([$id]);
    $rows = $stmt->fetchAll();
    foreach($rows as $r) @unlink(__DIR__.'/uploads/'.$r['filename']);
    $pdo->prepare('DELETE FROM post_images WHERE post_id=?')->execute([$id]);
    $pdo->prepare('DELETE FROM posts WHERE id=?')->execute([$id]);
}
header('Location: my_posts.php'); exit;