<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . "/db.php";

/* ================= HÀM XỬ LÝ ẢNH ================= */
function resolve_chat_image($thumbVal) {
    $thumbVal = trim((string)$thumbVal);
    $default = "/hiihi/assets/default.png";
    if ($thumbVal === '') return $default;
    if (preg_match('#^https?://#i', $thumbVal)) return $thumbVal;

    $paths = [
        "/hiihi/uploads/" . ltrim($thumbVal, '/'),
        "/hiihi/uploads/post_images/" . ltrim($thumbVal, '/'),
        "/hiihi/" . ltrim($thumbVal, '/')
    ];
    foreach ($paths as $p) {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $p)) return $p;
    }
    return $default;
}

/* ================= NHẬN MESSAGE ================= */
$data = json_decode(file_get_contents("php://input"), true);
$raw_message = $data['message'] ?? '';
$message = mb_strtolower(trim($raw_message), 'UTF-8');

if ($message === '') {
    echo json_encode(["reply" => "❌ Bạn chưa nhập nội dung"]);
    exit;
}

/* ================= PHÂN TÍCH GIÁ ================= */
$maxPrice = null;
$minPrice = null;

if (preg_match('/(?:dưới|có|tầm|khoảng|mức)?\s*(\d+(?:\.\d+)?)\s*(?:triệu|tr)?/u', $message, $m)) {
    $maxPrice = (float)$m[1];

    // 👉 Quy ước: dưới X triệu → lấy từ (X-2) đến X
    $minPrice = max(0, $maxPrice - 2);
}

/* ================= PHÂN TÍCH KHU VỰC ================= */
$khu_vuc = null;
$districts = [
    'cầu giấy' => 'Cầu Giấy',
    'đống đa' => 'Đống Đa',
    'ba đình' => 'Ba Đình',
    'thanh xuân' => 'Thanh Xuân',
    'hai bà trưng' => 'Hai Bà Trưng',
    'long biên' => 'Long Biên',
    'nam từ liêm' => 'Nam Từ Liêm',
    'bắc từ liêm' => 'Bắc Từ Liêm'
];
foreach ($districts as $key => $val) {
    if (mb_strpos($message, $key) !== false) {
        $khu_vuc = $val;
        break;
    }
}

/* ================= PHÂN LOẠI CÂU HỎI ================= */
$isPriceQuestion = (mb_strpos($message, 'giá phổ biến') !== false || mb_strpos($message, 'tìm giá') !== false);
$isTypeQuestion  = (mb_strpos($message, 'số lượt khách') !== false || mb_strpos($message, 'quan tâm') !== false || mb_strpos($message, 'loại phòng') !== false);

/* ================= XÁC ĐỊNH ROLE ================= */
$role = $_SESSION['user']['role'] ?? 'guest';
$user_id = $_SESSION['user']['id'] ?? null;

/* ================= LƯU LOG CHAT ================= */
if ($role !== 'guest' && $user_id) {
    $log = $pdo->prepare("INSERT INTO chat_logs (user_id, role, message, khu_vuc, max_price) VALUES (?, ?, ?, ?, ?)");
    $log->execute([$user_id, $role, $raw_message, $khu_vuc, $maxPrice]);
}

