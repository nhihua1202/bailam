<?php
require 'db.php';
session_start();

// ==========================
// KIỂM TRA ĐĂNG NHẬP
// ==========================
if (!isset($_SESSION['user'])) {
    header("Location: auth.php?mode=login");
    exit;
}

$user = $_SESSION['user'];
$msg = "";


// ==========================
// CẬP NHẬT THÔNG TIN
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {

    $name  = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $stmt = $pdo->prepare("UPDATE users SET name=?, phone=?, email=? WHERE id=?");
    $stmt->execute([$name, $phone, $email, $user['id']]);

    // cập nhật session
    $_SESSION['user']['name']  = $name;
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['user']['email'] = $email;

    $msg = "✔ Cập nhật thông tin thành công!";
}



// ==========================
// CẬP NHẬT AVATAR
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_avatar'])) {

    if (!empty($_FILES['avatar']['name'])) {

        $upload_dir = __DIR__ . "/uploads/avatars/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $file_ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $file_ext = strtolower($file_ext);
        if (!in_array($file_ext, ['jpg','jpeg','png','gif','webp'])) {
            $msg = "❌ Chỉ chấp nhận ảnh JPG, PNG, GIF, WEBP!";
        } else {

            $file_name = "avatar_" . $user['id'] . "_" . time() . "." . $file_ext;
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $file_path)) {

                // lưu đường dẫn DB
                $db_path = "uploads/avatars/" . $file_name;

                $stmt = $pdo->prepare("UPDATE users SET avatar=? WHERE id=?");
                $stmt->execute([$db_path, $user['id']]);

                // cập nhật session
                $_SESSION['user']['avatar'] = $db_path;

                $msg = "✔ Cập nhật avatar thành công!";
            } else {
                $msg = "❌ Lỗi: không thể tải ảnh lên!";
            }
        }
    }
}


// ==========================
// LOAD LẠI USER SAU CẬP NHẬT
// ==========================
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user['id']]);
$user = $stmt->fetch();
$_SESSION['user'] = $user;




// ==========================
// XỬ LÝ HIỂN THỊ AVATAR
// ==========================
$avatar_path = $user['avatar'] ?? "";
$default_avatar = "assets/default-avatar.png";

// Cách check file chính xác
if (!empty($avatar_path) && file_exists(__DIR__ . "/$avatar_path")) {
    $avatar_url = $avatar_path;
} else {
    $avatar_url = $default_avatar;
}

require 'header.php';
?>

<main class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow mt-6">

    <h2 class="text-2xl font-semibold mb-4">Quản lý tài khoản</h2>

    <?php if ($msg): ?>
        <p class="text-green-600 font-medium mb-4"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <!-- ========================== -->
    <!--   AVATAR USER -->
    <!-- ========================== -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-2">Ảnh đại diện</h3>

        <img src="<?= $avatar_url ?>" 
             class="w-24 h-24 rounded-full border object-cover mb-3">

        <form method="post" enctype="multipart/form-data">
            <input type="file" name="avatar" accept="image/*" class="mb-3">
            <br>
            <button name="update_avatar"
                    class="px-4 py-2 bg-green-600 text-white rounded">
                Cập nhật avatar
            </button>
        </form>
    </div>

    <hr class="my-6">

    <!-- ========================== -->
    <!--   THÔNG TIN CÁ NHÂN -->
    <!-- ========================== -->
    <h3 class="text-lg font-semibold mb-3">Thông tin cá nhân</h3>

    <form method="post">
        <input type="hidden" name="update_info" value="1">

        <div class="mb-3">
            <label class="block text-sm font-medium">Họ và tên</label>
            <input type="text" name="name"
                   value="<?= htmlspecialchars($user['name']) ?>"
                   class="mt-1 p-2 border rounded w-full">
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Số điện thoại</label>
            <input type="text" name="phone"
                   value="<?= htmlspecialchars($user['phone']) ?>"
                   class="mt-1 p-2 border rounded w-full">
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email"
                   value="<?= htmlspecialchars($user['email']) ?>"
                   class="mt-1 p-2 border rounded w-full">
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded">
            Lưu thay đổi
        </button>
    </form>

    <hr class="my-6">

    <a href="change_password.php" class="text-blue-600 text-sm">
        Đổi mật khẩu
    </a>

</main>

</body>
</html>
