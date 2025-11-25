<?php
require_once 'db.php';
require_once 'header.php';
if (empty($_SESSION['user'])) { header('Location: auth.php'); exit; }
$u = $_SESSION['user'];
$post_id = intval($_GET['post_id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO rental_requests (post_id, tenant_id, fullname, dob, phone, email, cccd, address, message, status) VALUES (?,?,?,?,?,?,?,?,?, "new")');
    $stmt->execute([$post_id, $u['id'], $_POST['fullname'], $_POST['dob'], $_POST['phone'], $_POST['email'], $_POST['cccd'], $_POST['address'], $_POST['message']]);
    header('Location: index.php?requested=1'); exit;
}
?>
<form method="post" class="max-w-xl mx-auto p-4">
  <h2 class="text-xl font-semibold mb-4">Đơn xin thuê phòng</h2>
  <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
  <label class="block mb-2">Họ tên<input name="fullname" required class="w-full p-2 border rounded" /></label>
  <label class="block mb-2">Ngày sinh<input type="date" name="dob" class="w-full p-2 border rounded" /></label>
  <label class="block mb-2">SĐT<input name="phone" required class="w-full p-2 border rounded" /></label>
  <label class="block mb-2">Email<input name="email" class="w-full p-2 border rounded" /></label>
  <label class="block mb-2">CCCD<input name="cccd" class="w-full p-2 border rounded" /></label>
  <label class="block mb-2">Quê quán<input name="address" class="w-full p-2 border rounded" /></label>
  <label class="block mb-2">Ghi chú<textarea name="message" class="w-full p-2 border rounded"></textarea></label>
  <button class="px-4 py-2 bg-blue-600 text-white rounded">Gửi đơn</button>
</form>
