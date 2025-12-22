<?php
require 'header.php';
require 'db.php';
require 'functions.php';

$u = currentUser();

// CHỈ ADMIN HOẶC LANDLORD
if (!$u || !in_array($u['role'], ['admin', 'landlord'])) {
    echo "<div class='min-h-screen flex items-center justify-center'>
            <h2 class='text-xl font-bold text-red-500'>⚠️ Bạn không có quyền truy cập</h2>
          </div>";
    exit;
}

$isAdmin = $u['role'] === 'admin';
$isLandlord = $u['role'] === 'landlord';

// ADMIN xem tất cả, LANDLORD xem bài của họ
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

<div class="min-h-screen bg-gray-50/50 py-12 px-4">
<main class="max-w-5xl mx-auto">

<!-- HEADER -->
<div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
            <span class="p-2 bg-blue-600 rounded-2xl text-white">📋</span>
            Quản lý tin đăng
        </h2>
        <p class="text-gray-500 mt-2 ml-12">
            Tổng cộng: <span class="text-blue-600 font-bold"><?= count($posts) ?></span> bài đăng
        </p>
    </div>

    <?php if ($isLandlord): ?>
        <a href="post_new.php"
           class="px-4 py-2 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700">
            + Đăng tin mới
        </a>
    <?php endif; ?>
</div>

<?php if (!$posts): ?>
    <div class="bg-white p-20 rounded-3xl text-center">
        <h3 class="text-xl font-bold">Chưa có bài đăng</h3>
    </div>
<?php else: ?>

<div class="space-y-6">
<?php foreach ($posts as $p):

$img = $p['filename']
    ? 'uploads/' . $p['filename']
    : 'https://via.placeholder.com/400x250?text=No+Image';

$statusMap = [
    'pending'  => ['Chờ duyệt', 'bg-amber-50 text-amber-700'],
    'approved' => ['Đã duyệt', 'bg-emerald-50 text-emerald-700'],
    'rejected' => ['Từ chối', 'bg-rose-50 text-rose-700']
];
[$statusText, $statusCss] = $statusMap[$p['status']] ?? [$p['status'], 'bg-gray-50'];
?>

<div class="bg-white p-5 rounded-2xl shadow flex gap-6">
    <img src="<?= esc($img) ?>" class="w-56 h-40 object-cover rounded-xl">

    <div class="flex-1 flex flex-col justify-between">
        <div>
            <h3 class="text-xl font-bold"><?= esc($p['title']) ?></h3>

            <div class="flex items-center gap-3 mt-2 text-sm text-gray-500">
                <span>📅 <?= date('d/m/Y', strtotime($p['created_at'])) ?></span>

                <span class="px-3 py-1 rounded-lg text-xs font-bold <?= $statusCss ?>">
                    <?= $statusText ?>
                </span>

                <?php if ($isAdmin): ?>
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold">
                        Chủ bài: <?= esc($p['owner_name']) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="mt-4 flex gap-3">

        <?php if ($isAdmin): ?>

            <?php if ($p['status'] === 'pending'): ?>
                <a href="admin/approve.php?id=<?= $p['id'] ?>&action=approve"
                   class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-bold">
                    ✔ Duyệt
                </a>

                <a href="admin/approve.php?id=<?= $p['id'] ?>&action=reject"
                   class="px-4 py-2 bg-rose-600 text-white rounded-xl font-bold">
                    ✖ Từ chối
                </a>
            <?php endif; ?>

            <a href="delete_post.php?id=<?= $p['id'] ?>"
               onclick="return confirm('Xóa bài này?')"
               class="px-4 py-2 border border-rose-200 text-rose-600 rounded-xl font-bold">
                🗑 Xóa
            </a>

        <?php else: ?>

            <a href="edit_post.php?id=<?= $p['id'] ?>"
               class="px-4 py-2 bg-amber-400 rounded-xl font-bold">
                ✏ Sửa
            </a>

            <a href="delete_post.php?id=<?= $p['id'] ?>"
               onclick="return confirm('Xóa bài này?')"
               class="px-4 py-2 border text-rose-600 rounded-xl font-bold">
                🗑 Xóa
            </a>

        <?php endif; ?>

        </div>
    </div>
</div>

<?php endforeach; ?>
</div>
<?php endif; ?>

</main>
</div>
