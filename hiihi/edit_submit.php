<?php
require 'db.php';
session_start();
$id = intval($_POST['id'] ?? 0);
if (!$id) { header('Location: my_posts.php'); exit; }
$title = $_POST['title'] ?? '';
$khu_vuc = $_POST['khu_vuc'] ?? '';
$price = $_POST['price'] ?? '';
$phone = $_POST['phone'] ?? '';
$zalo = $_POST['zalo'] ?? '';
$description = $_POST['description'] ?? '';

$update = $pdo->prepare('UPDATE posts SET title=?, khu_vuc=?, price=?, phone=?, zalo=?, description=?, status=? WHERE id=?');
// edits set status back to pending for re-approval
$update->execute([$title,$khu_vuc,$price,$phone,$zalo,$description,'pending',$id]);

// delete selected images
if (!empty($_POST['delete_images'])) {
    $to_delete = $_POST['delete_images'];
    $in = implode(',', array_fill(0,count($to_delete),'?'));
    $stmt = $pdo->prepare('SELECT filename FROM post_images WHERE id IN ('.$in.')');
    $stmt->execute($to_delete);
    $rows = $stmt->fetchAll();
    foreach($rows as $r) {
        @unlink(__DIR__.'/uploads/'.$r['filename']);
    }
    $del = $pdo->prepare('DELETE FROM post_images WHERE id IN ('.$in.')');
    $del->execute($to_delete);
}

// add new images
if (!empty($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
    $uploadDir = __DIR__ . '/uploads/';
    for ($i=0;$i<count($_FILES['images']['name']);$i++) {
        if (empty($_FILES['images']['name'][$i])) continue;
        $tmp = $_FILES['images']['tmp_name'][$i];
        $name = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($_FILES['images']['name'][$i]));
        $target = $uploadDir . time() . '_' . $name;
        if (move_uploaded_file($tmp, $target)) {
            $fname = basename($target);
            $stmt = $pdo->prepare('INSERT INTO post_images (post_id, filename) VALUES (?,?)');
            $stmt->execute([$id, $fname]);
        }
    }
}

header('Location: my_posts.php');
exit;
?>