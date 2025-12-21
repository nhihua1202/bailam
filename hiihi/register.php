<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'header.php';
require 'db.php';
?>
<main class="max-w-3xl mx-auto p-6">
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold text-blue-600">Tạo tài khoản</h2>
    <?php
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = in_array($_POST['role'] ?? '', ['landlord','tenant']) ? $_POST['role'] : 'tenant';
    if ($email === '' || $password === '') $err = 'Vui lòng nhập email và mật khẩu.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $err = 'Email không hợp lệ.';
    else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $err = 'Email này đã được sử dụng.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare("INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)");
            $ins->execute([$email, $hash, $role]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['role'] = $role;
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<?php if ($err): ?><div class="text-sm text-red-600 mb-3"><?=htmlspecialchars($err)?></div><?php endif; ?>
<form method="post">
  <label class="block text-sm">Email</label>
  <input name="email" type="email" required class="w-full p-2 border rounded mb-3">
  <label class="block text-sm">Mật khẩu (>=6 ký tự)</label>
  <input name="password" type="password" minlength="6" required class="w-full p-2 border rounded mb-3">
  <label class="block text-sm">Vai trò</label>
  <select name="role" class="w-full p-2 border rounded mb-4">
    <option value="landlord">Người cho thuê (landlord)</option>
    <option value="tenant" selected>Người thuê (tenant)</option>
  </select>
  <div class="flex justify-between items-center">
    <button class="px-4 py-2 rounded text-black bg-yellow-400" type="submit">Đăng ký</button>
    <a href="login.php" class="underline text-sm">Đã có tài khoản?</a>
  </div>
</form>

  </div>
</main>
<?php include 'footer.php'; ?>
