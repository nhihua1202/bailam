<?php
session_start();
require '../header.php';
require '../db.php';
require '../functions.php';

/* -------------------------
   CHỈ ADMIN ĐƯỢC VÀO
-------------------------- */
if (!isAdmin()) {
    echo "<main class='p-4 max-w-xl mx-auto'>
            <p class='text-red-600 font-semibold'>
                Bạn không có quyền truy cập trang này.
            </p>
          </main>";
    exit;
}

/* -------------------------
   XỬ LÝ XÓA USER
   (XÓA THẲNG DB)
-------------------------- */
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $current_id = $_SESSION['user']['id'] ?? 0;

    // Không cho admin tự xóa mình
    if ($del_id === $current_id) {
        echo "<script>alert('❌ Không thể tự xóa tài khoản đang đăng nhập!');</script>";
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$del_id]);

        // Load lại chính trang này → KHÔNG 404
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

/* -------------------------
   LẤY DANH SÁCH USER
-------------------------- */
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>

<main class="max-w-4xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Quản lý tài khoản</h2>

    <table class="w-full border text-sm">
        <tr class="bg-gray-200 text-left">
            <th class="p-2 border">ID</th>
            <th class="p-2 border">Tên</th>
            <th class="p-2 border">Email</th>
            <th class="p-2 border">Vai trò</th>
            <th class="p-2 border">Hành động</th>
        </tr>

        <?php foreach ($users as $u): ?>
        <tr class="hover:bg-gray-50">
            <td class="p-2 border"><?= $u['id'] ?></td>
            <td class="p-2 border"><?= esc($u['name']) ?></td>
            <td class="p-2 border"><?= esc($u['email']) ?></td>
            <td class="p-2 border">
                <?= $u['is_admin'] ? 'Admin' : esc($u['role']) ?>
            </td>
            <td class="p-2 border">
                <?php if ($u['id'] != ($_SESSION['user']['id'] ?? 0)): ?>
                    <a href="?delete=<?= $u['id'] ?>"
                       onclick="return confirm('⚠️ Bạn có chắc chắn muốn XÓA tài khoản này không?');"
                       class="text-red-600 hover:underline font-semibold">
                        Xóa
                    </a>
                <?php else: ?>
                    <span class="text-gray-400">—</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>

</body>
</html>
