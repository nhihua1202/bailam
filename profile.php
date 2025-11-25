<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: auth.php?mode=login');
    exit;
}

$user = $_SESSION['user'];
$msg = "";

/* ----------------------------------------------------
   ĐƯỜNG DẪN CHUẨN – KHÔNG BAO GIỜ BỊ SAI NỮA
   ---------------------------------------------------- */
$root = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/') . '/';      // đường dẫn tuyệt đối
$publicBase = "uploads/avatars/";                                                 // đường dẫn public
$fullDir = $root . $publicBase;                                                   // đường dẫn thật trên server

// Tạo thư mục nếu chưa có
if (!is_dir($fullDir)) {
    mkdir($fullDir, 0777, true);
}


/* ----------------------------------------------------
   CẬP NHẬT THÔNG TIN
   ---------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name  = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $avatar_path = $user['avatar'] ?? null;

    /* --- Upload avatar mới --- */
    if (!empty($_FILES['avatar']['name'])) {

        $file = $_FILES['avatar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            $msg = "Chỉ chấp nhận JPG, PNG, WEBP!";
        } else {
            $newName = "avatar_" . $user['id'] . "_" . time() . "." . $ext;
            $fullPath = $fullDir . $newName;                 // lưu thật trên server
            $publicPath = $publicBase . $newName;            // dùng trong <img> và DB

            if (move_uploaded_file($file['tmp_name'], $fullPath)) {

                // Xóa avatar cũ nếu có
                if (!empty($avatar_path) && is_file($root . $avatar_path)) {
                    @unlink($root . $avatar_path);
                }

                $avatar_path = $publicPath;

            } else {
                $msg = "Không thể lưu ảnh upload!";
            }
        }
    }

    /* --- UPDATE DB --- */
    $stmt = $pdo->prepare("UPDATE users SET name=?, phone=?, email=?, avatar=? WHERE id=?");
    $stmt->execute([$name, $phone, $email, $avatar_path, $user['id']]);

    /* --- UPDATE SESSION --- */
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['avatar'] = $avatar_path;

    $user = $_SESSION['user'];

    if (!$msg) $msg = "Cập nhật thông tin thành công!";
}

require 'header.php';
?>

<main class="max-w-xl mx-auto p-4 bg-white rounded shadow">
  <h2 class="text-xl font-semibold mb-4">Quản lý thông tin cá nhân</h2>

  <?php if (!empty($msg)): ?>
    <p class="text-green-600 mb-3"><?= htmlspecialchars($msg) ?></p>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">

    <!-- Avatar hiển thị -->
    <div class="mb-4 text-center">
      <?php
        $avatar_src = 'assets/default-avatar.png';

        if (!empty($user['avatar'])) {
            if (is_file($root . $user['avatar'])) {
                $avatar_src = $user['avatar'];
            }
        }
      ?>
      <img src="/<?= $avatar_src ?>" alt="avatar"
           class="w-24 h-24 rounded-full mx-auto object-cover border" />
    </div>

    <div class="mb-3">
      <label class="block text-sm font-medium">Đổi ảnh đại diện</label>
      <input type="file" name="avatar" class="mt-1 p-2 w-full border rounded bg-white" accept="image/*" />
    </div>

    <div class="mb-3">
      <label class="block text-sm font-medium">Họ và tên</label>
      <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>"
             class="mt-1 p-2 border rounded w-full" />
    </div>

    <div class="mb-3">
      <label class="block text-sm font-medium">Số điện thoại</label>
      <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
             class="mt-1 p-2 border rounded w-full" />
    </div>

    <div class="mb-3">
      <label class="block text-sm font-medium">Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>"
             class="mt-1 p-2 border rounded w-full" />
    </div>

    <button class="px-4 py-2 bg-blue-600 text-white rounded">Lưu thay đổi</button>
  </form>

  <hr class="my-4">
  <a href="change_password.php" class="text-blue-600 text-sm">Đổi mật khẩu</a>
</main>

</body>
</html>
