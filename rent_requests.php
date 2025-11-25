<?php
require 'header.php';
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit;
}

$uid = $_SESSION['user']['id'];

/* LẤY DANH SÁCH ĐƠN */
$stmt = $pdo->prepare("
    SELECT r.*, 
           p.title, 
           p.price, 
           p.type, 
           p.id AS post_id
    FROM rental_requests r
    JOIN posts p ON r.post_id = p.id
    WHERE r.user_id = :uid
    ORDER BY r.id DESC
");
$stmt->execute(['uid' => $uid]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* LẤY 1 ẢNH ĐẠI DIỆN CHO TỪNG POST */
foreach ($rows as &$r) {
    $img = $pdo->prepare("
        SELECT filename 
        FROM post_images 
        WHERE post_id = :pid 
        ORDER BY id ASC 
        LIMIT 1
    ");
    $img->execute(['pid' => $r['post_id']]);
    $imgData = $img->fetch(PDO::FETCH_ASSOC);

    $r['thumbnail'] = (!empty($imgData['filename']))
        ? "uploads/" . $imgData['filename']
        : "assets/no-image.png";
}
unset($r);
?>

<main class="max-w-4xl mx-auto p-4">

    <h2 class="text-2xl font-semibold mb-6 text-center text-gray-900">
        Đơn đăng ký thuê phòng của bạn
    </h2>

    <?php if (empty($rows)): ?>
        <p class="text-gray-600 text-sm text-center">Bạn chưa gửi đơn nào.</p>

    <?php else: ?>

        <div class="space-y-6">

            <?php foreach ($rows as $r): ?>

                <div class="p-4 bg-white rounded-lg shadow border flex gap-4">

                    <!-- ẢNH -->
                    <img src="<?= $r['thumbnail'] ?>"
                         class="w-32 h-24 rounded object-cover border"
                         onerror="this.src='assets/no-image.png';">

                    <div class="flex-1">

                        <h3 class="text-lg font-semibold text-gray-900">
                            <?= htmlspecialchars($r['title']) ?>
                        </h3>

                        <p class="text-sm text-gray-700">
                            <?php 
                                $price = rtrim(rtrim(number_format($r['price'], 1, ',', '.'), '0'), ',');
                            ?>
                            Giá: <?= $price ?> triệu / tháng<br>
                            Loại phòng: <?= htmlspecialchars($r['type']) ?>
                        </p>

                        <!-- TRẠNG THÁI -->
                        <p class="mt-1 text-sm font-medium
                           <?php 
                                if ($r['status'] == 'approved') echo 'text-green-600';
                                elseif ($r['status'] == 'rejected') echo 'text-red-600';
                                else echo 'text-yellow-600';
                           ?>">
                           <?php 
                                if ($r['status'] == 'approved')       echo "✔ Đã duyệt";
                                elseif ($r['status'] == 'rejected')   echo "✖ Bị từ chối";
                                else                                  echo "⏳ Đang đợi duyệt";
                           ?>
                        </p>

                        <p class="text-xs text-gray-500 mt-2">
                            Ngày gửi: <?= $r['created_at'] ?>
                        </p>

                        <a href="post_detail.php?id=<?= $r['post_id'] ?>" 
                           class="text-blue-600 text-sm mt-2 inline-block hover:underline">
                           Xem bài đăng →
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</main>
