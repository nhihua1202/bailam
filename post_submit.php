<?php
session_start();
require 'db.php';

// =====================================================
// 1. KIỂM TRA SESSION USER
// =====================================================
if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
    die("❌ Lỗi: Bạn chưa đăng nhập hoặc session đã hết hạn.");
}

$user_id = intval($_SESSION['user']['id']); // đảm bảo dạng số

// =====================================================
// 2. KIỂTRA USER_ID CÓ TỒN TẠI TRONG BẢNG users KHÔNG
// =====================================================
$checkUser = $pdo->prepare("SELECT id FROM users WHERE id = ?");
$checkUser->execute([$user_id]);

if ($checkUser->rowCount() == 0) {
    die("❌ Lỗi: user_id ($user_id) không tồn tại trong bảng users!");
}

// =====================================================
// 3. LẤY DỮ LIỆU FORM
// =====================================================
$title = trim($_POST['title'] ?? '');
$type = trim($_POST['type'] ?? '');
$khu_vuc = trim($_POST['khu_vuc'] ?? '');
$price = trim($_POST['price'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$zalo = trim($_POST['zalo'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($title === '' || $price === '' || $khu_vuc === '') {
    header('Location: post_new.php?error=missing');
    exit;
}

// =====================================================
// 4. INSERT BÀI ĐĂNG
// =====================================================
$stmt = $pdo->prepare("
    INSERT INTO posts (user_id, title, type, khu_vuc, price, phone, zalo, description, status, created_at)
    VALUES (?,?,?,?,?,?,?,?,?,NOW())
");

try {
    $stmt->execute([
        $user_id,
        $title,
        $type,
        $khu_vuc,
        $price,
        $phone,
        $zalo,
        $description,
        'pending'
    ]);
} catch (PDOException $e) {
    die("❌ Lỗi INSERT posts: " . $e->getMessage());
}

$post_id = $pdo->lastInsertId();

// =====================================================
// 5. UPLOAD ẢNH
// =====================================================
if (!empty($_FILES['images']) && !empty($_FILES['images']['name'][0])) {

    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
        if (!is_uploaded_file($tmp)) continue;

        $name = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($_FILES['images']['name'][$i]));
        $target = $uploadDir . time() . '_' . $name;

        if (move_uploaded_file($tmp, $target)) {
            $fname = basename($target);
            $stmt = $pdo->prepare("INSERT INTO post_images (post_id, filename) VALUES (?, ?)");
            $stmt->execute([$post_id, $fname]);
        }
    }
}

// =====================================================
// 6. HOÀN TẤT – CHUYỂN TRANG
// =====================================================
header("Location: my_posts.php?success=1");
exit;
