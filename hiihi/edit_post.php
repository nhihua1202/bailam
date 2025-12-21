<?php 
require 'header.php'; 
require 'functions.php'; 

$id = intval($_GET['id'] ?? 0);

// Lấy bài đăng
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=?");
$stmt->execute([$id]);
$post = $stmt->fetch();
if (!$post) { echo "<p>Không tìm thấy bài đăng.</p>"; exit; }

// Lấy danh sách hình ảnh
$imgs = $pdo->prepare("SELECT * FROM post_images WHERE post_id=?");
$imgs->execute([$id]);
$images = $imgs->fetchAll();

// KHU VỰC
$areas = ["Cầu Giấy", "Đống Đa", "Hai Bà Trưng", "Thanh Xuân", "Hoàng Mai", "Ba Đình", "Tây Hồ"];

// LOẠI BÀI
$types = [
    "Phòng trọ",
    "Nhà nguyên căn",
    "Căn hộ chung cư",
    "Căn hộ mini",
    "Căn hộ dịch vụ"
];
?>

<main class="max-w-3xl mx-auto p-4">

  <h2 class="text-xl font-semibold mb-4">Sửa bài: <?= esc($post['title']) ?></h2>

  <form action="edit_submit.php" method="post" enctype="multipart/form-data"
        class="bg-white p-4 rounded shadow">

    <input type="hidden" name="id" value="<?= $post['id'] ?>">

    <!-- TIÊU ĐỀ -->
    <div class="mb-3">
      <label class="block text-sm font-medium">Tiêu đề *</label>
      <input name="title" required
             value="<?= esc($post['title']) ?>"
             class="mt-1 p-2 border rounded w-full" />
    </div>

    <!-- KHU VỰC -->
    <div class="mb-3">
      <label class="block text-sm font-medium">Khu vực *</label>
      <select name="khu_vuc" required class="mt-1 p-2 border rounded w-full">
        <?php foreach ($areas as $a): ?>
            <option value="<?= $a ?>" <?= ($post['khu_vuc'] == $a ? "selected" : "") ?>>
              <?= $a ?>
            </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- LOẠI -->
    <div class="mb-3">
      <label class="block text-sm font-medium">Loại *</label>
      <select name="type" required class="mt-1 p-2 border rounded w-full">
        <?php foreach ($types as $t): ?>
            <option value="<?= $t ?>" <?= ($post['type'] == $t ? "selected" : "") ?>>
              <?= $t ?>
            </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- GIÁ -->
    <div class="mb-3">
      <label class="block text-sm font-medium">Giá (triệu VNĐ) *</label>
      <input type="number" step="0.1" min="0" required
             name="price"
             value="<?= esc($post['price']) ?>"
             class="mt-1 p-2 border rounded w-full">
    </div>

    <!-- SỐ ĐIỆN THOẠI -->
    <div class="mb-3">
      <label class="block text-sm font-medium">Số điện thoại *</label>
      <input name="phone" required
             value="<?= esc($post['phone']) ?>"
             class="mt-1 p-2 border rounded w-full">
    </div>

    <!-- ZALO -->
    <div class="mb-3">
      <label class="block text-sm font-medium">Link Zalo</label>
      <input name="zalo"
             value="<?= esc($post['zalo']) ?>"
             class="mt-1 p-2 border rounded w-full">
    </div>

    <!-- MÔ TẢ -->
    <div class="mb-3">
      <label class="block text-sm font-medium">Mô tả *</label>
      <textarea name="description" required rows="6"
                class="mt-1 p-2 border rounded w-full"><?= esc($post['description']) ?></textarea>
    </div>

    <!-- ẢNH HIỆN CÓ -->
    <?php if ($images): ?>
      <div class="mb-3">
        <label class="block text-sm font-medium">Ảnh hiện có (chọn để xóa)</label>

        <div class="grid grid-cols-3 gap-2 mt-2">
          <?php foreach($images as $im): ?>
            <div class="relative border rounded overflow-hidden">
              <img src="uploads/<?= esc($im['filename']) ?>"
                   class="w-full h-28 object-cover">

              <label class="absolute top-1 right-1 bg-white rounded px-1 text-red-600 text-xs cursor-pointer">
                <input type="checkbox" name="delete_images[]" value="<?= $im['id'] ?>"> Xóa
              </label>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- THÊM ẢNH MỚI -->
    <div class="mb-3">
      <label class="block text-sm font-medium">Thêm ảnh mới</label>
      <input type="file" name="images[]" multiple accept="image/*"
             class="mt-1">
    </div>

    <!-- BUTTONS -->
    <div class="flex items-center justify-between">
      <button class="px-4 py-2 bg-blue-600 text-white rounded">
        Lưu thay đổi
      </button>
      <a href="my_posts.php" class="text-sm text-gray-600">Hủy</a>
    </div>

  </form>
</main>

</body>
</html>
