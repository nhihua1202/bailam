<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'header.php';
require 'db.php';

// ---------------- KIỂM TRA LOGIN ----------------
if (empty($_SESSION['user']['id'])) {
    header('Location: login.php');
    exit;
}

$userId = (int)$_SESSION['user']['id'];
$role   = $_SESSION['user']['role'];

// ---------------- LẤY THÔNG TIN USER ----------------
$stmt = $pdo->prepare("SELECT email, role, created_at FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// ---------------- DUYỆT TIN (ADMIN) ----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_id']) && $role === 'admin') {
    $id = (int)$_POST['approve_id'];
    $u = $pdo->prepare("UPDATE listings SET status='approved' WHERE id = ?");
    $u->execute([$id]);
    header('Location: dashboard.php');
    exit;
}

// ---------------- LẤY TIN CỦA USER ----------------
$listings = $pdo->prepare("SELECT * FROM listings WHERE user_id = ? ORDER BY id DESC");
$listings->execute([$userId]);
$listings = $listings->fetchAll(PDO::FETCH_ASSOC);

// ---------------- ADMIN: LẤY TIN CHỜ DUYỆT ----------------
$pending = [];
if ($role === 'admin') {
    $pending = $pdo->query("
        SELECT l.*, u.email 
        FROM listings l 
        JOIN users u ON l.user_id = u.id 
        WHERE l.status='pending'
        ORDER BY l.id DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<main class="max-w-5xl mx-auto p-6">
  <div class="bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-bold text-blue-600">Dashboard</h2>
    <p class="text-sm text-gray-600">
      <?= htmlspecialchars($user['email']) ?> • <?= htmlspecialchars($user['role']) ?>
    </p>

    <!-- ADMIN SECTION -->
    <?php if ($role === 'admin'): ?>
      <section class="mt-4">
        <h3 class="font-semibold text-lg mb-2">Tin chờ duyệt</h3>

        <?php if (!$pending): ?>
          <p class="text-sm text-gray-600">Không có tin chờ duyệt.</p>
        <?php endif; ?>

        <?php foreach ($pending as $p): ?>
          <div class="border p-3 rounded mb-2">
            <div class="flex justify-between">
              <div>
                <strong><?= htmlspecialchars($p['title']) ?></strong>
                <div class="text-sm text-gray-600">
                  Đăng bởi <?= htmlspecialchars($p['email']) ?>
                </div>
              </div>

              <form method="post">
                <input type="hidden" name="approve_id" value="<?= intval($p['id']) ?>">
                <button class="px-3 py-1 bg-blue-500 text-white rounded">
                  Duyệt
                </button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </section>
    <?php endif; ?>

    <!-- USER LISTINGS -->
    <section class="mt-6">
      <h3 class="font-semibold text-lg mb-2">
        Tin của bạn
        <a href="create_listing.php" class="ml-2 px-2 py-1 bg-yellow-400 rounded text-black text-sm">
          Tạo mới
        </a>
      </h3>

      <?php if (!$listings): ?>
        <p class="text-sm text-gray-600">Bạn chưa có tin đăng.</p>
      <?php endif; ?>

      <?php foreach ($listings as $l): ?>
        <div class="border p-3 rounded mb-2 flex justify-between">

          <div>
            <strong><?= htmlspecialchars($l['title']) ?></strong>
            <div class="text-sm text-gray-600">
              <?= htmlspecialchars($l['address']) ?>
              • <?= number_format($l['price']) ?> VNĐ  
              • Trạng thái: <strong><?= htmlspecialchars($l['status']) ?></strong>
            </div>
          </div>

          <div class="space-x-3 text-sm">
            <a href="view_listing.php?id=<?= intval($l['id']) ?>" class="underline text-blue-600">
              Xem
            </a>
            <a href="edit_listing.php?id=<?= intval($l['id']) ?>" class="underline">
              Sửa
            </a>
            <a href="delete_listing.php?id=<?= intval($l['id']) ?>" class="underline text-red-600"
               onclick="return confirm('Bạn có chắc muốn xóa?')">
              Xóa
            </a>
          </div>

        </div>
      <?php endforeach; ?>
    </section>

  </div>
</main>

<?php include 'footer.php'; ?>
