<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'header.php';
require 'db.php';

if (empty($_GET['id'])) {
    echo "<main class='max-w-4xl mx-auto p-6'><p>Không tìm thấy tin.</p></main>";
    exit;
}

$id = (int)$_GET['id'];

// Lấy tin
$stmt = $pdo->prepare("
    SELECT l.*, u.email 
    FROM listings l 
    JOIN users u ON l.user_id = u.id
    WHERE l.id = ?
");
$stmt->execute([$id]);
$listing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$listing) {
    echo "<main class='max-w-4xl mx-auto p-6'><p>Tin không tồn tại.</p></main>";
    exit;
}

// Lấy ảnh
$imgStmt = $pdo->prepare("SELECT * FROM listing_images WHERE listing_id = ?");
$imgStmt->execute([$id]);
$images = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="max-w-4xl mx-auto p-6">
  <div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold text-blue-600 mb-4">
      <?= htmlspecialchars($listing['title']) ?>
    </h1>

    <?php if ($images): ?>
      <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
        <?php foreach ($images as $img): ?>
          <img src="<?= htmlspecialchars($img['image_path']) ?>" 
               class="w-full h-40 object-cover rounded">
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <p class="text-lg"><strong>Giá:</strong> <?= number_format($listing['price']) ?> VNĐ</p>
    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($listing['address']) ?></p>
    <p><strong>Người đăng:</strong> <?= htmlspecialchars($listing['email']) ?></p>

    <div class="mt-4">
      <h3 class="font-semibold">Mô tả:</h3>
      <p class="text-gray-700 whitespace-pre-line">
        <?= nl2br(htmlspecialchars($listing['description'])) ?>
      </p>
    </div>

    <?php if (!empty($_SESSION['user_id']) && $_SESSION['user_id'] == $listing['user_id']): ?>
      <div class="mt-6 flex space-x-4">
        <a href="edit_listing.php?id=<?= $listing['id'] ?>" class="px-4 py-2 bg-yellow-400 rounded">Sửa</a>
        <a href="delete_listing.php?id=<?= $listing['id'] ?>" 
           onclick="return confirm('Bạn chắc chắn muốn xóa?')"
           class="px-4 py-2 bg-red-500 text-white rounded">Xóa</a>
      </div>
    <?php endif; ?>

  </div>
</main>

<?php include 'footer.php'; ?>
