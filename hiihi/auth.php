<?php
session_start();
require 'db.php';
require 'functions.php';

/* ------------------------------------------------------
   TẠO CỘT NẾU CHƯA CÓ
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
   ADMIN MẶC ĐỊNH
------------------------------------------------------- */
$checkAdmin = $pdo->query("SELECT id FROM users WHERE email='admin@local' LIMIT 1")->fetch();
if (!$checkAdmin) {
    $pdo->prepare("
        INSERT INTO users (name,email,password,role,is_admin,status)
        VALUES ('Admin','admin@local', ?, 'admin', 1, 'active')
    ")->execute([password_hash('Admin@123', PASSWORD_DEFAULT)]);
}

$mode = $_GET['mode'] ?? 'login';
$err  = '';

/* GIỮ GIÁ TRỊ FORM */
$old_name  = $_POST['name']  ?? '';
$old_email = $_POST['email'] ?? '';
$old_role  = $_POST['role']  ?? 'renter';

/* ------------------------------------------------------
   XỬ LÝ FORM
------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($old_email);
    $name     = trim($old_name);
    $password = $_POST['password'] ?? '';

    /* ---------------------- ĐĂNG KÝ ---------------------- */
    if ($mode === 'register') {

        if ($name === '' || $email === '' || $password === '') {
            $err = "Vui lòng nhập đầy đủ thông tin.";
        }
        elseif (
            strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W_]/', $password)
        ) {
            $err = "Mật khẩu ≥ 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.";
        }
        else {
            $check = $pdo->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
            $check->execute([$email]);

            if ($check->fetch()) {
                $err = "Email này đã được sử dụng.";
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO users (name,email,password,role,is_admin,status)
                    VALUES (?,?,?,?,0,'active')
                ");
                $stmt->execute([
                    $name,
                    $email,
                    password_hash($password, PASSWORD_DEFAULT),
                    $old_role
                ]);

                header("Location: auth.php?mode=login");
                exit;
            }
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
    <input name="name" value="<?= esc($old_name) ?>" required
           class="p-2 border rounded w-full">
  </div>

  <div class="mb-2">
    <label class="block text-sm">Vai trò</label>
    <label>
      <input type="radio" name="role" value="landlord"
        <?= $old_role === 'landlord' ? 'checked' : '' ?>>
      Chủ nhà
    </label>
    <label class="ml-3">
      <input type="radio" name="role" value="renter"
        <?= $old_role === 'renter' ? 'checked' : '' ?>>
      Người thuê
    </label>
  </div>

  <div class="mb-2">
    <label class="block text-sm">Email</label>
    <input name="email" type="email" value="<?= esc($old_email) ?>" required
           class="p-2 border rounded w-full">
  </div>

  <div class="mb-2 relative">
    <label class="block text-sm">Mật khẩu</label>

    <input id="password" name="password" type="password" required
           class="p-2 border rounded w-full pr-12">

    <button type="button" onclick="togglePass()"
            class="absolute right-3 top-8 text-gray-500 hover:text-gray-800">
      <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg"
           class="h-5 w-5" fill="none" viewBox="0 0 24 24"
           stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2"
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2"
              d="M2.458 12C3.732 7.943 7.523 5 12 5
                 c4.478 0 8.268 2.943 9.542 7
                 -1.274 4.057-5.064 7-9.542 7
                 -4.477 0-8.268-2.943-9.542-7z"/>
      </svg>
    </button>

    <small class="text-gray-500">
      ≥ 8 ký tự, có hoa, thường, số, ký tự đặc biệt
    </small>
  </div>

  <button class="mt-2 px-3 py-2 bg-green-600 text-white rounded w-full">
    Đăng ký
  </button>
</form>

<p class="mt-2 text-sm text-center">
  Đã có tài khoản?
  <a class="text-blue-600" href="auth.php?mode=login">Đăng nhập</a>
</p>

<?php else: ?>

<h3 class="text-lg font-semibold mb-3">Đăng nhập</h3>
<?php if ($err): ?><p class="text-red-600 mb-2"><?= $err ?></p><?php endif; ?>

<form method="post">

  <div class="mb-2">
    <label class="block text-sm">Email</label>
    <input name="email" value="<?= esc($old_email) ?>" required
           class="p-2 border rounded w-full">
  </div>

  <div class="mb-2 relative">
    <label class="block text-sm">Mật khẩu</label>
    <input id="password" name="password" type="password" required
           class="p-2 border rounded w-full pr-12">
    <button type="button" onclick="togglePass()"
        class="absolute right-3 top-8 text-gray-500 hover:text-gray-800">
  <svg xmlns="http://www.w3.org/2000/svg"
       class="h-5 w-5" fill="none" viewBox="0 0 24 24"
       stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round"
          stroke-width="2"
          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    <path stroke-linecap="round" stroke-linejoin="round"
          stroke-width="2"
          d="M2.458 12C3.732 7.943 7.523 5 12 5
             c4.478 0 8.268 2.943 9.542 7
             -1.274 4.057-5.064 7-9.542 7
             -4.477 0-8.268-2.943-9.542-7z"/>
  </svg>
</button>
  </div>

  <button class="px-3 py-2 bg-blue-600 text-white rounded w-full">
    Đăng nhập
  </button>
</form>

<p class="mt-2 text-sm text-center">
  Chưa có tài khoản?
  <a class="text-green-600" href="auth.php?mode=register">Đăng ký</a>
</p>

<?php endif; ?>

</div>
</main>

<script>
function togglePass() {
    const p = document.getElementById('password');
    p.type = p.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>
