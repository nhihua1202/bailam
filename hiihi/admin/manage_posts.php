<?php
require __DIR__ . '/../header.php';
require __DIR__ . '/../functions.php';
require __DIR__ . '/../db.php'; 

if(!isAdmin()) {
    echo "
    <div class='min-h-screen flex items-center justify-center bg-gray-50'>
        <div class='text-center p-8 bg-white rounded-3xl shadow-xl border border-gray-100 max-w-md mx-4'>
            <div class='w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4'>
                <svg class='w-8 h-8 text-red-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 15v2m0 0v2m0-2h2m-2 0H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>
            </div>
            <h2 class='text-xl font-black text-gray-900 mb-2'>Truy cập bị chặn</h2>
            <p class='text-gray-500 text-sm'>Khu vực này chỉ dành cho quản trị viên.</p>
            <a href='../index.php' class='mt-6 inline-block px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200'>Quay lại</a>
        </div>
    </div>";
    exit;
}

$stmt = $pdo->query("
    SELECT p.*, 
        (SELECT filename FROM post_images WHERE post_id = p.id LIMIT 1) AS filename,
        u.name AS owner_name, u.avatar AS owner_avatar
    FROM posts p 
    LEFT JOIN users u ON u.id = p.user_id
    ORDER BY p.created_at DESC
");
$posts = $stmt->fetchAll();
?>

<div class="min-h-screen bg-gray-50/50 py-8 px-4 font-sans">
    <main class="max-w-4xl mx-auto">
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                    <span class="p-2 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200 text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </span>
                    Quản trị bài đăng
                </h2>
            </div>
            <p class="text-gray-500 text-sm font-medium border-l-2 border-indigo-100 pl-4 hidden sm:block">
                Tổng: <span class="text-indigo-600 font-bold"><?= count($posts) ?></span> bài viết
            </p>
        </div>

        <?php if (!$posts): ?>
            <div class="bg-white rounded-[1.5rem] p-16 text-center border-2 border-dashed border-gray-100">
                <p class="text-gray-400 font-bold">Danh sách trống.</p>
            </div>
        <?php else: ?>
            
            <div class="grid grid-cols-1 gap-4">
                <?php foreach ($posts as $p): 
                    $img = $p['filename'] ? '../uploads/' . $p['filename'] : '../uploads/no-image.png';
                    $statusConfig = [
                        'approved' => ['label' => 'Đã duyệt', 'css' => 'text-indigo-600 bg-indigo-50 border-indigo-100'],
                        'pending'  => ['label' => 'Chờ duyệt', 'css' => 'text-amber-600 bg-amber-50 border-amber-100'],
                        'rejected' => ['label' => 'Từ chối', 'css' => 'text-rose-600 bg-rose-50 border-rose-100']
                    ];
                    $cfg = $statusConfig[$p['status']] ?? ['label' => $p['status'], 'css' => 'bg-gray-50 text-gray-500'];
                ?>

                <div class="bg-white rounded-2xl p-3 sm:p-4 flex flex-col sm:flex-row gap-4 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 group relative">
                    
                    <div class="relative shrink-0 overflow-hidden rounded-xl w-full sm:w-40 h-28 bg-gray-50">
                        <img src="<?= $img ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='../uploads/no-image.png'">
                        <div class="absolute top-2 left-2">
                             <span class="px-2 py-0.5 rounded-lg text-[9px] font-bold border shadow-sm uppercase tracking-wider <?= $cfg['css'] ?>">
                                <?= $cfg['label'] ?>
                             </span>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start gap-2">
                                <h3 class="text-base font-bold text-gray-800 leading-tight line-clamp-1 group-hover:text-indigo-600">
                                    <a href="../view_post.php?id=<?= $p['id'] ?>">
                                        <?= htmlspecialchars($p['title']) ?>
                                    </a>
                                </h3>
                                <p class="text-lg font-black text-indigo-600 shrink-0 tracking-tighter">
                                    <?= number_format($p['price'], 1, ',', '.') ?> 
                                    <span class="text-[9px] font-bold text-gray-400 uppercase">tr</span>
                                </p>
                            </div>
                            
                            <div class="flex items-center gap-4 mt-2">
                                <div class="flex items-center gap-1 text-gray-400 text-[10px] font-bold uppercase tracking-wider">
                                    <svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <?= htmlspecialchars($p['khu_vuc'] ?? '---') ?>
                                </div>
                                <div class="flex items-center gap-1.5 border-l border-gray-100 pl-4">
                                    <img src="../uploads/<?= $p['owner_avatar'] ?: 'default-avatar.png' ?>" class="w-5 h-5 rounded-full object-cover grayscale group-hover:grayscale-0 transition-all">
                                    <span class="text-[10px] font-semibold text-gray-500"><?= htmlspecialchars($p['owner_name'] ?? 'Guest') ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-end gap-2">
                            <?php if ($p['status'] !== 'approved'): ?>
                                <a href="approve.php?id=<?= $p['id'] ?>&action=approve" 
                                   class="flex items-center gap-1.5 px-4 py-1.5 bg-indigo-600 text-white text-[10px] font-bold rounded-lg hover:bg-indigo-700 transition-all active:scale-95 uppercase shadow-sm shadow-indigo-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Duyệt bài
                                </a>
                            <?php else: ?>
                                <a href="approve.php?id=<?= $p['id'] ?>&action=reject" 
                                   class="flex items-center gap-1.5 px-4 py-1.5 bg-indigo-500 text-white text-[10px] font-bold rounded-lg hover:bg-indigo-600 transition-all active:scale-95 uppercase shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Hủy duyệt
                                </a>
                            <?php endif; ?>

                            <a href="delete_post_admin.php?id=<?= $p['id'] ?>" 
                               onclick="return confirm('⚠️ Xóa bài viết này?')"
                               class="flex items-center justify-center p-2 bg-rose-50 text-rose-500 rounded-lg hover:bg-rose-500 hover:text-white transition-all border border-rose-100"
                               title="Xóa bài">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>