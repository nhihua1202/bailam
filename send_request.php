<?php
require 'header.php';
require 'functions.php';
require 'db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: auth.php?mode=login");
    exit;
}

$u = $_SESSION['user'];
if (($u['role'] ?? 'renter') !== 'renter') {
    echo "<p class='p-4 text-red-600'>Chỉ người thuê mới được gửi yêu cầu.</p>";
    exit;
}

$post_id = intval($_POST['post_id'] ?? 0);

// Lấy thông tin bài đăng
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=? AND status='approved'");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    echo "<p class='p-4 text-red-600'>Bài đăng không tồn tại hoặc chưa được duyệt.</p>";
    exit;
}

// Lấy thông tin form
$full_name  = trim($_POST['full_name'] ?? '');
$dob        = trim($_POST['dob'] ?? '');
$gender     = trim($_POST['gender'] ?? '');
$nationality = trim($_POST['nationality'] ?? '');
$cccd       = trim($_POST['cccd'] ?? '');
$hometown   = trim($_POST['hometown'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$email      = trim($_POST['email'] ?? '');

// Kiểm tra dữ liệu
if ($full_name == '' || $dob == '' || $gender == '' || $phone == '' || $email == '') {
    echo "<p class='p-4 text-red-600'>Vui lòng nhập đầy đủ thông tin.</p>";
    exit;
}

// Chèn vào DB
$stmt = $pdo->prepare("
    INSERT INTO rent_requests
        (post_id, tenant_id, full_name, dob, gender, nationality, cccd, hometown, phone, email, status, created_at)
    VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
");

$stmt->execute([
    $post_id,
    $u['id'],         // tenant_id từ users.id
    $full_name,
    $dob,
    $gender,
    $nationality,
    $cccd,
    $hometown,
    $phone,
    $email
]);

?>
<main class="max-w-xl mx-auto p-6 bg-white mt-10 shadow rounded text-center">
    <h2 class="text-xl font-bold text-green-600">Gửi yêu cầu thuê thành công!</h2>
    <p class="mt-2">Chủ phòng sẽ xem xét và liên hệ với bạn.</p>

    <a href="post_detail.php?id=<?= $post_id ?>"
       class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded">
        Quay lại bài đăng
    </a>
</main>
</body>
</html>
