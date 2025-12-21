<?php
require 'header.php';
require 'db.php';
require 'functions.php';

$u = currentUser();

// CHO ADMIN HOẶC LANDLORD TRUY CẬP
if (!$u || !in_array($u['role'], ['admin', 'landlord'])) {
    echo "<div class='min-h-screen flex items-center justify-center'><h2 class='text-xl font-bold text-red-500'>⚠️ Bạn không có quyền truy cập</h2></div>";
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

<div class="min-h-screen bg-gray-50/50 py-12 px-4">
    <main class="max-w-5xl mx-auto">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <span class="p-2 bg-blue-600 rounded-2xl shadow-lg shadow-blue-200 text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </span>
                    Quản lý tin đăng
                </h2>
                <p class="text-gray-500 mt-2 font-medium ml-12">Tổng cộng: <span class="text-blue-600"><?= count($posts) ?></span> bài đăng</p>
            </div>
            
            <?php if (!$isAdmin): ?>
           <a href="add_post.php" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-100 hover:bg-blue-700 active:scale-95 transition-all w-fit">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Đăng tin mới
</a>
            <?php endif; ?>
        </div>

        <?php if (!$posts): ?>
            <div class="bg-white rounded-[2.5rem] p-20 text-center border border-dashed border-gray-300 shadow-sm">
                <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Chưa có bài đăng nào</h3>
                <p class="text-gray-500 mt-2">Hãy bắt đầu bằng cách đăng tin đầu tiên của bạn!</p>
            </div>
        <?php else: ?>
            
            <div class="space-y-6">
                <?php foreach ($posts as $p):
                    $img = $p['filename'] ? 'uploads/' . $p['filename'] : 'https://via.placeholder.com/400x250?text=No+Image';

                    // Cấu hình nhãn trạng thái mượt mà hơn
                    $statusConfig = [
                        'approved' => ['label' => 'Đã duyệt', 'css' => 'text-emerald-700 bg-emerald-50 border-emerald-100'],
                        'pending'  => ['label' => 'Chờ duyệt', 'css' => 'text-amber-700 bg-amber-50 border-amber-100'],
                        'rejected' => ['label' => 'Đã từ chối', 'css' => 'text-rose-700 bg-rose-50 border-rose-100']
                    ];
                    $cfg = $statusConfig[$p['status']] ?? ['label' => $p['status'], 'css' => 'bg-gray-50 text-gray-600 border-gray-100'];
                ?>

                <div class="bg-white rounded-[2rem] p-5 flex flex-col md:flex-row gap-6 border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300 group">
                    
                    <div class="relative shrink-0 overflow-hidden rounded-3xl w-full md:w-56 h-40">
                        <a href="view_post.php?id=<?= $p['id'] ?>">
                            <img src="<?= esc($img) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </a>
                        <div class="absolute top-3 left-3">
                             <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border shadow-sm uppercase tracking-wider <?= $cfg['css'] ?>">
                                <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                                <?= $cfg['label'] ?>
                             </span>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col justify-between py-1">
                        <div>
                            <div class="flex items-start justify-between gap-4">
                                <a href="view_post.php?id=<?= $p['id'] ?>" class="no-underline block">
                                    <h3 class="text-xl font-bold text-gray-800 hover:text-blue-600 transition-colors leading-tight">
                                        <?= esc($p['title']) ?>
                                    </h3>
                                </a>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-y-2 gap-x-4 mt-3">
                                <div class="flex items-center gap-1.5 text-gray-400 text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Đăng ngày: <?= date('d/m/Y', strtotime($p['created_at'])) ?></span>
                                </div>
                                
                                <?php if ($isAdmin): ?>
                                <div class="flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold border border-blue-100">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                                    Chủ bài: <?= esc($p['owner_name']) ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end md:justify-start gap-3">
                            <?php if ($isAdmin): ?>
                                <a href="approve_post.php?id=<?= $p['id'] ?>&action=approve"
                                   class="flex items-center gap-1.5 px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 shadow-md shadow-emerald-100 transition-all active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Duyệt tin
                                </a>
                                <a href="approve_post.php?id=<?= $p['id'] ?>&action=reject"
                                   class="flex items-center gap-1.5 px-5 py-2.5 bg-rose-600 text-white text-sm font-bold rounded-xl hover:bg-rose-700 shadow-md shadow-rose-100 transition-all active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Từ chối
                                </a>
                            <?php else: ?>
                                <a href="edit_post.php?id=<?= $p['id'] ?>"
                                   class="flex items-center gap-1.5 px-5 py-2.5 bg-amber-400 text-gray-900 text-sm font-bold rounded-xl hover:bg-amber-500 shadow-md shadow-amber-100 transition-all active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Chỉnh sửa
                                </a>
                                <a href="delete_post.php?id=<?= $p['id'] ?>"
                                   onclick="return confirm('Bạn chắc chắn muốn xóa bài này?')"
                                   class="flex items-center gap-1.5 px-5 py-2.5 bg-white text-rose-600 border-2 border-rose-50 text-sm font-bold rounded-xl hover:bg-rose-50 transition-all active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Xóa bài
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

<style>
    /* Bo góc tùy chỉnh nếu Tailwind không hỗ trợ đủ */
    .rounded-3xl { border-radius: 1.5rem; }
    .rounded-\[2rem\] { border-radius: 2rem; }
    .rounded-\[2\.5rem\] { border-radius: 2.5rem; }
</style>

</body>
</html>