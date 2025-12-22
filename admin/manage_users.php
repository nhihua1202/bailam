<?php
session_start();
require '../db.php';
require '../functions.php';

/* -------------------------
   KIỂM TRA QUYỀN ADMIN
-------------------------- */
if (!isAdmin()) {
    require '../header.php';
    echo "
    <div class='min-h-screen flex items-center justify-center bg-gray-50'>
        <div class='text-center p-8 bg-white rounded-3xl shadow-xl border border-gray-100 max-w-md mx-4'>
            <div class='w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4'>
                <svg class='w-8 h-8 text-red-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                        d='M12 15v2m0 0v2m0-2h2m-2 0H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z'/>
                </svg>
            </div>
            <h2 class='text-xl font-black text-gray-900 mb-2'>Truy cập bị chặn</h2>
            <p class='text-gray-500 text-sm font-medium'>Khu vực này chỉ dành cho quản trị viên.</p>
            <a href='../index.php'
               class='mt-6 inline-block px-8 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700'>
               Quay lại trang chủ
            </a>
        </div>
    </div>";
    exit;
}

/* -------------------------
   XỬ LÝ XÓA USER (TRƯỚC HEADER)
-------------------------- */
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $current_id = $_SESSION['user']['id'] ?? 0;

    if ($del_id === $current_id) {
        $_SESSION['flash_error'] = '❌ Không thể tự xóa tài khoản đang đăng nhập!';
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$del_id]);
        $_SESSION['flash_success'] = '✅ Đã xóa tài khoản thành công';
    }

    header("Location: manage_users.php");
    exit;
}

/* -------------------------
   LẤY DANH SÁCH USER
-------------------------- */
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();

/* -------------------------
   GIAO DIỆN
-------------------------- */
require '../header.php';
?>

<div class="min-h-screen bg-[#f8fafc] py-8 px-4 font-sans text-gray-800">
    <main class="max-w-4xl mx-auto">

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="mb-4 p-4 bg-red-50 text-red-600 rounded-xl font-bold">
                <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="mb-4 p-4 bg-emerald-50 text-emerald-600 rounded-xl font-bold">
                <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
            </div>
        <?php endif; ?>

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                    <span class="p-2 bg-indigo-600 rounded-xl text-white">
                        👤
                    </span>
                    Quản lý tài khoản
                </h2>
                <p class="text-gray-400 text-[10px] font-black mt-1.5 uppercase tracking-[0.2em]">
                    Hệ thống có <?= count($users) ?> thành viên
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="px-5 py-3 text-xs text-gray-400">ID</th>
                        <th class="px-5 py-3 text-xs text-gray-400">Tên</th>
                        <th class="px-5 py-3 text-xs text-gray-400">Email</th>
                        <th class="px-5 py-3 text-xs text-gray-400">Vai trò</th>
                        <th class="px-5 py-3 text-right text-xs text-gray-400">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr class="border-t hover:bg-indigo-50/30">
                            <td class="px-5 py-3 text-xs text-gray-400">#<?= $u['id'] ?></td>
                            <td class="px-5 py-3 font-bold"><?= esc($u['name']) ?></td>
                            <td class="px-5 py-3 text-xs text-gray-500"><?= esc($u['email']) ?></td>
                            <td class="px-5 py-3 text-xs">
                                <?= $u['is_admin'] ? 'Admin' : ($u['role'] === 'landlord' ? 'Chủ nhà' : 'Người thuê') ?>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <?php if ($u['id'] != ($_SESSION['user']['id'] ?? 0)): ?>
                                    <a href="?delete=<?= $u['id'] ?>"
                                       onclick="return confirm('Xóa tài khoản <?= esc($u['name']) ?>?')"
                                       class="text-rose-600 font-bold text-xs">
                                        Xóa
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-300 text-xs italic">Đang đăng nhập</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p class="text-center text-[10px] text-gray-400 mt-8 uppercase tracking-[0.3em]">
            Hanoi Rental Admin Dashboard
        </p>
    </main>
</div>

</body>
</html>
