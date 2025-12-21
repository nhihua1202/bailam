<?php
require 'header.php';
require 'functions.php';
require 'db.php';

// Chỉ admin được quyền xem trang này
if (!isAdmin()) {
    echo '<main class="max-w-4xl mx-auto p-4"><p>Bạn phải là admin để xem trang này.</p></main>';
    exit;
}

// Lấy danh sách yêu cầu thuê phòng
$stmt = $pdo->query("
    SELECT rr.*, u.name AS user_name, p.title AS post_title
    FROM rent_requests rr
    JOIN users u ON rr.user_id = u.id
    JOIN posts p ON rr.post_id = p.id
    ORDER BY rr.created_at DESC
");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="max-w-6xl mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Quản lý yêu cầu thuê phòng</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 bg-white">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Người thuê</th>
                    <th class="border p-2">Phòng</th>
                    <th class="border p-2">Ngày gửi</th>
                    <th class="border p-2">Trạng thái</th>
                    <th class="border p-2">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $r): ?>
                    <tr>
                        <td class="border p-2 text-center"><?= $r['id'] ?></td>
                        <td class="border p-2"><?= htmlspecialchars($r['user_name']) ?></td>
                        <td class="border p-2"><?= htmlspecialchars($r['post_title']) ?></td>
                        <td class="border p-2"><?= $r['created_at'] ?></td>
                        <td class="border p-2 text-center">
                            <?php
                                if ($r['status'] === 'pending') echo '<span class="text-yellow-600 font-semibold">Chờ duyệt</span>';
                                else if ($r['status'] === 'approved') echo '<span class="text-green-600 font-semibold">Đã duyệt</span>';
                                else echo '<span class="text-red-600 font-semibold">Từ chối</span>';
                            ?>
                        </td>
                        <td class="border p-2 text-center">
                            <?php if ($r['status'] === 'pending'): ?>
                                <a href="approve_request.php?id=<?= $r['id'] ?>" class="bg-green-500 text-white px-3 py-1 rounded">Duyệt</a>
                                <a href="reject_request.php?id=<?= $r['id'] ?>" class="bg-red-500 text-white px-3 py-1 rounded ml-2">Từ chối</a>
                            <?php else: ?>
                                <span class="text-gray-500 italic">Không khả dụng</span>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</main>
