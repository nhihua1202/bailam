<?php
require __DIR__.'/includes/header.php';

$id = $_GET['id'] ?? null;
$file = __DIR__ . '/data/listings.json';

$list = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$item = null;
$idx = null;

/* ===== TÌM TIN THEO ID ===== */
foreach ($list as $k => $l) {
    if ($l['id'] == $id) {
        $item = $l;
        $idx = $k;
        break;
    }
}

/* ===== LƯU CẬP NHẬT ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $item && $idx !== null) {

    $list[$idx]['title'] = $_POST['title'] ?? '';
    $list[$idx]['address'] = $_POST['address'] ?? '';
    $list[$idx]['price'] = $_POST['price'] ?? '';

    file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Redirect đúng file quản lý tin
    header('Location: /manage_listings.php');
    exit;
}
?>

<div class="card">
    <h2>Sửa tin</h2>

    <?php if (!$item): ?>
        <div class="small text-red-600">Không tìm thấy tin.</div>

    <?php else: ?>

    <form method="post">

        <label class="small">Tiêu đề</label>
        <input class="form-input" name="title" value="<?= htmlspecialchars($item['title']) ?>">

        <label class="small">Địa chỉ</label>
        <input class="form-input" name="address" value="<?= htmlspecialchars($item['address']) ?>">

        <label class="small">Giá (triệu)</label>
        <input class="form-input" name="price" value="<?= htmlspecialchars($item['price']) ?>">

        <div style="text-align:right; margin-top:12px;">
            <button class="btn" type="submit">Lưu thay đổi</button>
        </div>

    </form>

    <?php endif; ?>
</div>

<?php require __DIR__.'/includes/footer.php'; ?>
