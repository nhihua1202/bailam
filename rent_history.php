<?php
require 'header.php';
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit;
}

$uid = $_SESSION['user']['id'];

/* LẤY TOÀN BỘ LỊCH SỬ ĐĂNG KÝ PHÒNG */
$sql = "
SELECT r.*, p.title, p.price, p.type
FROM rental_requests r
JOIN posts p ON r.post_id = p.id
WHERE r.tenant_id = :uid
ORDER BY r.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['uid' => $uid]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* LẤY ẢNH ĐẠI DIỆN */
foreach ($rows as &$r) {
    $img_stmt = $pdo->prepare("
        SELECT image FROM post_images 
        WHERE post_id = :pid 
        ORDER BY id ASC 
        LIMIT 1
    ");
    $img_stmt->execute(['pid' => $r['post_id']]);
    $img = $img_stmt->fetch(PDO::FETCH_ASSOC);

    $r['thumbnail'] = $img ? "uploads/" . $img['image'] : "assets/no-image.png";
}

/* HÀM ĐỔI MÀU STATUS */
function statusColor($status) {
    return [
        'approved' => 'text-green-600 font-semibold',
        'pending'  => 'text-yellow-600 font-semibold',
        'rejected' => 'text-red-600 font-semibold',
    ][$status] ?? 'text-gray-600';
}
?>

<main class="max-w-4xl mx-auto p-4">

    <h2 class="text-2xl font-semibold mb-4 text-gray-900">
        Lịch sử đăng ký phòng
    </h2>

    <?php if (empty($rows)): ?>
        <p class="text-gray-600 text-sm">Bạn chưa đăng ký phòng nào.</p>

    <?php else: ?>
        <div class="space-y-4">

            <?php foreach ($rows as $r): ?>
                <div class="p-4 bg-white border border-gray-200 rounded-lg flex gap-4 hover:shadow transition">

                    <img src="<?= $r['thumbnail'] ?>" 
                         class="w-32 h-24 rounded object-cover border" />

                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-gray-900">
                            <?= $r['title'] ?>
                        </h3>

                        <p class="text-sm text-gray-600 mt-1 leading-5">
                            Giá: <?= number_format($r['price']) ?>đ<br>
                            Loại phòng: <?= $r['type'] ?>
                        </p>

                        <p class="text-sm mt-2 <?= statusColor($r['status']) ?>">
                            Trạng thái: 
                            <?= ($r['status'] == 'pending' ? '⏳ Chờ duyệt' : '') ?>
                            <?= ($r['status'] == 'approved' ? '✔ Đã duyệt' : '') ?>
                            <?= ($r['status'] == 'rejected' ? '✖ Bị từ chối' : '') ?>
                        </p>

                        <p class="text-xs text-gray-500 mt-2">
                            Ngày gửi yêu cầu: <?= $r['created_at'] ?>
                        </p>

                    </div>

                </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>

</main>