/* ================= XỬ LÝ LANDLORD ================= */
if ($role === 'landlord') {
    // Giá phổ biến
    if ($isPriceQuestion) {
        $sql = "SELECT ROUND(price,1) AS price, COUNT(*) AS total FROM posts WHERE status='approved' ";
        $params = [];
        if ($khu_vuc) { 
            $sql .= " AND LOWER(khu_vuc) LIKE :kv "; 
            $params[':kv'] = '%'.strtolower($khu_vuc).'%'; 
        }
        $sql .= " GROUP BY ROUND(price,1) ORDER BY total DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $top = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(["reply" => $top ? "💰 Mức giá phổ biến nhất ".($khu_vuc ? "tại $khu_vuc" : "")." là <b>{$top['price']} triệu</b>." : "😥 Chưa có dữ liệu."]);
        exit;
    }

    // Số lượt khách quan tâm / loại phòng
    if ($isTypeQuestion) {
        preg_match('/phòng trọ|nhà nguyên căn|căn hộ dịch vụ|căn hộ chung cư/', $message, $m);
        $typeFilter = $m[0] ?? null;
        if ($typeFilter) {
            $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM posts WHERE status='approved' AND LOWER(type)=:type");
            $stmt->execute([':type'=>strtolower($typeFilter)]);
            $total = $stmt->fetchColumn();
            echo json_encode(["reply" => "🏠 Số lượt khách quan tâm <b>".ucwords($typeFilter)."</b>: {$total} lượt"]);
        } else {
            $roomTypes = $pdo->query("SELECT LOWER(type) AS room_type, COUNT(*) AS total 
                                      FROM posts 
                                      WHERE status='approved' 
                                      GROUP BY LOWER(type) 
                                      ORDER BY total DESC")->fetchAll(PDO::FETCH_ASSOC);
            $reply = "🏠 Số lượt khách quan tâm theo loại phòng:<br>";
            foreach ($roomTypes as $rt) $reply .= " - ".ucwords($rt['room_type']).": <b>{$rt['total']} lượt</b><br>";
            echo json_encode(["reply"=>$reply]);
        }
        exit;
    }

    // Xu hướng giá / giá dao động
    if ($khu_vuc && (mb_strpos($message, 'xu hướng') !== false || mb_strpos($message, 'dao động') !== false || mb_strpos($message, 'tăng hay giảm') !== false)) {
        $stmt = $pdo->prepare("SELECT MIN(CAST(price AS DECIMAL(10,2))) AS min_price, 
                                      MAX(CAST(price AS DECIMAL(10,2))) AS max_price, 
                                      AVG(CAST(price AS DECIMAL(10,2))) AS avg_price 
                               FROM posts 
                               WHERE status='approved' AND LOWER(khu_vuc) LIKE :kv");
        $stmt->execute([':kv'=> '%'.strtolower($khu_vuc).'%']);
        $stat = $stmt->fetch(PDO::FETCH_ASSOC);
        $reply = ($stat && $stat['avg_price'] !== null)
            ? "📊 Giá phòng tại <b>$khu_vuc</b> dao động từ <b>".number_format($stat['min_price'],1)." triệu</b> đến <b>".number_format($stat['max_price'],1)." triệu</b><br>💰 Giá trung bình: <b>".number_format($stat['avg_price'],1)." triệu</b>"
            : "😥 Chưa có dữ liệu giá tại khu vực này";
        echo json_encode(["reply"=>$reply]);
        exit;
    }
}

/* ================= XỬ LÝ TENANT ================= */
if ($role === 'tenant') {
    // Không được xem giá phổ biến hoặc xu hướng
    if ($isPriceQuestion || (mb_strpos($message, 'xu hướng') !== false || mb_strpos($message, 'dao động') !== false)) {
        echo json_encode(["reply" => "😥 Rất tiếc! Bạn không có quyền xem giá phổ biến hoặc xu hướng giá. Hãy liên hệ chủ phòng."]);
        exit;
    }
}

/* ================= XỬ LÝ TÌM PHÒNG (TENANT & GUEST) ================= */
$sql = "SELECT p.id, p.title, p.price, p.khu_vuc, 
               (SELECT filename FROM post_images WHERE post_id = p.id ORDER BY id ASC LIMIT 1) AS thumbnail 
        FROM posts p 
        WHERE status='approved' AND status_rent=0";

$params = [];
if ($maxPrice !== null && $minPrice !== null) {
    $sql .= " AND CAST(p.price AS DECIMAL(10,2)) BETWEEN :minPrice AND :maxPrice";
    $params[':minPrice'] = $minPrice;
    $params[':maxPrice'] = $maxPrice;
}

if ($khu_vuc) {
    $sql .= " AND LOWER(p.khu_vuc) LIKE :kv";
    $params[':kv'] = '%'.strtolower($khu_vuc).'%';
}
$sql .= " ORDER BY p.id DESC LIMIT 10";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rooms) {
    $txt = "😥 Rất tiếc, hiện tại chưa có phòng nào";
    if ($khu_vuc) $txt .= " tại <b>$khu_vuc</b>";
    if ($maxPrice) $txt .= " dưới <b>$maxPrice triệu</b>";
    echo json_encode(["reply" => $txt . "."]);
    exit;
}

$html = "🔎 <b>Kết quả tìm phòng phù hợp:</b><br><br>";
foreach ($rooms as $r) {
    $img = resolve_chat_image($r['thumbnail'] ?? '');
    $html .= "<div style='border:1px solid #eee; padding:10px; border-radius:10px; margin-bottom:10px; background:#fff;'>
                <img src='{$img}' style='width:100%; height:120px; object-fit:cover; border-radius:8px;'>
                <div style='margin-top:8px;'>
                    <b style='font-size:14px;'>{$r['title']}</b><br>
                    <span style='color:red; font-weight:bold;'>".number_format($r['price'],1)." triệu</span> - <span>{$r['khu_vuc']}</span><br>
                    <a href='/hiihi/post.php?id={$r['id']}' style='display:inline-block; margin-top:5px; color:#007bff; text-decoration:none; font-weight:bold;'>🏠 Xem chi tiết</a>
                </div>
              </div>";
}

echo json_encode(["reply" => $html]);
exit;
?>
