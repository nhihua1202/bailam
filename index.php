<?php
require 'header.php';
require 'functions.php';
require 'db.php';
require 'includes/districts.php';

/* ================= BIẾN ================= */
$type        = $_GET['type'] ?? '';
$khu_vuc     = $_GET['khu_vuc'] ?? '';
$price_range = $_GET['price_range'] ?? 'all';
$q           = trim($_GET['q'] ?? '');

function sel($v,$c){ return $v===$c?'selected':''; }
function ck($v,$c){ return $v===$c?'checked':''; }

/* ================= PHÂN TÍCH Ô TÌM KIẾM ================= */
$q_price_min = null;
$q_price_max = null;
$q_text      = '';

$norm = mb_strtolower($q,'UTF-8');

/* chuẩn hoá đơn vị */
$norm = str_replace(
  ['triệu','tr','m','đ','vnd',','],
  '',
  $norm
);
$norm = trim($norm);

/* ===== dưới X ===== */
if (preg_match('/dưới\s*(\d+(\.\d+)?)/u',$norm,$m)) {
  $q_price_max = (float)$m[1];
}

/* ===== trên X ===== */
elseif (preg_match('/trên\s*(\d+(\.\d+)?)/u',$norm,$m)) {
  $q_price_min = (float)$m[1];
}

/* ===== khoảng 3-5 ===== */
elseif (preg_match('/(\d+(\.\d+)?)\s*-\s*(\d+(\.\d+)?)/u',$norm,$m)) {
  $q_price_min = (float)$m[1];
  $q_price_max = (float)$m[3];
}

/* ===== > < ===== */
elseif (preg_match('/(>=|>|<=|<)\s*(\d+(\.\d+)?)/u',$norm,$m)) {
  if ($m[1]=='>' || $m[1]=='>=') $q_price_min=(float)$m[2];
  if ($m[1]=='<' || $m[1]=='<=') $q_price_max=(float)$m[2];
}

/* ===== chỉ 1 số: 3tr / 5m ===== */
elseif (preg_match('/\b(\d+(\.\d+)?)\b/u',$norm,$m)) {
  $v=(float)$m[1];
  $q_price_min=$v-0.5;
  $q_price_max=$v+0.5;
}

/* ===== text còn lại ===== */
$q_text = trim(
  preg_replace(
    '/(dưới|trên|>=|<=|>|<)?\s*\d+(\.\d+)?(\s*-\s*\d+(\.\d+)?)?/u',
    '',
    mb_strtolower($q,'UTF-8')
  )
);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>HaNoiRental</title>
</head>

<body class="bg-gray-100">
<main class="max-w-7xl mx-auto p-4">

<h1 class="text-2xl font-semibold text-center mb-6">HaNoiRental</h1>

<div class="flex flex-col md:flex-row gap-6">

<!-- ================= BỘ LỌC ================= -->
<aside class="w-full md:w-72 bg-white p-4 rounded shadow">
<form method="get">
<input type="hidden" name="q" value="<?=esc($q)?>">

<label>Loại phòng</label>
<select name="type" class="w-full p-2 border rounded mb-3">
<option value="">Tất cả</option>
<option value="Phòng trọ" <?=sel('Phòng trọ',$type)?>>Phòng trọ</option>
<option value="Nhà nguyên căn" <?=sel('Nhà nguyên căn',$type)?>>Nhà nguyên căn</option>
<option value="Căn hộ chung cư" <?=sel('Căn hộ chung cư',$type)?>>Căn hộ chung cư</option>
<option value="Căn hộ mini" <?=sel('Căn hộ mini',$type)?>>Căn hộ mini</option>
</select>

<label>Khu vực</label>
<select name="khu_vuc" class="w-full p-2 border rounded mb-3">
<option value="">Tất cả</option>
<?php foreach($districts_hanoi as $g=>$ds): ?>
<optgroup label="<?=$g?>">
<?php foreach($ds as $d): ?>
<option value="<?=$d?>" <?=sel($d,$khu_vuc)?>><?=$d?></option>
<?php endforeach ?>
</optgroup>
<?php endforeach ?>
</select>

<div class="text-sm mb-3">
<label><input type="radio" name="price_range" value="all" <?=ck('all',$price_range)?>> Tất cả</label><br>
<label><input type="radio" name="price_range" value="1-3" <?=ck('1-3',$price_range)?>> 1 – 3 triệu</label><br>
<label><input type="radio" name="price_range" value="3-5" <?=ck('3-5',$price_range)?>> 3 – 5 triệu</label><br>
<label><input type="radio" name="price_range" value="5-15" <?=ck('5-15',$price_range)?>> 5 – 15 triệu</label><br>
<label><input type="radio" name="price_range" value="15+" <?=ck('15+',$price_range)?>> Trên 15 triệu</label>
</div>

<button class="w-full bg-red-600 text-white py-2 rounded">Lọc</button>
</form>
</aside>

<!-- ================= DANH SÁCH ================= -->
<section class="flex-1">

<form method="get" class="mb-4">
<input type="hidden" name="type" value="<?=esc($type)?>">
<input type="hidden" name="khu_vuc" value="<?=esc($khu_vuc)?>">
<input type="hidden" name="price_range" value="<?=esc($price_range)?>">
<input type="text" name="q" value="<?=esc($q)?>" class="w-full p-2 border rounded">
</form>

<?php
$sql="SELECT p.*,
 (SELECT filename FROM post_images WHERE post_id=p.id LIMIT 1) thumbnail
FROM posts p
WHERE p.status='approved' AND p.status_rent=0";

$params=[];
if($type){$sql.=" AND p.type=:t";$params[':t']=$type;}
if($khu_vuc){$sql.=" AND p.khu_vuc=:kv";$params[':kv']=$khu_vuc;}
if($q_text){
  $sql.=" AND (p.title LIKE :q OR p.description LIKE :q OR p.khu_vuc LIKE :q)";
  $params[':q']="%$q_text%";
}

$st=$pdo->prepare($sql);
$st->execute($params);

$rooms=[];
foreach($st as $p){
  $price = $p['price']>1000 ? $p['price']/1000000 : (float)$p['price'];

  if($price_range==='1-3' && ($price<1||$price>3)) continue;
  if($price_range==='3-5' && ($price<3||$price>5)) continue;
  if($price_range==='5-15' && ($price<5||$price>15)) continue;
  if($price_range==='15+' && $price<=15) continue;

  if($q_price_min!==null && $price<$q_price_min) continue;
  if($q_price_max!==null && $price>$q_price_max) continue;

  $rooms[]=$p;
}

if(!$rooms){
  echo '<p class="text-center text-gray-500">Không có phòng phù hợp.</p>';
}else{
  echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">';
  foreach($rooms as $p){
    $price = $p['price']>1000 ? $p['price']/1000000 : $p['price'];
    $m=rtrim(rtrim(number_format($price,1),'0'),'.');
    $thumb=$p['thumbnail']?'uploads/'.$p['thumbnail']:'assets/no-image.png';

    echo "
<a href='post.php?id={$p['id']}' class='block bg-white p-4 rounded shadow'>
<img src='$thumb' class='h-48 w-full object-cover rounded'>
<h3 class='mt-2 font-semibold'>{$p['title']}</h3>
<p class='text-sm text-gray-600'>Khu vực: {$p['khu_vuc']}</p>
<div class='flex justify-between mt-2'>
<span class='text-blue-600 text-sm'>Xem chi tiết</span>
<span class='text-green-700 font-medium'>$m triệu</span>
</div>
</a>";
  }
  echo '</div>';
}
?>

</section>
</div>
</main>
</body>
</html>
