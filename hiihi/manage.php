<?php
require __DIR__ . '/includes/header.php';

// Đường dẫn file JSON lưu danh sách tin
$file = __DIR__ . '/data/listings.json';
$listings = [];

// Đọc dữ liệu tin đăng
if (file_exists($file)) {
    $json = file_get_contents($file);
    $listings = json_decode($json, true) ?: [];
}
?>
<div class="card">
  <h2>Quản lý tin</h2>

  <?php if (empty($listings)): ?>
    <p class="small text-gray-600">Không có tin đăng nào.</p>

  <?php else: ?>
    <?php foreach ($listings as $l): ?>
      <div class="card flex justify-between items-center mb-2">
        <div>
          <div class="title font-semibold"><?= htmlspecialchars($l['title']) ?></div>
          <div class="small text-gray-700">
            <?= htmlspecialchars($l['price']) ?> triệu
          </div>
        </div>
        <div class="flex space-x-2">
          <a href="/edit.php?id=<?= urlencode($l['id']) ?>" class="small text-blue-600 hover:underline">Sửa</a>
          |
          <a href="/delete.php?id=<?= urlencode($l['id']) ?>" class="small text-red-600 hover:underline"
             onclick="return confirm('Bạn có chắc muốn xóa tin này không?');">Xóa</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
