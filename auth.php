<?php
session_start();
require 'db.php';
require 'functions.php';

/* ------------------------------------------------------
   TẠO CỘT NẾU CHƯA CÓ (MariaDB không hỗ trợ IF NOT EXISTS)
------------------------------------------------------- */
function addColumnIfMissing($pdo, $table, $column, $type) {
    $check = $pdo->prepare("
        SELECT COUNT(*) 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
          AND TABLE_NAME = ? 
          AND COLUMN_NAME = ?
    ");
    $check->execute([$table, $column]);

    if ($check->fetchColumn() == 0) {
        $pdo->exec("ALTER TABLE `$table` ADD `$column` $type");
    }
}

addColumnIfMissing($pdo, 'users', 'role', "VARCHAR(20) DEFAULT 'renter'");
addColumnIfMissing($pdo, 'users', 'status', "VARCHAR(20) DEFAULT 'active'");
addColumnIfMissing($pdo, 'users', 'is_admin', "TINYINT(1) DEFAULT 0");
addColumnIfMissing($pdo, 'users', 'avatar', "VARCHAR(255) DEFAULT NULL");
addColumnIfMissing($pdo, 'users', 'phone', "VARCHAR(20) DEFAULT NULL");

/* ------------------------------------------------------
   TỰ TẠO TÀI KHOẢN ADMIN NẾU CHƯA CÓ
------------------------------------------------------- */
$checkAdmin = $pdo->query("SELECT id FROM users WHERE email='admin@local' LIMIT 1")->fetch();
if (!$checkAdmin) {
    $hash = password_hash("admin123", PASSWORD_DEFAULT);

    $pdo->prepare("
        INSERT INTO users (name,email,password,role,is_admin,status)
        VALUES ('Admin','admin@local', ?, 'admin', 1, 'active')
    ")->execute([$hash]);
}

$mode = $_GET['mode'] ?? 'login';
$err = '';

/* ------------------------------------------------------
   XỬ LÝ ĐĂNG NHẬP / ĐĂNG KÝ
------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';

    /* ---------------------- ĐĂNG KÝ ---------------------- */
    if ($mode === 'register') {

        $role = $_POST['role'] ?? 'renter';

        $check = $pdo->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $check->execute([$email]);

        if ($check->fetch()) {
            $err = "Email này đã được sử dụng.";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO users (name,email,password,role,is_admin,status)
                VALUES (?,?,?,?,0,'active')
            ");
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $role]);

            header("Location: auth.php?mode=login");
            exit;
        }

    /* ---------------------- ĐĂNG NHẬP ---------------------- */
    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $stmt->execute([$email]);
        $u = $stmt->fetch();

        if ($u && password_verify($password, $u['password'])) {

            if ($u['status'] === 'banned') {
                $err = "⚠️ Tài khoản đã bị khóa.";
            } else {

                $_SESSION['user'] = [
                    'id'       => $u['id'],
                    'name'     => $u['name'],
                    'email'    => $u['email'],
                    'phone'    => $u['phone'],
                    'avatar'   => $u['avatar'],
                    'role'     => $u['role'],
                    'is_admin' => $u['is_admin']
                ];

                header("Location: index.php");
                exit;
            }

        } else {
            $err = "✖ Sai email hoặc mật khẩu.";
        }
    }
}

require 'header.php';
?>
<main class="max-w-md mx-auto p-4">
  <div class="bg-white p-4 rounded shadow">

    <?php if ($mode === 'register'): ?>

      <h3 class="text-lg font-semibold mb-3">Đăng ký</h3>

      <?php if ($err): ?><p class="text-red-600 mb-2"><?= $err ?></p><?php endif; ?>

      <form method="post">
        <div class="mb-2">
          <label class="block text-sm">Tên</label>
          <input name="name" required class="p-2 border rounded w-full">
        </div>

        <div class="mb-2">
          <label class="block text-sm">Vai trò</label>
          <label><input type="radio" name="role" value="landlord"> Chủ nhà</label>
          <label><input type="radio" name="role" value="renter" checked> Người thuê</label>
        </div>

        <div class="mb-2">
          <label class="block text-sm">Email</label>
          <input name="email" type="email" required class="p-2 border rounded w-full">
        </div>

        <div class="mb-2">
          <label class="block text-sm">Mật khẩu</label>
          <input name="password" type="password" required class="p-2 border rounded w-full">
        </div>

        <button class="px-3 py-2 bg-green-600 text-white rounded">Đăng ký</button>
      </form>

      <p class="mt-2 text-sm">Đã có tài khoản? <a href="auth.php?mode=login">Đăng nhập</a></p>

    <?php else: ?>

      <h3 class="text-lg font-semibold mb-3">Đăng nhập</h3>

      <?php if ($err): ?><p class="text-red-600 mb-2"><?= $err ?></p><?php endif; ?>

      <form method="post">
        <div class="mb-2">
          <label class="block text-sm">Email</label>
          <input name="email" type="email" required class="p-2 border rounded w-full">
        </div>

        <div class="mb-2">
          <label class="block text-sm">Mật khẩu</label>
          <input name="password" type="password" required class="p-2 border rounded w-full">
        </div>

        <button class="px-3 py-2 bg-blue-600 text-white rounded">Đăng nhập</button>
      </form>

      <p class="mt-2 text-sm">Chưa có tài khoản? <a href="auth.php?mode=register">Đăng ký</a></p>

    <?php endif; ?>

  </div>
</main>

</body>
</html>
