<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'header.php';
require 'db.php';
?>
<main class="max-w-3xl mx-auto p-6">
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold text-blue-600">Đăng nhập</h2>
    <?php
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email === '' || $password === '') $err = 'Vui lòng nhập email và mật khẩu.';
    else {
        $stmt = $pdo->prepare("SELECT id, password_hash, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header('Location: dashboard.php');
            exit;
        } else {
            $err = 'Email hoặc mật khẩu không đúng.';
        }
    }
}
?>
<?php if ($err): ?><div class="text-sm text-red-600 mb-3"><?=htmlspecialchars($err)?></div><?php endif; ?>
<form method="post">
  <label class="block text-sm">Email</label>
  <input name="email" type="email" required class="w-full p-2 border rounded mb-3">
  <label class="block text-sm">Mật khẩu</label>
  <input name="password" type="password" required class="w-full p-2 border rounded mb-4">
  <div class="flex justify-between items-center">
    <button class="px-4 py-2 rounded text-white bg-blue-500" type="submit">Đăng nhập</button>
    <div class="text-sm">
      <a href="register.php" class="underline">Đăng ký</a> |
      <a href="forgot.php" class="underline">Quên mật khẩu?</a>
    </div>
  </div>
</form>

  </div>
</main>
<?php include 'footer.php'; ?>
