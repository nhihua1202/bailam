<?php
require 'header.php';
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit;
}

$uid = $_SESSION['user']['id'];

/* ===== LẤY DANH SÁCH PHÒNG USER ĐANG THUÊ ===== */
$sql = "
SELECT 
    r.id AS request_id,
    r.created_at,
    p.id AS post_id,
    p.title,
    p.price,
    p.type,
    (
        SELECT filename 
        FROM post_images 
        WHERE post_id = p.id 
        ORDER BY id ASC 
        LIMIT 1
    ) AS thumbnail
FROM rental_requests r
JOIN posts p ON r.post_id = p.id
WHERE r.user_id = :uid
  AND r.status = 'approved'
GROUP BY p.id         -- tránh trùng phòng
ORDER BY r.created_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['uid' => $uid]);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===== CHUẨN HOÁ ẢNH ===== */
foreach ($rooms as &$room) {
    if (!empty($room['thumbnail'])) {
        $room['thumbnail'] = "uploads/" . $room['thumbnail'];
    } else {
        $room['thumbnail'] = "assets/no-image.png";
    }
}

/* ===== HÀM FORMAT GIÁ ===== */
function fmt_price($price) {
    if (!$price) return "—";
    $txt = rtrim(rtrim(number_format($price, 1, '.', ''), '0'), '.');
    return $txt . " triệu";
}
?>

<main class="max-w-4xl mx-auto p-4">

    <h2 class="text-2xl font-semibold mb-4 text-gray-900">
        Phòng bạn đang thuê
    </h2>

    <?php if (empty($rooms)): ?>
        <p class="text-gray-600 text-sm">Bạn chưa thuê phòng nào.</p>

    <?php else: ?>
        <div class="space-y-4">

            <?php foreach ($rooms as $r): ?>
                <a href="post_detail.php?id=<?= $r['post_id'] ?>"
                   class="block p-4 bg-white border border-gray-200 rounded-lg flex gap-4 hover:shadow-md transition">

                    <img src="<?= $r['thumbnail'] ?>"
                         class="w-32 h-24 rounded object-cover border"
                         onerror="this.src='assets/no-image.png';">

                    <div class="flex-1">

                        <h3 class="text-lg font-medium text-gray-900">
                            <?= htmlspecialchars($r['title']) ?>
                        </h3>

                        <p class="text-sm text-gray-600 mt-1 leading-5">
                            Giá: <?= fmt_price($r['price']) ?><br>
                            Loại phòng: <?= htmlspecialchars($r['type']) ?>
                        </p>

                        <p class="text-sm mt-2 font-medium text-green-700">
                            ✔ Đang thuê
                        </p>

                        <p class="text-xs text-gray-500 mt-2">
                            Ngày đăng ký: <?= $r['created_at'] ?>
                        </p>

                    </div>

                </a>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>

</main>
