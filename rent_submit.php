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
    echo "<main class='max-w-3xl mx-auto p-4 text-red-600'>
            ❌ Không tìm thấy bài đăng hoặc bài đăng chưa được duyệt.
          </main>";
    exit;
}

$user_id = $_SESSION['user']['id'];

/* ===============================
   KIỂM TRA ĐƠN CŨ
================================ */
$check = $pdo->prepare("
    SELECT id, status
    FROM rental_requests
    WHERE post_id = ? AND user_id = ?
    ORDER BY id DESC
    LIMIT 1
");
$check->execute([$post_id, $user_id]);
$req = $check->fetch(PDO::FETCH_ASSOC);

if ($req && in_array($req['status'], ['pending', 'approved'])) {
    echo "<main class='max-w-3xl mx-auto p-4 text-yellow-600'>
            ⏳ Bạn đã gửi yêu cầu cho phòng này. Vui lòng chờ phản hồi.
          </main>";
    exit;
}

if ($req && $req['status'] === 'rejected') {
    $pdo->prepare("DELETE FROM rental_requests WHERE id = ?")->execute([$req['id']]);
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

    foreach ($data as $key => $val) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    /* RỖNG */
    foreach ($data as $key => $val) {
        if ($val === '') {
            $errors[$key] = 'Trường này không được để trống';
        }
    }

    /* CCCD 12 SỐ */
    if ($data['cccd'] !== '' && !preg_match('/^[0-9]{12}$/', $data['cccd'])) {
        $errors['cccd'] = 'Căn cước công dân phải đủ 12 chữ số';
    }

    /* SĐT 10 SỐ */
    if ($data['phone'] !== '' && !preg_match('/^[0-9]{10}$/', $data['phone'])) {
        $errors['phone'] = 'Số điện thoại phải đủ 10 chữ số';
    }

    /* KHÔNG LỖI → LƯU */
    if (empty($errors)) {

        $sql = "INSERT INTO rental_requests
                (post_id, user_id, fullname, birthday, phone, gmail, cccd, address, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";

        $stmt = $pdo->prepare($sql);
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

        echo "<main class='max-w-3xl mx-auto p-4 text-green-600 font-medium'>
                ✅ Gửi yêu cầu thuê phòng thành công!
              </main>";
        exit;
    }
}
?>

<!-- ===============================
     FORM
================================ -->
<main class="max-w-2xl mx-auto p-4">
    <div class="bg-white p-5 rounded-lg shadow border">

        <h2 class="text-xl font-bold mb-1">
            Thuê phòng: <?= esc($post['title']) ?>
        </h2>

        <p class="text-gray-600 text-sm mb-4">
            Vui lòng nhập đầy đủ thông tin để gửi yêu cầu thuê phòng
        </p>

        <form method="POST" class="grid gap-4">

            <div>
                <label class="text-sm font-medium">Họ và tên *</label>
                <input name="name" value="<?= esc($data['name']) ?>"
                       class="border p-2 w-full rounded">
                <?php if (isset($errors['name'])): ?>
                    <p class="text-red-600 text-xs mt-1"><?= $errors['name'] ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="text-sm font-medium">Ngày sinh *</label>
                <input type="date" name="dob" value="<?= esc($data['dob']) ?>"
                       class="border p-2 w-full rounded">
                <?php if (isset($errors['dob'])): ?>
                    <p class="text-red-600 text-xs mt-1"><?= $errors['dob'] ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="text-sm font-medium">CCCD (12 số) *</label>
                <input name="cccd" maxlength="12" value="<?= esc($data['cccd']) ?>"
                       class="border p-2 w-full rounded">
                <?php if (isset($errors['cccd'])): ?>
                    <p class="text-red-600 text-xs mt-1"><?= $errors['cccd'] ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="text-sm font-medium">Quê quán *</label>
                <input name="address" value="<?= esc($data['address']) ?>"
                       class="border p-2 w-full rounded">
                <?php if (isset($errors['address'])): ?>
                    <p class="text-red-600 text-xs mt-1"><?= $errors['address'] ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="text-sm font-medium">Số điện thoại (10 số) *</label>
                <input name="phone" maxlength="10" value="<?= esc($data['phone']) ?>"
                       class="border p-2 w-full rounded">
                <?php if (isset($errors['phone'])): ?>
                    <p class="text-red-600 text-xs mt-1"><?= $errors['phone'] ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="text-sm font-medium">Email *</label>
                <input type="email" name="email" value="<?= esc($data['email']) ?>"
                       class="border p-2 w-full rounded">
                <?php if (isset($errors['email'])): ?>
                    <p class="text-red-600 text-xs mt-1"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>

            <button
                class="mt-3 bg-green-600 text-white py-2 rounded-lg
                       hover:bg-green-700 transition font-medium">
                Gửi yêu cầu thuê phòng
            </button>

        </form>
    </div>
</main>

</body>
</html>
