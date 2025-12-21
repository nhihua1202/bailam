<?php
require_once 'db.php';
require_once 'header.php';
if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'landlord') { echo 'Không có quyền'; exit; }
$u = $_SESSION['user'];
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']); $action = $_GET['action'];
    if ($action === 'accept') {
        $pdo->prepare('UPDATE rental_requests SET status = "accepted", updated_at = NOW() WHERE id = ?')->execute([$id]);
        $req = $pdo->query('SELECT post_id, tenant_id FROM rental_requests WHERE id = '.(int)$id)->fetch();
        if ($req) {
            $post_id = (int)$req['post_id']; $tenant_id = (int)$req['tenant_id'];
            $pdo->prepare('UPDATE posts SET is_rented = 1, owner_id = ? WHERE id = ?')->execute([$tenant_id, $post_id]);
            $pdo->prepare('INSERT INTO notifications (user_id, message) VALUES (?, ?)')->execute([$tenant_id, 'Yêu cầu thuê của bạn đã được chấp nhận']);
            $pdo->prepare('INSERT INTO notifications (user_id, message) VALUES (?, ?)')->execute([$u['id'], 'Bạn đã chấp nhận yêu cầu thuê #' . $id]);
        }
    } elseif ($action === 'reject') {
        $pdo->prepare('UPDATE rental_requests SET status = "rejected", updated_at = NOW() WHERE id = ?')->execute([$id]);
        $req = $pdo->query('SELECT tenant_id FROM rental_requests WHERE id = '.(int)$id)->fetch();
        if ($req) {
            $tenant_id = (int)$req['tenant_id'];
            $pdo->prepare('INSERT INTO notifications (user_id, message) VALUES (?, ?)')->execute([$tenant_id, 'Yêu cầu thuê của bạn đã bị từ chối']);
        }
    }
    header('Location: landlord_requests.php'); exit;
}
$stmt = $pdo->prepare('SELECT rr.*, p.title, u.name AS tenant_name FROM rental_requests rr JOIN posts p ON rr.post_id = p.id JOIN users u ON rr.tenant_id = u.id WHERE p.owner_id = ? ORDER BY rr.created_at DESC');
$stmt->execute([$u['id']]);
$requests = $stmt->fetchAll();
?>
<main class="max-w-4xl mx-auto p-4">
  <h2 class="text-xl font-semibold mb-4">Yêu cầu thuê phòng</h2>
  <table class="w-full">
    <tr><th>ID</th><th>Phòng</th><th>Người thuê</th><th>Trạng thái</th><th>Hành động</th></tr>
    <?php foreach($requests as $r): ?>
    <tr>
      <td><?= $r['id'] ?></td>
      <td><?= htmlspecialchars($r['title']) ?></td>
      <td><?= htmlspecialchars($r['tenant_name']) ?></td>
      <td><?= $r['status'] ?></td>
      <td>
        <?php if($r['status']==='new' || $r['status']==='pending'): ?>
          <a href="?action=accept&id=<?= $r['id'] ?>" class="px-2 py-1 bg-green-600 text-white rounded">Chấp nhận</a>
          <a href="?action=reject&id=<?= $r['id'] ?>" class="px-2 py-1 bg-red-500 text-white rounded">Từ chối</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</main>
