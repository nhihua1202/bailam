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
$uploadDir = __DIR__ . "/uploads/";
$uploadWeb = "uploads/";

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

    $avatarName = $u['avatar'] ?? null;
    if (!empty($_FILES['avatar']['name'])) {
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
            $avatarName = "avatar_" . $u['id'] . "." . $ext;  
            $uploadPath = $uploadDir . $avatarName;
            move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath);
        }
    }

    if (!$new_password) {
        $stmt = $pdo->prepare("UPDATE users SET name=?, phone=?, email=?, avatar=? WHERE id=?");
        $stmt->execute([$name, $phone, $email, $avatarName, $u['id']]);
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['avatar'] = $avatarName;
        $msg = "✅ Cập nhật thành công!";
    } else {
        if ($new_password !== $confirm_password) {
            $error = "❌ Mật khẩu mới không khớp.";
        } else {
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id=? LIMIT 1");
            $stmt->execute([$u['id']]);
            $user = $stmt->fetch();
            if (!$user || !password_verify($password, $user['password'])) {
                $error = "❌ Mật khẩu hiện tại không đúng.";
            } else {
                $hashed = password_hash($new_password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE users SET name=?, phone=?, email=?, avatar=?, password=? WHERE id=?");
                $stmt->execute([$name, $phone, $email, $avatarName, $hashed, $u['id']]);
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['phone'] = $phone;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['avatar'] = $avatarName;
                $msg = "✅ Đổi mật khẩu thành công!";
            }
        }
    }
}
?>

<div class="min-h-screen bg-[#f8f9fa] py-10 px-4">
    <main class="max-w-xl mx-auto">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <span class="w-1 h-6 bg-blue-600 rounded-full"></span>
                Hồ sơ cá nhân
            </h2>
        </div>

        <?php if ($msg): ?>
            <div class="mb-4 p-3 text-sm bg-green-50 text-green-600 border border-green-100 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="mb-4 p-3 text-sm bg-red-50 text-red-600 border border-red-100 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/></svg>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="p-6 md:p-8 space-y-6">
                <div class="flex items-center gap-5 pb-6 border-b border-gray-50">
                    <div class="relative group">
                        <img id="preview" src="<?= $uploadWeb . esc($_SESSION['user']['avatar'] ?? 'default.png') ?>" 
                             class="w-20 h-20 rounded-2xl object-cover border-2 border-gray-100 shadow-sm transition-transform group-hover:scale-105">
                        <label class="absolute -bottom-2 -right-2 bg-white p-1.5 rounded-lg shadow-md border border-gray-100 cursor-pointer hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <input type="file" name="avatar" class="hidden" onchange="previewImg(this)">
                        </label>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Ảnh đại diện</p>
                        <p class="text-[11px] text-gray-400 mt-1">Nên chọn ảnh vuông, tối đa 2MB</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-wider mb-2">Họ và tên</label>
                        <input type="text" name="name" value="<?= esc($u['name'] ?? '') ?>" 
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all font-medium">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-wider mb-2">Số điện thoại</label>
                            <input type="text" name="phone" value="<?= esc($u['phone'] ?? '') ?>" 
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all font-medium">
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                            <input type="email" name="email" value="<?= esc($u['email'] ?? '') ?>" 
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all font-medium">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="button" onclick="togglePassword()" 
                            class="text-[12px] font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1.5 transition-colors">
                        <span id="pwIcon">🔒</span> Đổi mật khẩu bảo mật
                    </button>

                    <div id="pwBox" class="mt-5 space-y-4 hidden animate-slide-down">
                        <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 space-y-4">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-1.5">Mật khẩu hiện tại</label>
                                <input type="password" name="password" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:border-blue-500 outline-none text-sm transition-all">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-400 uppercase mb-1.5">Mật khẩu mới</label>
                                    <input type="password" name="new_password" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:border-blue-500 outline-none text-sm transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-400 uppercase mb-1.5">Nhập lại mật khẩu</label>
                                    <input type="password" name="confirm_password" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:border-blue-500 outline-none text-sm transition-all">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100 flex justify-end">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-100 hover:bg-blue-700 active:scale-95 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Lưu thông tin
                </button>
            </div>
        </form>
    </main>
</div>

<style>
@keyframes slide-down {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-slide-down { animation: slide-down 0.3s ease-out; }
</style>

<script>
function togglePassword() {
    const box = document.getElementById("pwBox");
    const icon = document.getElementById("pwIcon");
    if(box.classList.contains('hidden')) {
        box.classList.remove('hidden');
        icon.innerText = "🔓";
    } else {
        box.classList.add('hidden');
        icon.innerText = "🔒";
    }
}

function previewImg(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</body>
</html>