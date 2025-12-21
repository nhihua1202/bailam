<?php
require_once 'db.php';
require_once 'header.php';

if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    echo 'Không có quyền';
    exit;
}

$id = intval($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';

if ($id && in_array($action, ['approve', 'reject'])) {

    // Kiểm tra bài có tồn tại
    $check = $pdo->prepare("SELECT id FROM posts WHERE id = ?");
    $check->execute([$id]);

    if ($check->rowCount() > 0) {
        $status = ($action === 'approve') ? 'approved' : 'rejected';

        $stmt = $pdo->prepare("
            UPDATE posts 
            SET status = ?, reviewed_by = ?, reviewed_at = NOW() 
            WHERE id = ?
        ");
        $stmt->execute([$status, $_SESSION['user']['id'], $id]);
    }
}

// 🔥 Trả về đúng trang quản trị bài đăng
header("Location: /hiihi/admin/manage_posts.php");
exit;
?>
