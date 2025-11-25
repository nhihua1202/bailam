<?php
require 'header.php';
require 'db.php';
require 'functions.php';

$u = currentUser();

// CHO ADMIN HOẶC LANDLORD TRUY CẬP
if (!$u || !in_array($u['role'], ['admin', 'landlord'])) {
    echo "<h2>Bạn không có quyền truy cập</h2>";
    exit;
}

$isAdmin = $u['role'] === 'admin';
$isLandlord = $u['role'] === 'landlord';

// ADMIN xem tất cả bài, LANDLORD xem bài của họ
if ($isAdmin) {
    $stmt = $pdo->query("
        SELECT p.*,
               (SELECT filename FROM post_images WHERE post_id = p.id LIMIT 1) AS filename,
               u.name AS owner_name
        FROM posts p
        LEFT JOIN users u ON u.id = p.user_id
        ORDER BY p.created_at DESC
    ");
} else {
    $stmt = $pdo->prepare("
        SELECT p.*,
               (SELECT filename FROM post_images WHERE post_id = p.id LIMIT 1) AS filename
        FROM posts p
        WHERE p.user_id = ?
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([$u['id']]);
}

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="max-w-6xl mx-auto p-4">
  <h2 class="text-2xl font-semibold mb-6">Quản lý tin đăng</h2>

  <?php if (!$posts): ?>
    <p class="text-gray-600">Chưa có bài đăng.</p>

  <?php else: ?>
    <div class="space-y-4">
      <?php foreach ($posts as $p):

        $img = $p['filename']
            ? 'uploads/' . $p['filename']
            : 'https://via.placeholder.com/400x250?text=No+Image';

        $badgeColor = [
          'approved' => 'text-green-700 bg-green-100',
          'pending'  => 'text-yellow-700 bg-yellow-100',
          'rejected' => 'text-red-700 bg-red-100'
        ][$p['status']] ?? 'bg-gray-100 text-gray-700';

      ?>

      <div class="bg-white shadow rounded-lg p-4 flex gap-4 hover:shadow-xl transition">

        <a href="view_post.php?id=<?= $p['id'] ?>">
          <img src="<?= esc($img) ?>" class="w-40 h-28 object-cover rounded border">
        </a>

        <div class="flex-1">

          <a href="view_post.php?id=<?= $p['id'] ?>" class="no-underline">
            <h3 class="text-xl font-semibold hover:text-blue-600 transition">
              <?= esc($p['title']) ?>
            </h3>
          </a>

          <div class="flex flex-wrap gap-2 mt-1">
            <span class="px-2 py-1 rounded text-sm <?= $badgeColor ?>">
              <?= strtoupper(esc($p['status'])) ?>
            </span>

            <?php if ($isAdmin): ?>
              <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-sm">
                Chủ bài: <?= esc($p['owner_name']) ?>
              </span>
            <?php endif; ?>
          </div>

          <p class="text-sm text-gray-500 mt-1">
            Ngày đăng: <?= date('d/m/Y', strtotime($p['created_at'])) ?>
          </p>

          <div class="mt-3 flex gap-2">

            <?php if ($isAdmin): ?>

              <a href="approve_post.php?id=<?= $p['id'] ?>&action=approve"
                 class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                 Duyệt
              </a>

              <a href="approve_post.php?id=<?= $p['id'] ?>&action=reject"
                 class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                 Từ chối
              </a>

            <?php else: ?>

              <a href="edit_post.php?id=<?= $p['id'] ?>"
                 class="px-3 py-1 text-sm bg-yellow-400 rounded hover:bg-yellow-500">
                 Sửa
              </a>

              <a href="delete_post.php?id=<?= $p['id'] ?>"
                 onclick="return confirm('Bạn chắc chắn muốn xóa bài này?')"
                 class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                 Xóa
              </a>

            <?php endif; ?>

          </div>
        </div>

      </div>

      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</main>

</body>
</html>
