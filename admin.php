<?php 
require 'header.php'; 
require 'functions.php'; 
require 'db.php'; // cần cho $pdo
?>

<?php 
// Chỉ admin mới được xem
if (!isAdmin()) { 
  echo '<main class="max-w-4xl mx-auto p-4"><p>Bạn phải là admin để xem trang này.</p></main>'; 
  exit; 
} 
?>

<main class="max-w-4xl mx-auto p-4">
  <h2 class="text-xl font-semibold mb-4">Admin - Duyệt tin</h2>

  <?php
    // Lấy danh sách bài đăng + ảnh đầu tiên
    $stmt = $pdo->query("
      SELECT 
        p.*, 
        (SELECT image_path FROM post_images WHERE post_id = p.id LIMIT 1) AS image_path
      FROM posts p 
      ORDER BY p.created_at DESC
    ");

    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$posts) {
      echo '<p>Chưa có tin nào.</p>';
    } else {
      echo '<div class="space-y-3">';

      foreach ($posts as $p) {

        // Xử lý ảnh
        if (!empty($p['image_path'])) {
            $img = "/hihii/" . $p['image_path']; 
            // 🚨 Bạn đang dùng /phongtro/hanoi_rent_v3/... → sai, tôi sửa theo đúng folder bạn đang dùng: H I H I I
        } else {
            $img = "https://via.placeholder.com/150x100?text=No+Image";
        }

        echo '
        <div class="bg-white p-3 rounded shadow flex items-center">
          <img src="'. esc($img) .'" class="w-28 h-20 object-cover rounded mr-3">

          <div class="flex-1">
            <h3 class="font-semibold">'. esc($p['title']) .'</h3>
            <p class="text-sm">'. esc($p['khu_vuc']) .' — '. esc($p['price']) .' triệu</p>
            <p class="text-sm">Trạng thái: <strong>'. esc($p['status']) .'</strong></p>
          </div>
        ';

        // Nút duyệt/hủy duyệt
        if ($p['status'] !== 'approved') {
          echo '
            <div>
                <a href="/hihii/admin/approve_post.php?id='. $p['id'] .'&action=approve" 
                    class="px-3 py-1 bg-green-600 text-white rounded">Duyệt</a>
            </div>';
        } else {
          echo '
            <div>
                <a href="/hihii/admin/approve_post.php?id='. $p['id'] .'&action=reject" 
                    class="px-3 py-1 bg-yellow-500 text-white rounded">Hủy duyệt</a>
            </div>';
        }

        echo '</div>';
      }

      echo '</div>';
    }
  ?>
</main>

</body>
</html>
