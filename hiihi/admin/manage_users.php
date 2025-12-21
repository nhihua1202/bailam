<?php
session_start();
require '../header.php';
require '../db.php';
require '../functions.php';

/* -------------------------
   KIỂM TRA QUYỀN ADMIN
-------------------------- */
if (!isAdmin()) {
    echo "
    <div class='min-h-screen flex items-center justify-center bg-gray-50'>
        <div class='text-center p-8 bg-white rounded-3xl shadow-xl border border-gray-100 max-w-md mx-4'>
            <div class='w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4'>
                <svg class='w-8 h-8 text-red-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 15v2m0 0v2m0-2h2m-2 0H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>
            </div>
            <h2 class='text-xl font-black text-gray-900 mb-2'>Truy cập bị chặn</h2>
            <p class='text-gray-500 text-sm font-medium'>Khu vực này chỉ dành cho quản trị viên.</p>
            <a href='../index.php' class='mt-6 inline-block px-8 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200'>Quay lại trang chủ</a>
        </div>
    </div>";
    exit;
}

/* -------------------------
   XỬ LÝ XÓA USER
-------------------------- */
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $current_id = $_SESSION['user']['id'] ?? 0;

    if ($del_id === $current_id) {
        echo "<script>alert('❌ Không thể tự xóa tài khoản đang đăng nhập!'); window.location='manage_users.php';</script>";
        exit;
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$del_id]);
        header("Location: manage_users.php");
        exit;
    }
}

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>

<div class="min-h-screen bg-[#f8fafc] py-8 px-4 font-sans text-gray-800">
    <main class="max-w-4xl mx-auto">
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                    <span class="p-2 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200 text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'/></svg>
                    </span>
                    Quản lý tài khoản
                </h2>
                <p class="text-gray-400 text-[10px] font-black mt-1.5 uppercase tracking-[0.2em]">Hệ thống có <?= count($users) ?> thành viên</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">ID</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Thành viên</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Email</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Vai trò</th>
                            <th class="px-5 py-4 text-right text-[10px] font-black uppercase tracking-widest text-gray-400">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach ($users as $u): 
                            $isAdmin = $u['is_admin'] == 1;
                            $roleLabel = $isAdmin ? 'Admin' : ($u['role'] == 'landlord' ? 'Chủ nhà' : 'Người thuê');
                            $roleClass = $isAdmin ? 'bg-indigo-50 text-indigo-600 border-indigo-100' : ($u['role'] == 'landlord' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-gray-50 text-gray-500 border-gray-100');
                        ?>
                        <tr class="hover:bg-indigo-50/20 transition-colors group">
                            <td class="px-5 py-3.5 font-bold text-gray-300 text-xs">#<?= $u['id'] ?></td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-[11px] font-black shadow-sm">
                                        <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                    </div>
                                    <span class="font-bold text-gray-700 text-sm group-hover:text-indigo-600 transition-colors tracking-tight"><?= esc($u['name']) ?></span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500 font-medium lowercase italic"><?= esc($u['email']) ?></td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black border uppercase tracking-wider <?= $roleClass ?>">
                                    <?= $roleLabel ?>
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <?php if ($u['id'] != ($_SESSION['user']['id'] ?? 0)): ?>
                                    <a href="?delete=<?= $u['id'] ?>"
                                       onclick="return confirm('⚠️ Chắc chắn muốn XÓA tài khoản <?= esc($u['name']) ?>?');"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-500 text-[9px] font-black rounded-lg hover:bg-rose-500 hover:text-white transition-all border border-rose-100 uppercase tracking-tighter active:scale-95 shadow-sm shadow-rose-50">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Xóa tài khoản
                                    </a>
                                <?php else: ?>
                                    <span class="text-[9px] font-bold text-gray-300 uppercase italic tracking-widest px-2">Bạn đang online</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <p class="text-center text-[10px] text-gray-400 mt-8 font-medium uppercase tracking-[0.3em]">Hanoi Rental Admin Dashboard</p>

    </main>
</div>

</body>
</html>