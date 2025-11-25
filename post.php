<?php
require 'header.php';
require 'db.php';
require 'functions.php';

$post_id = intval($_GET['id'] ?? 0);

// Lấy bài đăng
$stmt = $pdo->prepare("
    SELECT p.*, u.name AS owner_name, u.phone AS owner_phone, u.zalo AS owner_zalo
    FROM posts p
    JOIN users u ON p.user_id = u.id
    WHERE p.id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    echo "<main class='max-w-4xl mx-auto p-4 text-red-600'>Bài đăng không tồn tại.</main>";
    exit;
}

// Lấy ảnh đúng
$imgQ = $pdo->prepare("SELECT filename FROM post_images WHERE post_id = ?");
$imgQ->execute([$post_id]);
$photos = $imgQ->fetchAll(PDO::FETCH_COLUMN);

// Người dùng hiện tại
$user = currentUser();
$is_owner = $user && $user['id'] == $post['user_id'];
?>

<main class="max-w-5xl mx-auto p-4">

  <!-- Ảnh -->
  <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">

      <?php if (!empty($photos)): ?>
          <?php foreach ($photos as $img): ?>
              <img src="/hiihi/uploads/<?php echo esc($img); ?>"
                   class="rounded-xl shadow object-cover w-full h-64"
                   onerror="this.src='/hiihi/uploads/noimage.png';">
          <?php endforeach; ?>
      <?php else: ?>
          <img src="/hiihi/uploads/noimage.png"
               class="rounded-xl shadow object-cover w-full h-64">
      <?php endif; ?>

  </div>

  <!-- Thông tin bài đăng -->
  <div class="bg-white p-6 rounded-xl shadow">

      <h1 class="text-2xl font-bold mb-4"><?php echo esc($post['title']); ?></h1>

      <p><strong>Khu vực:</strong> <?php echo esc($post['khu_vuc']); ?></p>
      <p><strong>Loại phòng:</strong> <?php echo esc($post['type']); ?></p>

      <!-- GIÁ CHUẨN -->
      <p><strong>Giá thuê:</strong>
          <span class="text-red-600 font-semibold">
              <?php
              $gia = trim($post['price']);

              if (!is_numeric($gia)) {
                  // Nếu người dùng nhập "3.4 triệu" -> giữ nguyên
                  echo esc($gia);
              } else {
                  // Nếu số dưới 100.000 => coi như số triệu
                  if ($gia < 100000) {
                      echo number_format($gia, 1, ',', '.') . " triệu / tháng";
                  } else {
                      // Giá theo VND -> chuyển thành triệu
                      $trieu = $gia / 1000000;
                      echo number_format($trieu, 1, ',', '.') . " triệu / tháng";
                  }
              }
              ?>
          </span>
      </p>

      <p class="mt-4"><strong>Chủ phòng:</strong> <?php echo esc($post['owner_name']); ?></p>
      <p><strong>Điện thoại:</strong> <?php echo esc($post['owner_phone']); ?></p>

      <p><strong>Zalo:</strong>
          <a href="https://zalo.me/<?php echo esc($post['owner_zalo']); ?>"
             class="text-blue-600 underline" target="_blank">
              Liên hệ Zalo
          </a>
      </p>

      <p class="mt-4"><strong>Mô tả:</strong></p>
      <p><?php echo nl2br(esc($post['description'])); ?></p>

      <!-- Nút thuê phòng -->
      <?php if (!$is_owner): ?>
          <a href="rent_submit.php?post_id=<?php echo $post_id; ?>"
             class="mt-6 inline-block bg-green-600 text-white px-5 py-3 rounded-lg hover:bg-green-700">
              Gửi yêu cầu thuê phòng
          </a>
      <?php else: ?>
          <p class="mt-6 text-blue-600 font-semibold">
              Bạn là chủ phòng – không thể thuê phòng của chính mình.
          </p>
      <?php endif; ?>

  </div>

</main>
</body>
</html>
