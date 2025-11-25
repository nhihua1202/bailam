<?php
require '../header.php';
require '../db.php';
require '../functions.php';

if (!isAdmin()) {
    echo "<main class='p-4 max-w-xl mx-auto'>
            <p class='text-red-600 font-semibold'>Bạn không có quyền truy cập trang này.</p>
          </main>";
    exit;
}

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>

<main class="max-w-4xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Quản lý tài khoản</h2>

    <table class="w-full border">
        <tr class="bg-gray-200">
            <th class="p-2 border">ID</th>
            <th class="p-2 border">Tên</th>
            <th class="p-2 border">Email</th>
            <th class="p-2 border">Vai trò</th>
        </tr>

        <?php foreach($users as $u): ?>
        <tr>
            <td class="p-2 border"><?= $u['id'] ?></td>
            <td class="p-2 border"><?= esc($u['name']) ?></td>
            <td class="p-2 border"><?= esc($u['email']) ?></td>
            <td class="p-2 border"><?= esc($u['role']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>

</body>
</html>
