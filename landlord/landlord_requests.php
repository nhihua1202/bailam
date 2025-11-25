<?php
// landlord/requests.php
require __DIR__ . '/../db.php';
require __DIR__ . '/../functions.php';
if (empty(\$_SESSION['user_id'])) header('Location: auth.php?mode=login');
$u = $_SESSION['user'];
// select posts owned by landlord (owner_id) and their rental requests
$stmt = $pdo->prepare('SELECT rr.*, p.title, p.id AS post_id, u.name AS tenant_name FROM rental_requests rr JOIN posts p ON rr.post_id = p.id JOIN users u ON rr.tenant_id = u.id WHERE p.owner_id = ? ORDER BY rr.created_at DESC');
$stmt->execute([$u['id']]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// handle accept/reject
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    if ($action === 'accept') {
        $pdo->prepare('UPDATE rental_requests SET status = "accepted", updated_at = NOW() WHERE id = ?')->execute([$id]);
        // mark post as rented
        $r = $pdo->prepare('SELECT post_id FROM rental_requests WHERE id = ?')->execute([$id]);
        // set is_rented and optionally set owner_id
        // caution: above is illustrative; integrate as needed in your codebase
    } elseif ($action === 'reject') {
        $pdo->prepare('UPDATE rental_requests SET status = "rejected", updated_at = NOW() WHERE id = ?')->execute([$id]);
    }
    header('Location: /landlord/requests.php');
    exit;
}
?>
<h2>Yêu cầu thuê phòng</h2>
<table>
  <tr><th>ID</th><th>Phòng</th><th>Người thuê</th><th>Trạng thái</th><th>Hành động</th></tr>
  <?php foreach($requests as $r): ?>
  <tr>
    <td><?php echo $r['id']?></td>
    <td><?php echo htmlspecialchars($r['title'])?></td>
    <td><?php echo htmlspecialchars($r['tenant_name'])?></td>
    <td><?php echo $r['status']?></td>
    <td>
      <?php if($r['status']==='new'): ?>
        <a href="?action=accept&id=<?php echo $r['id']?>">Chấp nhận</a> |
        <a href="?action=reject&id=<?php echo $r['id']?>">Từ chối</a>
      <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
