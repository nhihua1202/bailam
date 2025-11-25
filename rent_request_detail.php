<?php
require 'header.php';
require 'functions.php';
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: auth.php?mode=login");
    exit;
}

$u = $_SESSION['user'];
$id = intval($_GET['id'] ?? 0);

// Không có id
if ($id <= 0) {
    echo '<main class="p-4">Không tìm thấy yêu cầu.</main>';
    exit;
}

// Lấy dữ liệu yêu cầu thuê
$stmt = $pdo->prepare("
    SELECT rr.*, 
           p.title, p.price, p.khu_vuc, p.address, p.user_id AS landlord_id,
           u.name AS tenant_name, u.email AS tenant_email, u.phone AS tenant_phone
    FROM rent_requests rr
    JOIN posts p ON rr.post_id = p.id
    JOIN users u ON rr.tenant_id = u.id
    WHERE rr.id = ?
");
$stmt->execute([$id]);
$req = $stmt->fetch();

if (!$req) {
    echo '<main class="p-4 text-red-600">Yêu cầu không tồn tại.</main>';
    exit;
}

/* ======================================================
   QUYỀN TRUY CẬP 
   - Landlord: xem yêu cầu của bài đăng thuộc họ
   - Renter: xem yêu cầu của chính họ
====================================================== */

if ($u['role'] === 'landlord') {
    if ($req['landlord_id'] != $u['id']) {
        echo '<main class="p-4 text-red-600">Bạn không có quyền truy cập yêu cầu này.</main>';
        exit;
    }
}

if ($u['role'] === 'renter') {
    if ($req['tenant_id'] != $u['id']) {
        echo '<main class="p-4 text-red-600">Bạn không được xem yêu cầu này.</main>';
        exit;
    }
}

/* ======================================================
   LANDLORD xử lý approve / reject
====================================================== */
$msg = '';
if ($u['role'] === 'landlord' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'approve') {

            $pdo->prepare("UPDATE rent_requests SET status='approved' WHERE id=?")->execute([$id]);

            // cập nhật trạng thái bài đăng
            $pdo->prepare("UPDATE posts SET status='renting' WHERE id=?")->execute([$req['post_id']]);

            $msg = "Đã duyệt yêu cầu.";

            $req['status'] = 'approved';

        } elseif ($_POST['action'] === 'reject') {

            $pdo->prepare("UPDATE rent_requests SET status='rejected' WHERE id=?")->execute([$id]);

            $msg = "Đã từ chối yêu cầu.";

            $req['status'] = 'rejected';
        }
    }
}
?>

<main class="max-w-3xl mx-auto p-4">
  <div class="bg-white p-4 shadow rounded">

    <h2 class="text-xl font-semibold mb-3">📄 Chi tiết yêu cầu thuê phòng</h2>

    <?php if ($msg): ?>
        <p class="p-2 bg-green-200 text-green-700 rounded mb-3"><?= esc($msg) ?></p>
    <?php endif; ?>

    <!-- Thông tin bài đăng -->
    <h3 class="text-lg font-semibold mt-3">🏠 Thông tin bài đăng</h3>
    <div class="p-2">
      <p><strong>Tiêu đề:</strong> <?= esc($req['title']) ?></p>
      <p><strong>Khu vực:</strong> <?= esc($req['khu_vuc']) ?></p>
      <p><strong>Giá:</strong> <?= esc($req['price']) ?> / tháng</p>
      <p><strong>Địa chỉ:</strong> <?= esc($req['address']) ?></p>
    </div>

    <!-- Thông tin người thuê -->
    <h3 class="text-lg font-semibold mt-3">👤 Thông tin người thuê</h3>
    <div class="p-2">
      <p><strong>Họ tên:</strong> <?= esc($req['tenant_name']) ?></p>
      <p><strong>Email:</strong> <?= esc($req['tenant_email']) ?></p>
      <p><strong>SĐT:</strong> <a href="tel:<?= esc($req['tenant_phone']) ?>" class="text-blue-600"><?= esc($req['tenant_phone']) ?></a></p>

      <p class="mt-2"><strong>Thông tin chi tiết đã gửi:</strong></p>
      <p>Họ tên: <?= esc($req['full_name']) ?></p>
      <p>Ngày sinh: <?= esc($req['dob']) ?></p>
      <p>Giới tính: <?= esc($req['gender']) ?></p>
      <p>Quốc tịch: <?= esc($req['nationality']) ?></p>
      <p>CCCD: <?= esc($req['cccd']) ?></p>
      <p>Quê quán: <?= esc($req['hometown']) ?></p>
    </div>

    <!-- Thông tin yêu cầu -->
    <h3 class="text-lg font-semibold mt-3">📌 Yêu cầu</h3>
    <div class="p-2">
      <p><strong>Ngày gửi:</strong> <?= esc($req['created_at']) ?></p>
      <p><strong>Trạng thái:</strong>
        <span class="px-2 py-1 rounded
              <?php if ($req['status']=='pending') echo 'bg-yellow-200 text-yellow-700'; ?>
              <?php if ($req['status']=='approved') echo 'bg-green-200 text-green-700'; ?>
              <?php if ($req['status']=='rejected') echo 'bg-red-200 text-red-700'; ?>">
            <?= esc($req['status']) ?>
        </span>
      </p>
    </div>

    <!-- Landlord mới có nút xử lý -->
    <?php if ($u['role'] === 'landlord' && $req['status'] === 'pending'): ?>
    <form method="post" class="mt-4 flex gap-3">
      <button name="action" value="approve" class="bg-green-600 text-white px-4 py-2 rounded">Duyệt</button>
      <button name="action" value="reject" class="bg-red-600 text-white px-4 py-2 rounded">Từ chối</button>
    </form>
    <?php endif; ?>

    <div class="mt-5">
      <?php if ($u['role'] === 'landlord'): ?>
          <a href="manage_rent.php" class="text-blue-600">&larr; Quay lại danh sách yêu cầu</a>
      <?php else: ?>
          <a href="rent_my_requests.php" class="text-blue-600">&larr; Quay lại lịch sử của tôi</a>
      <?php endif; ?>
    </div>

  </div>
</main>
</body>
</html>
