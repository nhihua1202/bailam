<?php
session_start();
require 'db.php';
require 'functions.php';

/* ===============================
    KIỂM TRA ĐĂNG NHẬP
================================ */
if (!isset($_SESSION['user'])) {
    header("Location: auth.php?mode=login");
    exit;
}

$post_id = (int)($_GET['post_id'] ?? 0);
$user_id = $_SESSION['user']['id'];

/* ===============================
    LẤY BÀI ĐĂNG ĐÃ DUYỆT
================================ */
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND status = 'approved'");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    require 'header.php';
    echo "
    <main class='max-w-3xl mx-auto p-6 text-red-600 min-h-[60vh] flex flex-col items-center justify-center'>
        <p class='text-xl font-bold mb-4'>❌ Không tìm thấy bài đăng.</p>
        <a href='index.php' class='bg-blue-600 text-white px-4 py-2 rounded'>Quay về trang chủ</a>
    </main>";
    require 'footer.php';
    exit;
}

/* ===============================
    KIỂM TRA ĐƠN CŨ
================================ */
$check = $pdo->prepare("
    SELECT status 
    FROM rental_requests 
    WHERE post_id = ? AND user_id = ?
    ORDER BY id DESC LIMIT 1
");
$check->execute([$post_id, $user_id]);
$req = $check->fetch(PDO::FETCH_ASSOC);

if ($req && in_array($req['status'], ['pending', 'approved'])) {
    require 'header.php';
    echo "
    <main class='max-w-3xl mx-auto p-6 text-yellow-600 min-h-[60vh] flex flex-col items-center justify-center'>
        <p class='text-xl font-bold mb-4'>⏳ Bạn đã gửi yêu cầu cho phòng này.</p>
        <a href='index.php' class='bg-gray-800 text-white px-4 py-2 rounded'>Về trang chủ</a>
    </main>";
    require 'footer.php';
    exit;
}

/* ===============================
    DỮ LIỆU FORM
================================ */
$data = [
    'name'    => '',
    'dob'     => '',
    'cccd'    => '',
    'address' => '',
    'phone'   => $_SESSION['user']['phone'] ?? '',
    'email'   => $_SESSION['user']['email'] ?? ''
];
$errors = [];

/* ===============================
    XỬ LÝ SUBMIT
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $k => $v) {
        $data[$k] = trim($_POST[$k] ?? '');
        if ($data[$k] === '') {
            $errors[$k] = 'Không được để trống';
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO rental_requests
            (post_id, user_id, fullname, birthday, phone, gmail, cccd, address, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
        ");
        $stmt->execute([
            $post_id,
            $user_id,
            $data['name'],
            $data['dob'],
            $data['phone'],
            $data['email'],
            $data['cccd'],
            $data['address']
        ]);

        require 'header.php';
        echo "
        <main class='min-h-[70vh] flex items-center justify-center p-4 bg-pink-50'>
            <div class='bg-white p-8 rounded-xl shadow-xl border text-center max-w-md'>
                <h2 class='text-2xl font-bold text-green-600 mb-2'>✔ Gửi yêu cầu thành công!</h2>
                <p class='text-gray-600 mb-6'>Chủ trọ sẽ sớm liên hệ với bạn.</p>
                <a href='index.php'
                   class='block w-full bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700'>
                   VỀ TRANG CHỦ
                </a>
            </div>
        </main>";
        require 'footer.php';
        exit;
    }
}

/* ===============================
    BẮT ĐẦU GIAO DIỆN
================================ */
require 'header.php';
?>

<style>
    body { background:#FFF0F5 }
</style>

<main class="max-w-2xl mx-auto p-4 my-12">
    <div class="bg-white p-6 rounded-lg shadow-xl border">

        <h2 class="text-xl font-bold mb-6">
            Thuê phòng: <?= esc($post['title']) ?>
        </h2>

        <form method="POST" class="grid gap-4">

            <?php
            function err($k, $e) {
                return isset($e[$k]) ? "<p class='text-red-600 text-xs mt-1'>{$e[$k]}</p>" : "";
            }
            ?>

            <div>
                <label>Họ và tên *</label>
                <input name="name" value="<?= esc($data['name']) ?>" class="w-full border p-2 rounded">
                <?= err('name', $errors) ?>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Ngày sinh *</label>
                    <input type="date" name="dob" value="<?= esc($data['dob']) ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label>CCCD *</label>
                    <input name="cccd" maxlength="12" value="<?= esc($data['cccd']) ?>" class="w-full border p-2 rounded">
                </div>
            </div>

            <div>
                <label>Quê quán *</label>
                <input name="address" value="<?= esc($data['address']) ?>" class="w-full border p-2 rounded">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>SĐT *</label>
                    <input name="phone" value="<?= esc($data['phone']) ?>" class="w-full border p-2 rounded">
                </div>
                <div>
                    <label>Email *</label>
                    <input type="email" name="email" value="<?= esc($data['email']) ?>" class="w-full border p-2 rounded">
                </div>
            </div>

            <button class="bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700">
                GỬI YÊU CẦU THUÊ
            </button>

        </form>
    </div>
</main>

<?php require 'footer.php'; ?>
</body>
</html>
