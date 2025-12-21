<?php
require 'db.php';
require 'functions.php';
session_start();

$u = $_SESSION['user'] ?? null;
if (!$u) {
    header('Location: auth.php?mode=login');
    exit;
}
if (!(($u['role'] ?? 'renter') === 'landlord') && !($u['is_admin'] ?? 0)) {
    require 'header.php';
    echo '<main class="max-w-3xl mx-auto p-4"><div class="bg-white p-4 rounded shadow"><p class="text-red-600">Bạn không có quyền đăng tin. Vui lòng đăng nhập bằng tài khoản người cho thuê.</p></div></main></body></html>';
    exit;
}

require 'header.php';
require 'includes/district.php'; // File chứa danh sách quận/huyện Hà Nội (bạn đã có từ bước trước)
?>

<main class="max-w-3xl mx-auto p-4">
  <h2 class="text-xl font-semibold mb-4">Đăng tin mới</h2>

  <form action="post_submit.php" method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow space-y-4">

    <!-- Tiêu đề -->
    <div>
      <label class="block text-sm font-medium">Tiêu đề <span class="text-red-500">*</span></label>
      <input name="title" required class="mt-1 p-2 border rounded w-full" placeholder="VD: Cho thuê phòng trọ gần Đại học Quốc Gia" />
    </div>

    <!-- Loại -->
    <div>
      <label class="block text-sm font-medium">Loại <span class="text-red-500">*</span></label>
      <select name="type" class="mt-1 p-2 border rounded w-full">
        <option value="Phòng trọ">Phòng trọ</option>
        <option value="Nhà nguyên căn">Nhà nguyên căn</option>
        <option value="Căn hộ chung cư">Căn hộ chung cư</option>
        <option value="Căn hộ mini">Căn hộ mini</option>
        <option value="Căn hộ dịch vụ">Căn hộ dịch vụ</option>
      </select>
    </div>

    <!-- Khu vực -->
    <div>
      <label class="block text-sm font-medium">Khu vực <span class="text-red-500">*</span></label>
      <select name="khu_vuc" class="mt-1 p-2 border rounded w-full" required>
        <option value="">-- Chọn quận/huyện --</option>
        <?php foreach ($districts as $d): ?>
          <option value="<?= htmlspecialchars($d) ?>"><?= htmlspecialchars($d) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Giá -->
    <div>
      <label class="block text-sm font-medium">Giá (triệu/tháng) <span class="text-red-500">*</span></label>
      <input type="number" name="price" step="0.1" min="0" class="mt-1 p-2 border rounded w-full" placeholder="Ví dụ: 3 hoặc 4.5" required />
    </div>

    <!-- Số điện thoại -->
    <div>
      <label class="block text-sm font-medium">Số điện thoại liên hệ</label>
      <input name="phone" type="text" class="mt-1 p-2 border rounded w-full" placeholder="VD: 0901234567" />
    </div>

    <!-- Zalo -->
    <div>
      <label class="block text-sm font-medium">Liên kết Zalo (tùy chọn)</label>
      <input name="zalo" type="text" class="mt-1 p-2 border rounded w-full" placeholder="VD: https://zalo.me/0901234567" />
    </div>

    <!-- Mô tả -->
    <div>
      <label class="block text-sm font-medium">Mô tả chi tiết</label>
      <textarea name="description" class="mt-1 p-2 border rounded w-full" rows="6" placeholder="Mô tả chi tiết phòng, diện tích, tiện ích xung quanh..."></textarea>
    </div>

    <!-- Ảnh -->
    <div>
      <label class="block text-sm font-medium">Ảnh (có thể chọn nhiều ảnh)</label>
      <input type="file" name="images[]" multiple accept="image/*" class="mt-1 w-full" />
      <p class="text-xs text-gray-500 mt-1">Bạn có thể chọn nhiều ảnh bằng cách giữ phím Ctrl (Windows) hoặc Command (Mac).</p>
    </div>

    <!-- Nút gửi -->
    <div class="flex items-center justify-between pt-4">
      <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">Gửi tin (chờ duyệt)</button>
      <a href="index.php" class="text-sm text-gray-600 hover:underline">Hủy</a>
    </div>

  </form>
</main>
</body>
</html>
