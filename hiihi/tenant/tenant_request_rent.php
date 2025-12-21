<?php
// tenant/request_rent.php
require __DIR__ . '/../db.php';
require __DIR__ . '/../functions.php';
if (empty(\$_SESSION['user_id'])) {
    header('Location: /auth.php?mode=login');
    exit;
}
$post_id = intval($_GET['post_id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO rental_requests (post_id, tenant_id, fullname, dob, phone, email, cccd, address, message, status) VALUES (?,?,?,?,?,?,?,?,?, "new")');
    $stmt->execute([
        $_POST['post_id'],
        $_SESSION['user_id'],
        $_POST['fullname'],
        $_POST['dob'],
        $_POST['phone'],
        $_POST['email'],
        $_POST['cccd'],
        $_POST['address'],
        $_POST['message']
    ]);
    header('Location: /posts.php?requested=1');
    exit;
}
?>
<form method="post">
  <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post_id); ?>">
  <label>Họ tên <input name="fullname" required></label><br>
  <label>Ngày sinh <input type="date" name="dob"></label><br>
  <label>SĐT <input name="phone" required></label><br>
  <label>Email <input name="email"></label><br>
  <label>CCCD <input name="cccd"></label><br>
  <label>Quê quán <input name="address"></label><br>
  <label>Ghi chú <textarea name="message"></textarea></label><br>
  <button type="submit">Gửi đơn xin thuê</button>
</form>
