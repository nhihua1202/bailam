<?php
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
    echo "<main class='max-w-4xl mx-auto p-10 text-center'>
            <h2 class='text-2xl text-red-600 font-bold'>Bài đăng không tồn tại</h2>
            <a href='index.php' class='text-blue-500 underline'>Quay về trang chủ</a>
          </main>";
    exit;
}

$imgQ = $pdo->prepare("SELECT filename FROM post_images WHERE post_id = ?");
$imgQ->execute([$post_id]);
$photos = $imgQ->fetchAll(PDO::FETCH_COLUMN);

$user = currentUser();
$is_owner = $user && $user['id'] == $post['user_id'];
?>

<main class="max-w-6xl mx-auto p-4 md:p-8">

    <!-- ================= ẢNH + CHỦ NHÀ ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

        <!-- ẢNH -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-4">

                <?php if (!empty($photos)): ?>

                    <!-- ẢNH LỚN -->
                    <div class="w-full h-[420px] mb-4 overflow-hidden rounded-xl">
                        <img
                            id="mainImage"
                            src="/hiihi/uploads/<?php echo esc($photos[0]); ?>"
                            class="w-full h-full object-cover"
                            onerror="this.src='/hiihi/uploads/noimage.png';"
                        >
                    </div>

                    <!-- ẢNH NHỎ -->
                    <?php if (count($photos) > 1): ?>
                        <div class="flex gap-3 overflow-x-auto pb-2">
                            <?php foreach ($photos as $index => $img): ?>
                                <img
                                    src="/hiihi/uploads/<?php echo esc($img); ?>"
                                    onclick="changeImage(this)"
                                    class="w-24 h-20 object-cover rounded-lg cursor-pointer border
                                           <?php echo $index === 0 ? 'border-blue-500' : 'border-gray-300'; ?>
                                           hover:border-blue-500 transition"
                                    onerror="this.src='/hiihi/uploads/noimage.png';"
                                >
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="w-full h-[420px]">
                        <img src="/hiihi/uploads/noimage.png" class="w-full h-full object-cover opacity-50">
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- CHỦ NHÀ -->
        <div class="lg:col-span-1">
            <div class="sticky top-20 space-y-4">

                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 relative">
                        <img
                            src="<?php echo !empty($post['owner_avatar']) ? '/hiihi/uploads/'.esc($post['owner_avatar']) : '/hiihi/uploads/default-avatar.png'; ?>"
                            class="w-full h-full rounded-full object-cover border-4 border-blue-50"
                        >
                        <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
                    </div>

                    <p class="text-xs text-gray-400 uppercase font-bold">Người đăng</p>
                    <h4 class="text-xl font-black text-gray-800 mb-4"><?php echo esc($post['owner_name']); ?></h4>

                    <a href="tel:<?php echo esc($post['owner_phone']); ?>"
                       class="block w-full bg-blue-600 text-white py-3 rounded-xl font-bold mb-3 hover:bg-blue-700">
                        📞 <?php echo esc($post['owner_phone']); ?>
                    </a>

                    <a href="https://id.zalo.me/account?continue=https%3A%2F%2Fchat.zalo.me%2F"
                    <?php echo esc($post['owner_zalo']); ?>" target="_blank"
                       class="block w-full border-2 border-blue-600 text-blue-600 py-3 rounded-xl font-bold hover:bg-blue-50">
                        💬 Nhắn Zalo
                    </a>

                    <div class="mt-5 pt-5 border-t">
                        <?php if (!$is_owner): ?>
                            <a href="rent_submit.php?post_id=<?php echo $post_id; ?>"
                               class="block bg-green-500 text-white py-3 rounded-xl font-bold hover:bg-green-600">
                                📩 Gửi yêu cầu thuê
                            </a>
                        <?php else: ?>
                            <div class="bg-blue-50 text-blue-700 p-3 rounded-xl font-bold text-sm">
                                👋 Bài đăng của bạn
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-amber-50 p-4 rounded-xl border border-amber-100">
                    <p class="text-amber-700 text-[11px] font-medium leading-relaxed italic"> * Lưu ý: Không đặt cọc tiền khi chưa xem phòng trực tiếp. </p>
                </div>

            </div>
        </div>
    </div>

    <!-- ================= THÔNG TIN PHÒNG ================= -->
    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">

        <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
            <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded font-bold uppercase text-xs">
                <?php echo esc($post['type']); ?>
            </span>
            <span>• Đăng ngày <?php echo date('d/m/Y', strtotime($post['created_at'])); ?></span>
        </div>

        <h1 class="text-3xl font-black text-gray-900 mb-4"><?php echo esc($post['title']); ?></h1>

        <p class="text-lg text-gray-600 mb-6">📍 <?php echo esc($post['khu_vuc']); ?></p>

        <div class="grid grid-cols-2 gap-6 border-y py-6 mb-6">

            <div>
                <p class="text-xs uppercase text-gray-400 font-bold">Giá thuê</p>
                <p class="text-2xl font-black text-red-600">
<?php
$gia_raw = trim($post['price']);

if ($gia_raw === '' || $gia_raw === null) {
    echo '<span class="text-gray-400">Liên hệ</span>';
} else {

    // đổi dấu , thành .
    $gia_clean = str_replace(',', '.', $gia_raw);

    // nếu là số (2.4, 1500000, 3)
    if (is_numeric($gia_clean)) {

        // nếu lớn hơn 1000 → coi là VNĐ
        if ($gia_clean > 1000) {
            echo number_format($gia_clean / 1000000, 1, ',', '.') . ' triệu/tháng';
        } else {
            // nếu là 2.4 → coi là triệu
            echo number_format($gia_clean, 1, ',', '.') . ' triệu/tháng';
        }

    } else {
        // chữ: "2tr", "1.5 triệu", ...
        echo esc($gia_raw);
    }
}
?>
</p>
            </div>

            <div>
                <p class="text-xs uppercase text-gray-400 font-bold">Trạng thái</p>
                <p class="text-xl font-bold <?php echo $post['status_rent'] ? 'text-gray-500' : 'text-green-600'; ?>">
                    <?php echo $post['status_rent'] ? 'Đã thuê' : 'Đang trống'; ?>
                </p>
            </div>

        </div>

        <h3 class="text-xl font-bold mb-3 border-l-4 border-blue-600 pl-3">Mô tả chi tiết</h3>
        <div class="text-gray-700 leading-relaxed text-lg whitespace-pre-line">
            <?php echo nl2br(esc($post['description'])); ?>
        </div>

    </div>
</main>

<script>
function changeImage(el) {
    document.getElementById('mainImage').src = el.src;
    document.querySelectorAll('[onclick="changeImage(this)"]').forEach(i => {
        i.classList.remove('border-blue-500');
        i.classList.add('border-gray-300');
    });
    el.classList.add('border-blue-500');
}
</script>
<style>
    body {
        background-color: #fdf6ee !important;
    }
</style>
<?php require 'footer.php'; ?>
</body>
</html>
