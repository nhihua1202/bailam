<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'header.php';
require 'db.php';
if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$id = (int)($_GET['id'] ?? 0);
$userId = (int)$_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM listings WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $userId]);
$listing = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$listing) { header('Location: dashboard.php'); exit; }
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $price = (int)($_POST['price'] ?? 0);
    $address = trim($_POST['address'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    if ($title === '' || $price <= 0) $err = 'Vui lòng nhập tiêu đề và giá hợp lệ.';
    else {
        $upd = $pdo->prepare("UPDATE listings SET title=?, price=?, address=?, description=? WHERE id = ? AND user_id = ?");
        $upd->execute([$title, $price, $address, $desc, $id, $userId]);
        header('Location: dashboard.php');
        exit;
    }
}
?>
<main class="max-w-3xl mx-auto p-6">
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold text-blue-600">Sửa tin đăng</h2>
    <?php if ($err): ?><div class="text-sm text-red-600 mb-3"><?=htmlspecialchars($err)?></div><?php endif; ?>
    <form method="post">
      <label class="block text-sm">Tiêu đề</label>
      <input name="title" value="<?=htmlspecialchars($listing['title'])?>" class="w-full p-2 border rounded mb-3" required>
      <label class="block text-sm">Giá (VNĐ)</label>
      <input name="price" type="number" value="<?=htmlspecialchars($listing['price'])?>" class="w-full p-2 border rounded mb-3" required>
      <label class="block text-sm">Địa chỉ</label>
      <input name="address" value="<?=htmlspecialchars($listing['address'])?>" class="w-full p-2 border rounded mb-3">
      <label class="block text-sm">Mô tả</label>
      <textarea name="description" class="w-full p-2 border rounded mb-3"><?=htmlspecialchars($listing['description'])?></textarea>
      <div class="flex justify-between">
        <button class="px-4 py-2 rounded text-white bg-blue-500" type="submit">Lưu thay đổi</button>
        <a href="dashboard.php" class="underline">Hủy</a>
      </div>
    </form>
  </div>
</main>
<?php include 'footer.php'; ?>
