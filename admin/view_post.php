<?php
require __DIR__ . '/../header.php';
require __DIR__ . '/../functions.php';

if (!isAdmin()) {
    echo "Không có quyền";
    exit;
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("ID không hợp lệ");
}

// Lấy thông tin bài + thông tin chủ phòng
$stmt = $pdo->prepare("
    SELECT p.*, 
           u.name AS owner_name,
           u.email AS owner_email,
           u.phone AS owner_phone,
           u.zalo AS owner_zalo
    FROM posts p
    LEFT JOIN users u ON p.user_id = u.id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    die("Không tìm thấy phòng");
}

// Lấy ảnh
$img = $pdo->prepare("SELECT filename FROM post_images WHERE post_id=? LIMIT 1");
$img->execute([$id]);
$image = $img->fetchColumn();
?>

<main class="max-w-3xl mx-auto p-4">

  <h2 class="text-2xl font-semibold mb-3"><?= htmlspecialchars($post['title']) ?></h2>

  <?php if ($image): ?>
    <img src="../uploads/<?= htmlspecialchars($image) ?>" class="w-full h-64 object-cover rounded mb-3">
  <?php endif; ?>

  <p><strong>Khu vực:</strong> <?= htmlspecialchars($post['khu_vuc']) ?></p>
  <p><strong>Giá:</strong> <?= htmlspecialchars($post['price']) ?> triệu</p>
  <p><strong>Mô tả:</strong> <?= nl2br(htmlspecialchars($post['description'])) ?></p>

  <h3 class="mt-4 font-semibold text-lg">Thông tin chủ phòng</h3>
  <p><strong>Họ tên:</strong> <?= htmlspecialchars($post['owner_name']) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($post['owner_email']) ?></p>
  <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($post['owner_phone']) ?></p>

  <p>
    <strong>Zalo:</strong>
    <?php if (!empty($post['owner_zalo'])): ?>
        <a href="https://zalo.me/<?= htmlspecialchars($post['owner_zalo']) ?>" 
           class="text-blue-600 underline" target="_blank">
           <?= htmlspecialchars($post['owner_zalo']) ?>
        </a>
    <?php else: ?>
        (Chưa có)
    <?php endif; ?>
  </p>

  <p class="mt-3"><strong>Trạng thái duyệt:</strong> 
     <?= htmlspecialchars($post['status']) ?>
  </p>

  <div class="mt-4 flex gap-3">
    <?php if ($post['status'] !== 'approved'): ?>
      <a href="approve.php?id=<?= $post['id'] ?>&action=approve"
         class="px-4 py-2 bg-green-600 text-white rounded">
         Duyệt bài
      </a>
    <?php else: ?>
      <a href="approve.php?id=<?= $post['id'] ?>&action=reject"
         class="px-4 py-2 bg-yellow-500 text-white rounded">
         Hủy duyệt
      </a>
    <?php endif; ?>

    <a href="manage_posts.php" class="px-4 py-2 bg-gray-300 rounded">Quay lại</a>
  </div>

</main>
