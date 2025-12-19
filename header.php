<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . "/db.php";
require_once __DIR__ . "/functions.php";

/* ================= USER ================= */
$u = $_SESSION['user'] ?? null;

/* ================= AVATAR ================= */
if ($u && !empty($u['avatar']) && file_exists(__DIR__ . "/uploads/" . $u['avatar'])) {
    $avatar_url = "/hiihi/uploads/" . $u['avatar'];
} else {
    $avatar_url = "/hiihi/assets/default-avatar.png";
}

$display_name = $u['name'] ?? "Người dùng";
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Hanoi Rental</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 min-h-screen">

<header class="bg-white shadow-md sticky top-0 z-30">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center h-16">

      <a href="/hiihi/index.php" class="text-2xl font-bold text-red-600">HANOI RENTAL</a>

      <nav class="hidden md:flex space-x-6 text-sm font-medium">
        <a href="/hiihi/index.php" class="hover:text-red-600">Trang Chủ</a>
        <a href="/hiihi/index.php?type=Phòng trọ" class="hover:text-red-600">Phòng trọ</a>
        <a href="/hiihi/index.php?type=Nhà nguyên căn" class="hover:text-red-600">Nhà nguyên căn</a>
        <a href="/hiihi/index.php?type=Căn hộ chung cư" class="hover:text-red-600">Căn hộ chung cư</a>
        <a href="/hiihi/index.php?type=Căn hộ mini" class="hover:text-red-600">Căn hộ mini</a>
      </nav>

      <div class="flex items-center space-x-4">

        <?php if (isLandlord()): ?>
        <a href="/hiihi/post_new.php"
           class="hidden md:inline-block px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
           Đăng Tin
        </a>
        <?php endif; ?>

        <?php if ($u): ?>
        <div class="relative" id="dropdown-root">
          <button id="user-btn"
            class="inline-flex items-center px-3 py-2 bg-white border rounded-lg hover:bg-gray-100">
            <img src="<?= $avatar_url ?>" class="w-10 h-10 rounded-full mr-2 object-cover border">
            <?= htmlspecialchars($display_name) ?> ▼
          </button>

          <div id="user-menu" class="hidden absolute right-0 mt-2 w-56 bg-white border rounded-lg shadow-lg z-40">
            
            <?php if (!isAdmin()): ?>
              <a href="/hiihi/account.php" class="block px-4 py-2 text-sm hover:bg-gray-100 font-semibold border-b">
                Quản lý tài khoản
              </a>
            <?php endif; ?>

            <?php if (isLandlord()): ?>
              <a href="/hiihi/manage_posts.php" class="block px-4 py-2 text-sm hover:bg-gray-100">Quản lý tin</a>
              <a href="/hiihi/manage_rooms.php" class="block px-4 py-2 text-sm hover:bg-gray-100">Quản lý phòng</a>
            <?php endif; ?>

            <?php if (isRenter()): ?>
              <a href="/hiihi/rent_current.php" class="block px-4 py-2 text-sm hover:bg-gray-100">Phòng đang thuê</a>
              <a href="/hiihi/rent_requests.php" class="block px-4 py-2 text-sm hover:bg-gray-100">Đơn đăng ký thuê</a>
            <?php endif; ?>

            <?php if (isAdmin()): ?>
              <div class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b bg-gray-50">Hệ thống Admin</div>
              <a href="/hiihi/admin/manage_posts.php" class="block px-4 py-2 text-sm hover:bg-gray-100 text-blue-600">
                Quản trị bài đăng
              </a>
              <a href="/hiihi/admin/manage_users.php" class="block px-4 py-2 text-sm hover:bg-gray-100 text-blue-600">
                Quản trị tài khoản
              </a>
            <?php endif; ?>

            <a href="/hiihi/logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 border-t">
              Đăng xuất
            </a>
          </div>
        </div>
        <?php else: ?>
          <a href="/hiihi/auth.php" class="px-4 py-2 border rounded-lg hover:bg-gray-100">Đăng ký / Đăng nhập</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>

<script>
const btn = document.getElementById("user-btn");
const menu = document.getElementById("user-menu");
const root = document.getElementById("dropdown-root");

btn?.addEventListener("click", (e) => {
    e.stopPropagation();
    menu.classList.toggle("hidden");
});

document.addEventListener("click", (e) => {
    if (root && !root.contains(e.target)) menu.classList.add("hidden");
});
</script>
</body>
</html>