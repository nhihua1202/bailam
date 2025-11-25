<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: auth.php?mode=login');
    exit;
}

$user = $_SESSION['user'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST['old_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id=?");
    $stmt->execute([$user['id']]);
    $hash = $stmt->fetchColumn();

    if (!password_verify($old, $hash)) {
        $msg = "Mật khẩu cũ không đúng.";
    } elseif ($new !== $confirm) {
        $msg = "Mật khẩu mới không khớp.";
    } else {
        $newHash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->execute([$newHash, $user['id']]);
        $msg = "Đổi mật khẩu thành công!";
    }
}

require 'header.php';
?>
<main class="max-w-xl mx-auto p-4 bg-white rounded shadow">
  <h2 class="text-xl font-semibold mb-4">Đổi mật khẩu</h2>
  <?php if ($msg): ?><p class="text-red-600 mb-3"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label class="block text-sm font-medium">Mật khẩu cũ</label>
      <input type="password" name="old_password" required class="mt-1 p-2 border rounded w-full" />
    </div>
    <div class="mb-3">
      <label class="block text-sm font-medium">Mật khẩu mới</label>
      <input type="password" name="new_password" required class="mt-1 p-2 border rounded w-full" />
    </div>
    <div class="mb-3">
      <label class="block text-sm font-medium">Xác nhận mật khẩu mới</label>
      <input type="password" name="confirm_password" required class="mt-1 p-2 border rounded w-full" />
    </div>
    <button class="px-4 py-2 bg-green-600 text-white rounded">Cập nhật mật khẩu</button>
  </form>
</main>
</body>
</html>
