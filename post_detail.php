<?php
require 'header.php';
require 'db.php';
require 'functions.php';

$post_id = intval($_GET['id'] ?? 0);

/* LẤY THÔNG TIN BÀI ĐĂNG */
$stmt = $pdo->prepare("
    SELECT p.*, u.name AS owner_name, u.phone AS owner_phone
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

/* LẤY ẢNH */
$images = $pdo->prepare("SELECT filename FROM post_images WHERE post_id = ?");
$images->execute([$post_id]);
$photos = $images->fetchAll(PDO::FETCH_COLUMN);

/* Format giá thuê: 2.6 triệu */
function priceToMillion($num) {
    return rtrim(rtrim(number_format($num, 1, ',', '.'), '0'), ',');
}
?>

<main class="max-w-4xl mx-auto p-4">

    <h1 class="text-2xl font-bold mb-4"><?php echo esc($post['title']); ?></h1>

    <!-- HIỂN THỊ ẢNH -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
        <?php foreach ($photos as $img): ?>
            <img src="uploads/<?php echo esc($img); ?>" 
                 class="rounded shadow w-full h-56 object-cover">
        <?php endforeach; ?>

        <?php if (empty($photos)): ?>
            <p class="text-gray-500 col-span-3">Không có ảnh.</p>
        <?php endif; ?>
    </div>

    <div class="bg-white p-4 rounded shadow">

        <p><strong>Khu vực:</strong> <?php echo esc($post['khu_vuc']); ?></p>
        <p><strong>Loại phòng:</strong> <?php echo esc($post['type']); ?></p>

        <p><strong>Giá thuê:</strong>
            <?php echo priceToMillion($post['price']); ?> triệu
        </p>
        
        <p class="mt-4"><strong>Mô tả:</strong></p>
        <p><?php echo nl2br(esc($post['description'])); ?></p>
        <p><strong>Liên hệ chủ phòng:</strong><br>

            <!-- GỌI ĐIỆN -->
            <a href="tel:<?php echo esc($post['owner_phone']); ?>" 
               class="text-blue-600 font-semibold hover:underline">
               📞 <?php echo esc($post['owner_phone']); ?> (Gọi)
            </a>
            <br>

            <!-- ZALO -->
            <a href="https://zalo.me/<?php echo esc($post['owner_phone']); ?>" 
               target="_blank"
               class="text-blue-600 font-semibold hover:underline">
               💬 Nhắn Zalo
            </a>

            <br>
            Chủ phòng: <?php echo esc($post['owner_name']); ?>
        </p>

        <p class="mt-3"><strong>Ngày thuê:</strong>
            <?php echo date("d/m/Y", strtotime($post['created_at'])); ?>
        </p>

        <!-- NÚT QUAY LẠI -->
        <a href="javascript:history.back()"
           class="mt-6 inline-flex items-center gap-2 bg-gradient-to-r from-gray-700 to-gray-900 
                  text-white px-5 py-2.5 rounded-lg shadow hover:from-gray-600 hover:to-gray-800 transition">
            <span>⬅</span> Quay lại
        </a>

    </div>

</main>
</body>
</html>
