<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../header.php';

// Kiểm tra quyền admin
if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    echo 'Không có quyền';
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$action = $_GET['action'] ?? '';

if ($id > 0 && in_array($action, ['approve', 'reject'])) {

    // Kiểm tra bài có tồn tại
    $check = $pdo->prepare("SELECT id FROM posts WHERE id = ?");
    $check->execute([$id]);

    if ($check->fetch()) {

        $status = ($action === 'approve') ? 'approved' : 'rejected';

        $stmt = $pdo->prepare("
            UPDATE posts
            SET status = ?, reviewed_by = ?, reviewed_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$status, $_SESSION['user']['id'], $id]);
    }
}

header("Location: manage_posts.php");
exit;
?>
