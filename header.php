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
    $avatar_url = "https://static.vecteezy.com/system/resources/previews/005/005/788/non_2x/user-icon-in-trendy-flat-style-isolated-on-grey-background-user-symbol-for-your-web-site-design-logo-app-ui-illustration-eps10-free-vector.jpg";
}

$display_name = $u['name'] ?? "Người dùng";
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hanoi Rental</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 min-h-screen">

<!-- ================= HEADER ================= -->
<header class="bg-white shadow-md sticky top-0 z-30">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center h-16">

      <a href="/hiihi/index.php" class="text-2xl font-bold text-red-600">
        HANOI RENTAL
      </a>

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
              <img src="<?= $avatar_url ?>" class="w-9 h-9 rounded-full mr-2 object-cover border">
              <?= htmlspecialchars($display_name) ?> ▼
            </button>

            <div id="user-menu"
              class="hidden absolute right-0 mt-2 w-56 bg-white border rounded-lg shadow-lg z-40">

              <?php if (!isAdmin()): ?>
                <a href="/hiihi/account.php"
                   class="block px-4 py-2 text-sm hover:bg-gray-100 font-semibold border-b">
                  Quản lý tài khoản
                </a>
              <?php endif; ?>

              <?php if (isLandlord()): ?>
                <a href="/hiihi/manage_posts.php" class="block px-4 py-2 text-sm hover:bg-gray-100">
                  Quản lý tin
                </a>
                <a href="/hiihi/manage_rooms.php" class="block px-4 py-2 text-sm hover:bg-gray-100">
                  Quản lý phòng
                </a>
              <?php endif; ?>

              <?php if (isRenter()): ?>
                <a href="/hiihi/rent_current.php" class="block px-4 py-2 text-sm hover:bg-gray-100">
                  Phòng đang thuê
                </a>
                <a href="/hiihi/rent_requests.php" class="block px-4 py-2 text-sm hover:bg-gray-100">
                  Đơn đăng ký thuê
                </a>
              <?php endif; ?>

              <?php if (isAdmin()): ?>
                <div class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase border-b bg-gray-50">
                  Hệ thống Admin
                </div>
                <a href="/hiihi/admin/manage_posts.php"
                   class="block px-4 py-2 text-sm hover:bg-gray-100 text-blue-600">
                  Quản trị bài đăng
                </a>
                <a href="/hiihi/admin/manage_users.php"
                   class="block px-4 py-2 text-sm hover:bg-gray-100 text-blue-600">
                  Quản trị tài khoản
                </a>
              <?php endif; ?>

              <a href="/hiihi/logout.php"
                 class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 border-t">
                Đăng xuất
              </a>
            </div>
          </div>
        <?php else: ?>
          <a href="/hiihi/auth.php"
             class="px-4 py-2 border rounded-lg hover:bg-gray-100">
            Đăng ký / Đăng nhập
          </a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</header>

<!-- ================= CHAT AI FLOAT ================= -->
<div class="fixed bottom-6 right-6 z-50">

  <!-- Toggle -->
  <button id="chat-toggle"
          class="w-14 h-14 rounded-full bg-red-600 text-white shadow-xl hover:bg-red-700 flex items-center justify-center">
    💬
  </button>

  <!-- Chat box -->
  <div id="chat-box"
       class="hidden mt-4 w-80 bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden border">

    <!-- Header -->
    <div class="bg-red-600 px-4 py-3 flex justify-between items-center">
      <div class="flex items-center space-x-2">
        <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">🤖</div>
        <div>
          <div class="text-white font-semibold text-sm">Hanoi Rental AI</div>
          <div class="text-red-100 text-[11px]">Trực tuyến 24/7</div>
        </div>
      </div>
      <button id="chat-close" class="text-white text-lg">✕</button>
    </div>

    <!-- Content -->
    <div id="ai-content"
         class="p-4 space-y-4 overflow-y-auto text-sm"
         style="height:320px">

      <div class="bg-gray-100 rounded-xl px-4 py-3">
        Xin chào! 👋 Mình có thể giúp bạn tìm phòng theo <b>giá tiền</b> hoặc <b>khu vực</b>.
      </div>

      <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 text-[13px] text-blue-700">
        <b>Mẹo tìm nhanh:</b>
        <ul class="mt-1 space-y-1">
          <li>• "Tìm phòng Cầu Giấy dưới 5 triệu"</li>
          <li>• "Giá phòng Đống Đa hiện tại"</li>
          <li>• "Căn hộ mini 2 phòng ngủ"</li>
        </ul>
      </div>

    </div>

    <!-- Input -->
    <div class="border-t px-3 py-2 flex items-center gap-2">
      <input id="ai-input"
             class="flex-1 px-4 py-2 rounded-full border text-sm outline-none focus:ring-2 focus:ring-red-500"
             placeholder="Nhập yêu cầu của bạn...">
      <button onclick="sendAI()"
              class="w-10 h-10 rounded-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center">
        ➤
      </button>
    </div>
  </div>
</div>

<!-- ================= SCRIPT ================= -->
<script>
/* USER DROPDOWN */
const btn = document.getElementById("user-btn");
const menu = document.getElementById("user-menu");
const root = document.getElementById("dropdown-root");

btn?.addEventListener("click", e => {
  e.stopPropagation();
  menu.classList.toggle("hidden");
});
document.addEventListener("click", e => {
  if (root && !root.contains(e.target)) menu.classList.add("hidden");
});

/* CHAT TOGGLE */
const toggleBtn = document.getElementById("chat-toggle");
const chatBox = document.getElementById("chat-box");
const closeBtn = document.getElementById("chat-close");

toggleBtn.onclick = () => chatBox.classList.toggle("hidden");
closeBtn.onclick = () => chatBox.classList.add("hidden");

/* CHAT AI */
function sendAI() {
  const input = document.getElementById("ai-input");
  const content = document.getElementById("ai-content");
  const msg = input.value.trim();
  if (!msg) return;

  content.innerHTML += `
    <div class="flex justify-end">
      <div class="bg-red-600 text-white px-4 py-2 rounded-2xl max-w-[75%]">
        ${msg}
      </div>
    </div>
  `;
  input.value = "";
  content.scrollTop = content.scrollHeight;

  const id = "load_" + Date.now();
  content.innerHTML += `
    <div id="${id}" class="flex justify-start">
      <div class="bg-gray-100 px-4 py-2 rounded-2xl text-gray-400">
        Đang tìm kiếm...
      </div>
    </div>
  `;
  content.scrollTop = content.scrollHeight;

  fetch("/hiihi/chat_ai.php", {
    method: "POST",
    headers: {"Content-Type": "application/json"},
    body: JSON.stringify({ message: msg })
  })
  .then(res => res.json())
  .then(data => {
    document.getElementById(id).remove();
    content.innerHTML += `
      <div class="flex justify-start">
        <div class="bg-gray-100 px-4 py-2 rounded-2xl max-w-[80%]">
          ${data.reply}
        </div>
      </div>
    `;
    content.scrollTop = content.scrollHeight;
  })
  .catch(() => {
    document.getElementById(id).remove();
    content.innerHTML += `<div class="text-red-500 text-xs">AI lỗi.</div>`;
  });
}

/* ENTER gửi */
document.getElementById("ai-input").addEventListener("keypress", e => {
  if (e.key === "Enter") sendAI();
});
</script>

</body>
</html>
