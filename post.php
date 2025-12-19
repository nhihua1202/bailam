<?php
// Đảm bảo không có bất kỳ dòng trống nào phía trên thẻ PHP này
require 'header.php';
require 'db.php';
require 'functions.php';

$post_id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT p.*, u.name AS owner_name, u.phone AS owner_phone, u.zalo AS owner_zalo, u.avatar AS owner_avatar
    FROM posts p
    JOIN users u ON p.user_id = u.id
    WHERE p.id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    echo "<main class='max-w-4xl mx-auto p-10 text-center'><h2 class='text-2xl text-red-600 font-bold'>Bài đăng không tồn tại.</h2><a href='index.php' class='text-blue-500 underline mt-4 inline-block'>Quay về trang chủ</a></main>";
    exit;
}

$imgQ = $pdo->prepare("SELECT filename FROM post_images WHERE post_id = ?");
$imgQ->execute([$post_id]);
$photos = $imgQ->fetchAll(PDO::FETCH_COLUMN);

$user = currentUser();
$is_owner = $user && $user['id'] == $post['user_id'];
?>

<main class="max-w-6xl mx-auto p-4 md:p-8 mt-0"> 

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-8 rounded-2xl overflow-hidden shadow-md border border-gray-100 bg-white">
        <?php if (!empty($photos)): ?>
            <div class="md:col-span-2 h-64 md:h-[400px]">
                <img src="/hiihi/uploads/<?php echo esc($photos[0]); ?>" class="w-full h-full object-cover hover:opacity-90 transition cursor-zoom-in" onerror="this.src='/hiihi/uploads/noimage.png';">
            </div>
            <div class="hidden md:grid col-span-2 grid-cols-2 grid-rows-2 gap-3">
                <?php for($i = 1; $i <= 4; $i++): ?>
                    <div class="h-[194px] bg-gray-100 overflow-hidden">
                        <?php if(isset($photos[$i])): ?>
                            <img src="/hiihi/uploads/<?php echo esc($photos[$i]); ?>" class="w-full h-full object-cover hover:opacity-90 transition cursor-zoom-in" onerror="this.src='/hiihi/uploads/noimage.png';">
                        <?php else: ?>
                            <div class="flex items-center justify-center h-full">
                                <img src="/hiihi/uploads/noimage.png" class="w-12 opacity-20">
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        <?php else: ?>
            <div class="col-span-4 h-96">
                <img src="/hiihi/uploads/noimage.png" class="w-full h-full object-cover opacity-50">
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                    <span class="bg-blue-100 text-blue-600 px-2.5 py-1 rounded-md font-bold uppercase tracking-wider text-xs"><?php echo esc($post['type']); ?></span>
                    <span>• Đăng ngày <?php echo date('d/m/Y', strtotime($post['created_at'])); ?></span>
                </div>
                
                <h1 class="text-3xl font-black text-gray-900 leading-tight mb-4"><?php echo esc($post['title']); ?></h1>
                
                <div class="flex items-center text-gray-600 mb-6">
                    <svg class="w-5 h-5 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                    <span class="text-lg font-medium"><?php echo esc($post['khu_vuc']); ?></span>
                </div>

                <div class="grid grid-cols-2 gap-4 border-y border-gray-100 py-6 my-6">
                    <div class="border-r border-gray-100 pr-4">
                        <p class="text-gray-400 text-xs font-bold uppercase mb-1">Mức giá thuê</p>
                        <p class="text-2xl font-black text-red-600">
                            <?php 
                            $gia = trim($post['price']);
                            if (!is_numeric($gia)) echo esc($gia);
                            else {
                                $trieu = ($gia < 100000) ? $gia : $gia / 1000000;
                                echo number_format($trieu, 1, ',', '.') . " triệu/tháng";
                            }
                            ?>
                        </p>
                    </div>
                    <div class="pl-4">
                        <p class="text-gray-400 text-xs font-bold uppercase mb-1">Trạng thái phòng</p>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full <?php echo $post['status_rent'] == 1 ? 'bg-gray-400' : 'bg-green-500 animate-pulse'; ?>"></span>
                            <p class="text-xl font-bold <?php echo $post['status_rent'] == 1 ? 'text-gray-500' : 'text-green-600'; ?>">
                                <?php echo $post['status_rent'] == 1 ? 'Đã thuê' : 'Đang trống'; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 border-l-4 border-blue-600 pl-3">Mô tả chi tiết</h3>
                    <div class="text-gray-700 leading-relaxed text-lg whitespace-pre-line">
                        <?php echo nl2br(esc($post['description'])); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="sticky-sidebar space-y-4">
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 text-center">
                    <div class="relative w-20 h-20 mx-auto mb-4">
                        <img src="<?php echo !empty($post['owner_avatar']) ? '/hiihi/uploads/'.esc($post['owner_avatar']) : '/hiihi/uploads/default-avatar.png'; ?>" 
                             class="w-full h-full rounded-full object-cover border-4 border-blue-50 shadow-sm">
                        <div class="absolute bottom-0 right-0 w-5 h-5 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    <p class="text-xs text-gray-400 font-bold uppercase mb-1">Người đăng tin</p>
                    <h4 class="text-xl font-black text-gray-800 mb-5"><?php echo esc($post['owner_name']); ?></h4>
                    
                    <div class="space-y-3">
                        <a href="tel:<?php echo esc($post['owner_phone']); ?>" 
                           class="flex items-center justify-center gap-2 w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <?php echo esc($post['owner_phone']); ?>
                        </a>
                        <a href="https://zalo.me/<?php echo esc($post['owner_zalo']); ?>" target="_blank"
                           class="flex items-center justify-center gap-2 w-full bg-white text-blue-600 border-2 border-blue-600 font-bold py-3.5 rounded-xl hover:bg-blue-50 transition">
                            Nhắn tin Zalo
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <?php if (!$is_owner): ?>
                            <a href="rent_submit.php?post_id=<?php echo $post_id; ?>"
                               class="block w-full bg-green-500 text-white font-bold py-4 rounded-xl hover:bg-green-600 transition shadow-lg shadow-green-100 uppercase text-sm tracking-widest">
                                📩 Gửi yêu cầu thuê
                            </a>
                        <?php else: ?>
                            <div class="bg-blue-50 text-blue-700 p-3 rounded-xl text-sm font-bold">
                                👋 Quản lý bài đăng của bạn
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-amber-50 p-4 rounded-xl border border-amber-100">
                    <p class="text-amber-700 text-[11px] font-medium leading-relaxed italic">
                        * Lưu ý: Không đặt cọc tiền khi chưa xem phòng trực tiếp.
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* Reset khoảng cách mặc định của trình duyệt */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    body { 
        background-color: #f8fafc; 
        padding-top: 0 !important; /* Xóa padding top của body */
    }

    /* Nếu Header của bạn là 'fixed', hãy điều chỉnh số này khớp với chiều cao header */
    main {
        margin-top: 20px; /* Chỉnh lại thành 80px nếu header bị đè lên nội dung */
    }

    .sticky-sidebar {
        position: sticky;
        top: 20px; /* Khoảng cách từ đỉnh khi cuộn */
    }
</style>

</body>
</html>