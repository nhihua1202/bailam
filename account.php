<?php
require 'header.php';
require 'db.php';
require 'functions.php';

if (!isset($_SESSION['user'])) {
    header('Location: auth.php?mode=login');
    exit;
}

$u = $_SESSION['user'];
$msg = '';
$error = '';

/* ========== ĐƯỜNG DẪN UPLOAD CHUẨN ========== */
$uploadDir = __DIR__ . "/uploads/";          // thư mục thật
$uploadWeb = "uploads/";                      // đường dẫn web

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    $password = trim($_POST['password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    /* ==================== AVATAR ==================== */
    $avatarName = $u['avatar'] ?? null;

    if (!empty($_FILES['avatar']['name'])) {

        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {

            $avatarName = "avatar_" . $u['id'] . "." . $ext;  
            $uploadPath = $uploadDir . $avatarName;

            move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath);
        }
    }

    /* =============== KHÔNG ĐỔI MẬT KHẨU =============== */
    if (!$new_password) {

        $stmt = $pdo->prepare("
            UPDATE users 
            SET name=?, phone=?, email=?, avatar=? 
            WHERE id=?
        ");
        $stmt->execute([$name, $phone, $email, $avatarName, $u['id']]);

        // cập nhật session
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['avatar'] = $avatarName;

        $msg = "✅ Cập nhật thông tin thành công!";
    }

    else {

        /* =============== CÓ ĐỔI MẬT KHẨU =============== */

        if ($new_password !== $confirm_password) {
            $error = "❌ Mật khẩu xác nhận không khớp.";
        } else {

            $stmt = $pdo->prepare("SELECT password FROM users WHERE id=? LIMIT 1");
            $stmt->execute([$u['id']]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($password, $user['password'])) {
                $error = "❌ Mật khẩu hiện tại không đúng.";
            } else {

                $hashed = password_hash($new_password, PASSWORD_BCRYPT);

                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET name=?, phone=?, email=?, avatar=?, password=? 
                    WHERE id=?
                ");
                $stmt->execute([$name, $phone, $email, $avatarName, $hashed, $u['id']]);

                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['phone'] = $phone;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['avatar'] = $avatarName;

                $msg = "✅ Đổi mật khẩu & cập nhật thông tin thành công!";
            }
        }
    }
}

?>

<main class="max-w-3xl mx-auto p-4">
  <h2 class="text-2xl font-semibold mb-4">👤 Quản lý tài khoản</h2>

  <?php if ($msg): ?>
    <div class="p-3 mb-3 bg-green-100 text-green-700 rounded"><?= $msg ?></div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="p-3 mb-3 bg-red-100 text-red-700 rounded"><?= $error ?></div>
  <?php endif; ?>


  <form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow space-y-4">

    <div class="flex items-center gap-4">
      <img src="<?= $uploadWeb . esc($_SESSION['user']['avatar'] ?? 'default.png') ?>" 
           class="w-20 h-20 rounded-full border object-cover">

      <div>
        <label class="block font-medium mb-1">Ảnh đại diện</label>
        <input type="file" name="avatar" class="p-2 border rounded w-full">
      </div>
    </div>

    <div>
      <label class="block font-medium mb-1">Họ và tên</label>
      <input type="text" name="name" 
             value="<?= esc($u['name'] ?? '') ?>" 
             class="w-full p-2 border rounded">
    </div>

    <div>
      <label class="block font-medium mb-1">Số điện thoại</label>
      <input type="text" name="phone" 
             value="<?= esc($u['phone'] ?? '') ?>" 
             class="w-full p-2 border rounded">
    </div>

    <div>
      <label class="block font-medium mb-1">Email</label>
      <input type="email" name="email" 
             value="<?= esc($u['email'] ?? '') ?>" 
             class="w-full p-2 border rounded">
    </div>

    <hr class="my-4">

    <button type="button" onclick="togglePassword()" 
            class="px-3 py-1 bg-gray-200 rounded border">
        🔒 Bật / tắt đổi mật khẩu
    </button>

    <div id="pwBox" style="display:none;">
      <div>
        <label class="block font-medium mb-1">Mật khẩu hiện tại</label>
        <input type="password" name="password" class="w-full p-2 border rounded">
      </div>

      <div>
        <label class="block font-medium mb-1">Mật khẩu mới</label>
        <input type="password" name="new_password" class="w-full p-2 border rounded">
      </div>

      <div>
        <label class="block font-medium mb-1">Xác nhận mật khẩu mới</label>
        <input type="password" name="confirm_password" class="w-full p-2 border rounded">
      </div>
    </div>

    <button type="submit" 
            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Lưu thay đổi
    </button>
  </form>
</main>

<script>
function togglePassword() {
    const box = document.getElementById("pwBox");
    box.style.display = box.style.display === "none" ? "block" : "none";
}
</script>

</body>
</html>
