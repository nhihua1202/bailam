<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'header.php';
require 'db.php';
$err = '';
$ok = '';
$token = $_GET['token'] ?? ($_POST['token'] ?? '');
if (!$token) { $err = 'Token không hợp lệ.'; }
else {
    $stmt = $pdo->prepare("SELECT id, reset_expires FROM users WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) $err = 'Token không hợp lệ.';
    elseif (strtotime($user['reset_expires']) < time()) $err = 'Token đã hết hạn.';
    else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pw = $_POST['password'] ?? '';
            $pw2 = $_POST['password2'] ?? '';
            if ($pw === '' || strlen($pw) < 6) $err = 'Mật khẩu mới phải >= 6 ký tự.';
            elseif ($pw !== $pw2) $err = 'Mật khẩu nhập lại không khớp.';
            else {
                $hash = password_hash($pw, PASSWORD_DEFAULT);
                $upd = $pdo->prepare("UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
                $upd->execute([$hash, $user['id']]);
                $ok = 'Đổi mật khẩu thành công. Bạn có thể <a href="login.php">đăng nhập</a>.';
            }
        }
    }
}
?>
<main class="max-w-3xl mx-auto p-6">
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold text-blue-600">Đặt lại mật khẩu</h2>
    <?php if ($err): ?><div class="text-sm text-red-600 mb-3"><?=htmlspecialchars($err)?></div><?php endif; ?>
    <?php if ($ok): ?><div class="text-sm text-green-700 mb-3"><?=$ok?></div><?php endif; ?>
    <?php if (!$ok && !$err): ?>
    <form method="post">
      <input type="hidden" name="token" value="<?=htmlspecialchars($token)?>">
      <label class="block text-sm">Mật khẩu mới</label>
      <input name="password" type="password" minlength="6" required class="w-full p-2 border rounded mb-3">
      <label class="block text-sm">Nhập lại mật khẩu</label>
      <input name="password2" type="password" minlength="6" required class="w-full p-2 border rounded mb-4">
      <div class="flex justify-between">
        <button class="px-4 py-2 rounded text-black bg-yellow-400" type="submit">Đặt lại</button>
        <a href="login.php" class="underline">Hủy</a>
      </div>
    </form>
    <?php endif; ?>
  </div>
</main>
<?php include 'footer.php'; ?>
