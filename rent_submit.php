<?php
require 'header.php';
require 'db.php';
require 'functions.php';

/* ===============================
    KIỂM TRA ĐĂNG NHẬP
================================ */
if (!isset($_SESSION['user'])) {
    header("Location: auth.php?mode=login");
    exit;
}

$post_id = intval($_GET['post_id'] ?? 0);

/* ===============================
    LẤY BÀI ĐĂNG ĐÃ DUYỆT
================================ */
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND status = 'approved'");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo "<main class='max-w-3xl mx-auto p-4 text-red-600 min-h-[60vh] flex flex-col items-center justify-center'>
            <p class='text-xl font-bold mb-4'>❌ Không tìm thấy bài đăng.</p>
            <a href='index.php' class='bg-blue-600 text-white px-4 py-2 rounded'>Quay về trang chủ</a>
          </main>";
    include 'footer.php';
    exit;
}

$user_id = $_SESSION['user']['id'];

/* ===============================
    KIỂM TRA ĐƠN CŨ
================================ */
$check = $pdo->prepare("SELECT id, status FROM rental_requests WHERE post_id = ? AND user_id = ? ORDER BY id DESC LIMIT 1");
$check->execute([$post_id, $user_id]);
$req = $check->fetch(PDO::FETCH_ASSOC);

if ($req && in_array($req['status'], ['pending', 'approved'])) {
    echo "<main class='max-w-3xl mx-auto p-4 text-yellow-600 min-h-[60vh] flex flex-col items-center justify-center'>
            <p class='text-xl font-bold mb-4'>⏳ Bạn đã gửi yêu cầu cho phòng này. Vui lòng chờ phản hồi.</p>
            <a href='index.php' class='bg-gray-800 text-white px-4 py-2 rounded shadow'>Về trang chủ</a>
          </main>";
    include 'footer.php';
    exit;
}

/* ===============================
    XỬ LÝ FORM
================================ */
$errors = [];
$data = [
    'name'    => '',
    'dob'     => '',
    'cccd'    => '',
    'address' => '',
    'phone'   => $_SESSION['user']['phone'] ?? '',
    'email'   => $_SESSION['user']['email'] ?? ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $key => $val) { $data[$key] = trim($_POST[$key] ?? ''); }
    foreach ($data as $key => $val) { if ($val === '') $errors[$key] = 'Trường này không được để trống'; }

    if (empty($errors)) {
        $sql = "INSERT INTO rental_requests (post_id, user_id, fullname, birthday, phone, gmail, cccd, address, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$post_id, $user_id, $data['name'], $data['dob'], $data['phone'], $data['email'], $data['cccd'], $data['address']]);

        echo "<main class='min-h-[70vh] flex items-center justify-center p-4' style='background-color: #FFF0F5;'>
                <div class='bg-white p-8 rounded-xl shadow-xl border text-center max-w-md w-full'>
                    <div class='text-green-500 text-5xl mb-4'><i class='fas fa-check-circle'></i></div>
                    <h2 class='text-2xl font-bold text-gray-800 mb-2'>Gửi yêu cầu thành công!</h2>
                    <p class='text-gray-600 mb-8'>Cảm ơn bạn đã tin dùng Hanoi Rental. Chủ trọ sẽ sớm liên hệ với bạn.</p>
                    <a href='index.php' class='inline-block w-full bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 transition shadow-lg'>
                        <i class='fas fa-home mr-2'></i> QUAY VỀ TRANG CHỦ
                    </a>
                </div>
              </main>";
        include 'footer.php';
        exit;
    }
}
?>

<style>
    body { background-color: #FFF0F5 !important; }
    .hn-footer { background-color: #1a1a1a !important; }
</style>

<main class="max-w-2xl mx-auto p-4 my-12">
    <div class="bg-white p-6 rounded-lg shadow-xl border">
        
        <div class="flex justify-between items-start mb-6 border-b pb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Thuê phòng: <?= esc($post['title']) ?></h2>
                <p class="text-gray-500 text-xs mt-1">Hanoi Rental - Nhóm 12</p>
            </div>
            <a href="index.php" class="flex items-center gap-1 text-sm bg-gray-50 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded-md transition border shadow-sm font-medium">
                <i class="fas fa-home"></i> Trang chủ
            </a>
        </div>

        <form method="POST" class="grid gap-4">
            <div>
                <label class="text-sm font-semibold text-gray-700">Họ và tên *</label>
                <input required name="name" value="<?= esc($data['name']) ?>" class="mt-1 border p-2 w-full rounded focus:ring-2 focus:ring-green-400 outline-none">
                <?php if (isset($errors['name'])): ?><p class="text-red-600 text-xs mt-1"><?= $errors['name'] ?></p><?php endif; ?>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Ngày sinh *</label>
                    <input required type="date" name="dob" value="<?= esc($data['dob']) ?>" class="mt-1 border p-2 w-full rounded outline-none">
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">CCCD (12 số) *</label>
                    <input required name="cccd" type="text" pattern="\d{12}" title="CCCD phải bao gồm đúng 12 chữ số" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="<?= esc($data['cccd']) ?>" class="mt-1 border p-2 w-full rounded outline-none">
                </div>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Quê quán *</label>
                <input required name="address" value="<?= esc($data['address']) ?>" class="mt-1 border p-2 w-full rounded outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Số điện thoại (10 số) *</label>
                    <input required name="phone" type="text" pattern="\d{10}" title="Số điện thoại phải bao gồm đúng 10 chữ số" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="<?= esc($data['phone']) ?>" class="mt-1 border p-2 w-full rounded outline-none">
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Email *</label>
                    <input required type="email" name="email" value="<?= esc($data['email']) ?>" class="mt-1 border p-2 w-full rounded outline-none">
                </div>
            </div>

            <button type="submit" class="mt-4 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-bold shadow-md">
                GỬI YÊU CẦU THUÊ PHÒNG
            </button>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>