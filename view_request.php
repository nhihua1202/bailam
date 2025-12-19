<?php
require 'db.php';
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: auth.php?mode=login");
    exit;
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("Yêu cầu không hợp lệ.");
}

// Lấy dữ liệu yêu cầu từ bảng rental_requests
$st = $pdo->prepare("
    SELECT rr.*, p.title AS room_title
    FROM rental_requests rr
    LEFT JOIN posts p ON rr.post_id = p.id
    WHERE rr.id = ?
    LIMIT 1
");
$st->execute([$id]);
$req = $st->fetch();

if (!$req) {
    die("Không tìm thấy yêu cầu.");
}

// Hàm xử lý giá trị rỗng
function val($x) {
    return $x ? htmlspecialchars($x) : "---";
}

// Hàm hiển thị Badge trạng thái
function getStatusBadge($status) {
    $status = strtolower($status);
    $colors = [
        'pending'  => ['bg' => '#fff3cd', 'text' => '#856404', 'label' => 'Đang chờ'],
        'approved' => ['bg' => '#d4edda', 'text' => '#155724', 'label' => 'Đã duyệt'],
        'rejected' => ['bg' => '#f8d7da', 'text' => '#721c24', 'label' => 'Từ chối'],
    ];
    $style = $colors[$status] ?? ['bg' => '#eee', 'text' => '#333', 'label' => $status];
    return "<span style='background:{$style['bg']}; color:{$style['text']}; padding:4px 12px; border-radius:20px; font-size:13px; font-weight:bold;'>{$style['label']}</span>";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết yêu cầu | <?= val($req['fullname']) ?></title>
    <style>
        :root {
            --primary: #2563eb;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-sub: #64748b;
            --border: #e2e8f0;
        }

        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: var(--bg);
            color: var(--text-main);
            margin: 0;
            padding: 40px 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 700px;
            margin: auto;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: var(--text-sub);
            font-size: 14px;
            margin-bottom: 20px;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--primary); }

        .card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .card-header {
            background: #fff;
            padding: 30px;
            border-bottom: 1px solid var(--border);
            text-align: center;
        }

        .card-header h2 {
            margin: 0;
            font-size: 22px;
            color: var(--primary);
        }

        .card-body { padding: 30px; }

        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .info-group { margin-bottom: 5px; }

        .label {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-sub);
            font-weight: 600;
            margin-bottom: 4px;
        }

        .value {
            display: block;
            font-size: 16px;
            font-weight: 500;
        }

        .full-width { grid-column: span 2; }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 20px 0;
            grid-column: span 2;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .info-section { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
            .divider { grid-column: span 1; }
        }
    </style>
</head>
<body>

<div class="container">
    <a href="manage_rooms.php" class="back-link">← Quay lại danh sách quản lý</a>

    <div class="card">
        <div class="card-header">
            <h2>Thông tin đơn đăng ký thuê</h2>
            <div style="margin-top: 10px;">
                <?= getStatusBadge($req['status']) ?>
            </div>
        </div>

        <div class="card-body">
            <div class="info-section">
                <div class="info-group full-width" style="background: #f1f5f9; padding: 15px; border-radius: 8px;">
                    <span class="label">🏢 Tên phòng / Bài đăng</span>
                    <span class="value" style="color: var(--primary); font-size: 18px;"><?= val($req['room_title']) ?></span>
                </div>

                <div class="divider"></div>

                <div class="info-group">
                    <span class="label">👤 Họ và tên</span>
                    <span class="value"><?= val($req['fullname']) ?></span>
                </div>

                <div class="info-group">
                    <span class="label">📞 Số điện thoại</span>
                    <span class="value"><?= val($req['phone']) ?></span>
                </div>

                <div class="info-group">
                    <span class="label">📧 Email liên hệ</span>
                    <span class="value"><?= val($req['gmail']) ?></span>
                </div>

                <div class="info-group">
                    <span class="label">🎂 Ngày sinh</span>
                    <span class="value"><?= val($req['birthday']) ?></span>
                </div>

                <div class="info-group">
                    <span class="label">🆔 Số CCCD</span>
                    <span class="value"><?= val($req['cccd']) ?></span>
                </div>

                <div class="info-group">
                    <span class="label">📅 Ngày gửi yêu cầu</span>
                    <span class="value"><?= date("d/m/Y H:i", strtotime($req['created_at'])) ?></span>
                </div>

                <div class="info-group full-width">
                    <span class="label">📍 Địa chỉ cư trú</span>
                    <span class="value"><?= val($req['address']) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>