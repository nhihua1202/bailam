<?php
require 'header.php';
require 'db.php';
require 'functions.php';

if (!isset($_SESSION['user'])) {
    header("Location: auth.php?mode=login");
    exit;
}

$post_id = intval($_GET['post_id'] ?? 0);

/* LẤY BÀI ĐĂNG */
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND status = 'approved'");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    echo "<main class='max-w-3xl mx-auto p-4'>Không tìm thấy bài đăng hoặc chưa được duyệt.</main>";
    exit;
}

/* user_id = người thuê */
$user_id = $_SESSION['user']['id'];

/* KIỂM TRA YÊU CẦU CŨ */
$check = $pdo->prepare("
    SELECT id, status 
    FROM rental_requests
    WHERE post_id = ? AND user_id = ?
    ORDER BY id DESC LIMIT 1
");
$check->execute([$post_id, $user_id]);
$req = $check->fetch();

/* Nếu có yêu cầu trước đó */
if ($req) {
    if ($req['status'] === 'pending' || $req['status'] === 'approved') {
        echo "<main class='max-w-3xl mx-auto p-4 text-yellow-600'>
                Bạn đã gửi yêu cầu cho bài này rồi. Vui lòng chờ chủ phòng phản hồi.
              </main>";
        exit;
    }

    if ($req['status'] === 'rejected') {
        $pdo->prepare("DELETE FROM rental_requests WHERE id = ?")->execute([$req['id']]);
    }
}

/* GỬI YÊU CẦU MỚI */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullname  = trim($_POST['name']);
    $birthday  = trim($_POST['dob']);
    $phone     = trim($_POST['phone']);
    $email     = trim($_POST['email']);
    $cccd      = trim($_POST['cccd']);
    $address   = trim($_POST['address']);

    if ($fullname === '' || $phone === '' || $email === '') {
        echo "<main class='max-w-3xl mx-auto p-4 text-red-600'>Vui lòng nhập đầy đủ thông tin.</main>";
        exit;
    }

    $sql = "INSERT INTO rental_requests 
            (post_id, user_id, fullname, birthday, phone, gmail, cccd, address, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";

    $q = $pdo->prepare($sql);
    $q->execute([
        $post_id,
        $user_id,
        $fullname,
        $birthday,
        $phone,
        $email,
        $cccd,
        $address
    ]);

    echo "<main class='max-w-3xl mx-auto p-4 text-green-600'>
            Gửi yêu cầu thuê phòng thành công! Chủ phòng sẽ xem và liên hệ bạn.
          </main>";
    exit;
}
?>
<main class="max-w-2xl mx-auto p-4">
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-bold">Thuê phòng: <?= esc($post['title']) ?></h2>
        <p class="text-gray-600 mb-4">Vui lòng nhập thông tin để gửi yêu cầu thuê.</p>

        <form method="POST" class="grid grid-cols-1 gap-3">

            <label>Họ và tên:</label>
            <input name="name" required 
                   value="<?= esc($_SESSION['user']['name'] ?? '') ?>" 
                   class="border p-2 w-full rounded">

            <label>Ngày sinh:</label>
            <input type="date" name="dob" class="border p-2 w-full rounded">

            <label>Căn cước công dân:</label>
            <input name="cccd" class="border p-2 w-full rounded">

            <label>Quê quán:</label>
            <input name="address" class="border p-2 w-full rounded">

            <label>Số điện thoại:</label>
            <input name="phone" required 
                   value="<?= esc($_SESSION['user']['phone'] ?? '') ?>" 
                   class="border p-2 w-full rounded">

            <label>Email:</label>
            <input name="email" required 
                   value="<?= esc($_SESSION['user']['email'] ?? '') ?>" 
                   class="border p-2 w-full rounded">

            <button class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Gửi yêu cầu thuê phòng
            </button>
        </form>
    </div>
</main>
</body>
</html>
