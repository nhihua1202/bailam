<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'header.php';
require 'db.php';

// ------------------- KIỂM TRA LOGIN -------------------
if (empty($_SESSION['user']['id'])) {
    header('Location: login.php');
    exit;
}
$userId = (int)$_SESSION['user']['id'];

$err = '';

// ================== XỬ LÝ SUBMIT ==================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $price = (int)($_POST['price'] ?? 0);
    $address = trim($_POST['address'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $area = trim($_POST['area'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $zalo = trim($_POST['zalo'] ?? '');

    // -------- Kiểm tra lỗi nhập --------
    if ($title === '' || $price <= 0) {
        $err = "Vui lòng nhập tiêu đề và giá hợp lệ.";
    } else {

        // --------- Upload hình ảnh ----------
        $images_arr = [];
        if (!empty($_FILES['images']['name']) && is_array($_FILES['images']['name'])) {
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {

                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmp = $_FILES['images']['tmp_name'][$i];

                    $name = preg_replace(
                        '/[^a-zA-Z0-9._-]/',
                        '_',
                        basename($_FILES['images']['name'][$i])
                    );

                    $new = 'uploads/' . time() . '_' . $name;

                    if (move_uploaded_file($tmp, $new)) {
                        $images_arr[] = $new;
                    }
                }
            }
        }

        // -------- INSERT DB --------
        $ins = $pdo->prepare("
            INSERT INTO listings 
            (user_id, title, type, price, address, area, description, phone, zalo, images, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
        ");

        $ins->execute([
            $userId,
            $title,
            $type,
            $price,
            $address,
            $area,
            $desc,
            $phone,
            $zalo,
            implode(',', $images_arr)
        ]);

        header('Location: dashboard.php');
        exit;
    }
}
?>
<main class="max-w-3xl mx-auto p-6">
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold text-blue-600">Tạo tin đăng mới</h2>

    <?php if ($err): ?>
      <div class="text-sm text-red-600 mb-3"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">

      <label class="block text-sm">Tiêu đề</label>
      <input name="title" class="w-full p-2 border rounded mb-3" required>

      <label class="block text-sm">Giá (VNĐ)</label>
      <input name="price" type="number" class="w-full p-2 border rounded mb-3" required>

      <label class="block text-sm">Địa chỉ</label>
      <input name="address" class="w-full p-2 border rounded mb-3">

      <label class="block text-sm">Loại phòng</label>
      <input name="type" class="w-full p-2 border rounded mb-3">

      <label class="block text-sm">Khu vực / Diện tích</label>
      <input name="area" class="w-full p-2 border rounded mb-3">

      <label class="block text-sm">Số điện thoại</label>
      <input name="phone" class="w-full p-2 border rounded mb-3">

      <label class="block text-sm">Zalo</label>
      <input name="zalo" class="w-full p-2 border rounded mb-3">

      <label class="block text-sm">Mô tả</label>
      <textarea name="description" class="w-full p-2 border rounded mb-3"></textarea>

      <label class="block text-sm">Hình ảnh</label>
      <input type="file" name="images[]" multiple class="mb-4">

      <div class="flex justify-between">
        <button class="px-4 py-2 rounded text-white bg-blue-500" type="submit">Tạo tin</button>
        <a href="dashboard.php" class="underline">Hủy</a>
      </div>

    </form>
  </div>
</main>

<?php include 'footer.php'; ?>
