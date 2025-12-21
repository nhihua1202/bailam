<?php
require "header.php";
require "db.php";
require "functions.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'owner') {
    echo "<h2>Bạn không có quyền truy cập</h2>";
    exit;
}

$owner_id = $_SESSION['user']['id'];

$stmt = $pdo->prepare("
    SELECT p.*,
        (SELECT COUNT(*) FROM rent_requests r WHERE r.post_id = p.id AND r.status = 'pending') AS pending_count
    FROM posts p
    WHERE p.owner_id = ?
    ORDER BY p.created_at DESC
");
$stmt->execute([$owner_id]);
$posts = $stmt->fetchAll();
?>

<main class="max-w-5xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Quản lý phòng</h2>

    <?php foreach ($posts as $p): ?>
    <div class="bg-white p-4 rounded shadow mb-4 flex justify-between items-center">

        <div>
            <p class="text-lg font-semibold"><?= esc($p['title']) ?></p>
            <p class="text-gray-600"><?= esc($p['address']) ?></p>
            <p>Giá: <b><?= number_format($p['price']) ?> triệu</b></p>
            <p>Trạng thái: 
                <span class="font-semibold text-blue-600"><?= $p['status'] ?></span>
            </p>

            <p class="mt-1">
                Yêu cầu thuê: 
                <b class="text-red-600"><?= $p['pending_count'] ?></b>
            </p>
        </div>

        <div class="flex gap-2">
            <a href="owner_rent_requests.php?post_id=<?= $p['id'] ?>"
               class="px-3 py-2 bg-blue-600 text-white rounded">Xem yêu cầu</a>

            <?php if ($p['status'] === 'rented'): ?>
            <button onclick="endRent(<?= $p['id'] ?>)"
                    class="px-3 py-2 bg-yellow-600 text-white rounded">
                Hết hạn
            </button>
            <?php endif; ?>
        </div>

    </div>
    <?php endforeach; ?>
</main>

<script>
function endRent(id) {
    if (!confirm("Xác nhận hết hạn phòng?")) return;

    fetch("update_post_status.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "post_id=" + id + "&action=available"
    })
    .then(() => location.reload());
}
</script>
