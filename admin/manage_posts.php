<?php
require __DIR__ . '/../header.php';
require __DIR__ . '/../functions.php';

if(!isAdmin()) {
  echo "<main class='max-w-4xl mx-auto p-4'><p>Bạn không có quyền truy cập.</p></main>";
  exit;
}

$stmt = $pdo->query("
  SELECT p.*, 
    (SELECT filename FROM post_images WHERE post_id = p.id LIMIT 1) AS filename 
  FROM posts p 
  ORDER BY p.created_at DESC
");
$posts = $stmt->fetchAll();
?>

<main class="max-w-4xl mx-auto p-4">
  <h2 class="text-xl font-semibold mb-4">Quản lý bài đăng</h2>

  <?php if (!$posts): ?>
    <p>Chưa có tin nào.</p>
  <?php else: ?>
    <div class="space-y-3">

      <?php foreach ($posts as $p): ?>
        <div class="bg-white p-3 rounded shadow flex items-center">

          <img src="../uploads/<?= htmlspecialchars($p['filename'] ?? '') ?>" 
               class="w-28 h-20 object-cover rounded mr-3"
               onerror="this.src='../uploads/no-image.png'">

          <div class="flex-1">
            <h3 class="font-semibold">
              <a href="view_post.php?id=<?= $p['id'] ?>" class="text-blue-600 hover:underline">
                <?= htmlspecialchars($p['title']) ?>
              </a>
            </h3>

            <p class="text-sm"><?= htmlspecialchars($p['khu_vuc']) ?> — <?= htmlspecialchars($p['price']) ?> triệu</p>
            <p class="text-sm">Trạng thái: <strong><?= htmlspecialchars($p['status']) ?></strong></p>
          </div>

          <?php if ($p['status'] !== 'approved'): ?>
            <a href="approve.php?id=<?= $p['id'] ?>&action=approve" 
               class="px-3 py-1 bg-green-600 text-white rounded">
               Duyệt
            </a>
          <?php else: ?>
            <a href="approve.php?id=<?= $p['id'] ?>&action=reject" 
               class="px-3 py-1 bg-yellow-500 text-white rounded">
               Hủy duyệt
            </a>
          <?php endif; ?>

        </div>
      <?php endforeach; ?>

    </div>
  <?php endif; ?>
</main>
