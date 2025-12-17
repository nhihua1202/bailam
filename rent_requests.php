<?php
require 'header.php';
require 'db.php';

/* ===============================
   KIỂM TRA ĐĂNG NHẬP
================================ */
if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit;
}

$user = $_SESSION['user'];
$uid  = $user['id'];

/* ===============================
   LẤY DANH SÁCH ĐƠN ĐĂNG KÝ
================================ */
$stmt = $pdo->prepare("
    SELECT 
        r.id,
        r.status,
        r.created_at,
        p.id   AS post_id,
        p.title,
        p.price,
        p.type
    FROM rental_requests r
    JOIN posts p ON r.post_id = p.id
    WHERE r.user_id = :uid
    ORDER BY r.id DESC
");
$stmt->execute(['uid' => $uid]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ===============================
   LẤY ẢNH ĐẠI DIỆN (KHÔNG VỠ)
================================ */
foreach ($requests as &$req) {
    $img = $pdo->prepare("
        SELECT filename
        FROM post_images
        WHERE post_id = :pid
        ORDER BY id ASC
        LIMIT 1
    ");
    $img->execute(['pid' => $req['post_id']]);
    $imgData = $img->fetch(PDO::FETCH_ASSOC);

    if (!empty($imgData['filename']) && file_exists(__DIR__ . '/uploads/' . $imgData['filename'])) {
        $req['thumbnail'] = 'uploads/' . $imgData['filename'];
    } else {
        $req['thumbnail'] = 'assets/no-image.png';
    }
}
unset($req);
?>

<main class="max-w-4xl mx-auto p-4">

    <h2 class="text-2xl font-semibold mb-6 text-center text-gray-900">
        Đơn đăng ký thuê phòng của bạn
    </h2>

    <?php if (empty($requests)): ?>
        <p class="text-center text-gray-600 text-sm">
            Bạn chưa gửi đơn đăng ký thuê phòng nào.
        </p>
    <?php else: ?>

        <div class="space-y-5">

            <?php foreach ($requests as $r): ?>

                <div class="bg-white border rounded-lg p-4 flex gap-4 shadow-sm hover:shadow transition">

                    <!-- ẢNH -->
                    <a href="post_detail.php?id=<?= $r['post_id'] ?>" class="flex-shrink-0">
                        <img src="<?= htmlspecialchars($r['thumbnail']) ?>"
                             alt="Ảnh phòng"
                             class="w-32 h-24 rounded object-cover border">
                    </a>

                    <!-- THÔNG TIN -->
                    <div class="flex-1 flex flex-col justify-between">

                        <div>
                            <a href="post_detail.php?id=<?= $r['post_id'] ?>">
                                <h3 class="text-lg font-semibold text-gray-900 hover:underline">
                                    <?= htmlspecialchars($r['title']) ?>
                                </h3>
                            </a>

                            <p class="text-sm text-gray-700 mt-1">
                                <?php
                                    $price = rtrim(
                                        rtrim(number_format($r['price'], 1, ',', '.'), '0'),
                                        ','
                                    );
                                ?>
                                Giá: <span class="font-medium"><?= $price ?> triệu / tháng</span><br>
                                Loại phòng: <?= htmlspecialchars($r['type']) ?>
                            </p>
                        </div>

                        <!-- TRẠNG THÁI + NGÀY -->
                        <div class="flex items-center justify-between mt-3">

                            <span class="text-sm font-medium
                                <?php
                                    if ($r['status'] === 'approved') echo 'text-green-600';
                                    elseif ($r['status'] === 'rejected') echo 'text-red-600';
                                    elseif ($r['status'] === 'cancelled') echo 'text-gray-500';
                                    else echo 'text-yellow-600';
                                ?>">
                                <?php
                                    if ($r['status'] === 'approved') echo '✔ Đã được duyệt';
                                    elseif ($r['status'] === 'rejected') echo '✖ Bị từ chối';
                                    elseif ($r['status'] === 'cancelled') echo '🚫 Đã hủy';
                                    else echo '⏳ Đang chờ duyệt';
                                ?>
                            </span>

                            <span class="text-xs text-gray-500">
                                <?= date('d/m/Y', strtotime($r['created_at'])) ?>
                            </span>
                        </div>

<!-- NÚT HÀNH ĐỘNG -->
<?php if ($r['status'] === 'pending'): ?>
    <div class="mt-4 text-right">
        <a href="cancel_rent_request.php?id=<?= $r['id'] ?>"
           onclick="return confirm('Bạn có chắc chắn muốn hủy đăng ký thuê phòng này không?');"
           class="inline-flex items-center px-4 py-2 text-sm font-medium
                  text-red-600 border border-red-600 rounded-md
                  hover:bg-red-50 transition">
            Hủy đăng ký
        </a>
    </div>
<?php endif; ?>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</main>
