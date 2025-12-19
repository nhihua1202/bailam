<?php
require 'header.php';
require 'functions.php';
require 'db.php';
require 'includes/districts.php';

/* ================= BIẾN TÌM KIẾM ================= */
$type        = $_GET['type'] ?? '';
$khu_vuc     = $_GET['khu_vuc'] ?? '';
$price_range = $_GET['price_range'] ?? 'all';
$q           = trim($_GET['q'] ?? '');

function sel($v,$c){ return $v===$c?'selected':''; }

/* ================= ẢNH BANNER ================= */
$bannerImages = [
  'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688',
  'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2',
  'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267'
];

/* ================= XỬ LÝ LOGIC TÌM KIẾM ================= */
$q_price_min = null;
$q_price_max = null;
$norm = mb_strtolower($q,'UTF-8');
$norm = str_replace(['triệu','tr','m','đ','vnd',','],'',$norm);
$norm = trim($norm);

if (preg_match('/dưới\s*(\d+(\.\d+)?)/u',$norm,$m)) $q_price_max=(float)$m[1];
elseif (preg_match('/trên\s*(\d+(\.\d+)?)/u',$norm,$m)) $q_price_min=(float)$m[1];
elseif (preg_match('/(\d+(\.\d+)?)\s*-\s*(\d+(\.\d+)?)/u',$norm,$m)) {
  $q_price_min=(float)$m[1]; $q_price_max=(float)$m[3];
}

$q_text = trim(preg_replace('/(dưới|trên|>=|<=|>|<)?\s*\d+(\.\d+)?(\s*-\s*\d+(\.\d+)?)?/u','',mb_strtolower($q,'UTF-8')));
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Tùy chỉnh để ẩn mũi tên mặc định của select để trông sạch sẽ hơn */
        select { appearance: none; -webkit-appearance: none; }
    </style>
</head>
<body class="bg-gray-50">

<div class="relative h-[420px] overflow-hidden">
    <div id="slider" class="absolute inset-0">
        <?php foreach($bannerImages as $img): ?>
            <img src="<?=$img?>" class="slide absolute inset-0 w-full h-full object-cover scale-105 opacity-0 transition-all duration-1000">
        <?php endforeach ?>
    </div>
    <div class="absolute inset-0 bg-black/30"></div>
    
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white pb-10">
        <h1 class="text-4xl font-bold drop-shadow-lg mb-2">Hanoi Rental</h1>
        <p class="text-lg opacity-90 drop-shadow-md">Tìm kiếm phòng trọ, căn hộ ưng ý tại Hà Nội</p>
    </div>
</div>

<div class="relative z-30 -mt-8 px-4">
    <form method="get" class="mx-auto w-[95%] max-w-5xl">
        
        <div class="bg-white/95 backdrop-blur-md border border-gray-100 rounded-full shadow-[0_15px_45px_rgba(0,0,0,0.1)] p-1.5 flex items-center transition-all hover:shadow-2xl">
            
            <div class="flex-1 flex items-center pl-6">
                <svg class="w-4 h-4 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="q" value="<?=esc($q)?>" placeholder="Nhập khu vực, tên đường..." 
                       class="w-full bg-transparent border-none focus:ring-0 text-sm py-2 text-gray-700 outline-none">
            </div>

            <div class="flex items-center">
                <div class="hidden md:flex items-center px-4 border-l border-gray-100">
                    <select name="type" class="bg-transparent border-none text-[13px] font-semibold text-gray-600 focus:ring-0 cursor-pointer hover:text-red-600 transition-colors py-1">
                        <option value="">🏠 Loại nhà</option>
                        <option value="Phòng trọ" <?=sel('Phòng trọ',$type)?>>Phòng trọ</option>
                        <option value="Nhà nguyên căn" <?=sel('Nhà nguyên căn',$type)?>>Nhà nguyên căn</option>
                        <option value="Căn hộ mini" <?=sel('Căn hộ mini',$type)?>>Căn hộ mini</option>
                    </select>
                </div>

                <div class="hidden lg:flex items-center px-4 border-l border-gray-100">
                    <select name="khu_vuc" class="bg-transparent border-none text-[13px] font-semibold text-gray-600 focus:ring-0 cursor-pointer hover:text-red-600 transition-colors py-1">
                        <option value="">📍 Khu vực</option>
                        <?php foreach($districts_hanoi as $g=>$ds): ?>
                            <?php foreach($ds as $d): ?>
                                <option value="<?=$d?>" <?=sel($d,$khu_vuc)?>><?=$d?></option>
                            <?php endforeach ?>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="hidden sm:flex items-center px-4 border-l border-gray-100">
                    <select name="price_range" class="bg-transparent border-none text-[13px] font-semibold text-gray-600 focus:ring-0 cursor-pointer hover:text-red-600 transition-colors py-1">
                        <option value="all">💰 Mức giá</option>
                        <option value="1-3" <?=sel('1-3',$price_range)?>>1-3 tr</option>
                        <option value="3-5" <?=sel('3-5',$price_range)?>>3-5 tr</option>
                        <option value="5-10" <?=sel('5-10',$price_range)?>>5-10 tr</option>
                        <option value="15+" <?=sel('15+',$price_range)?>>Trên 15tr</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white h-11 px-7 rounded-full transition-all active:scale-95 flex items-center gap-2 shadow-lg shadow-red-200 ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span class="hidden md:block text-sm font-bold">Tìm kiếm</span>
            </button>
        </div>

        <div class="flex justify-center mt-4">
            <a href="index.php" class="flex items-center gap-2 px-4 py-1.5 bg-white/80 hover:bg-white rounded-full border border-gray-100 shadow-sm transition-all group">
                <svg class="w-3.5 h-3.5 text-gray-400 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span class="text-[11px] font-bold text-gray-500 group-hover:text-red-500 uppercase tracking-widest">Làm mới bộ lọc</span>
            </a>
        </div>
    </form>
</div>

<main class="max-w-7xl mx-auto p-8">
<?php
$sql="SELECT p.*, (SELECT filename FROM post_images WHERE post_id=p.id LIMIT 1) thumbnail 
      FROM posts p WHERE p.status='approved' AND p.status_rent=0";
$params=[];
if($type){$sql.=" AND p.type=:t";$params[':t']=$type;}
if($khu_vuc){$sql.=" AND p.khu_vuc=:kv";$params[':kv']=$khu_vuc;}
if($q_text){ $sql.=" AND (p.title LIKE :q OR p.khu_vuc LIKE :q)"; $params[':q']="%$q_text%"; }

$st=$pdo->prepare($sql);
$st->execute($params);

$rooms=[];
foreach($st as $p){
    $price = $p['price'] > 1000 ? $p['price'] / 1000000 : (float)$p['price'];
    if ($price_range === '1-3' && ($price < 1 || $price > 3)) continue;
    if ($price_range === '3-5' && ($price < 3 || $price > 5)) continue;
    if ($price_range === '5-10' && ($price < 5 || $price > 10)) continue;
    if ($price_range === '15+' && $price <= 15) continue;
    $rooms[]=$p;
}

if(!$rooms){
    echo '<div class="text-center py-20"><p class="text-gray-400">Không tìm thấy phòng nào phù hợp...</p></div>';
} else {
    echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">';
    foreach($rooms as $p){
        $price = $p['price']>1000 ? $p['price']/1000000 : $p['price'];
        $m=rtrim(rtrim(number_format($price,1),'0'),'.');
        $thumb=$p['thumbnail']?'uploads/'.$p['thumbnail']:'assets/no-image.png';
        echo "
        <a href='post.php?id={$p['id']}' class='group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all overflow-hidden border border-gray-100'>
            <div class='relative overflow-hidden'>
                <img src='$thumb' class='h-56 w-full object-cover group-hover:scale-110 transition-duration-500'>
                <div class='absolute top-3 right-3 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-red-500'>$m triệu/tháng</div>
            </div>
            <div class='p-5'>
                <h3 class='font-bold text-gray-800 text-lg mb-1 truncate'>{$p['title']}</h3>
                <p class='text-sm text-gray-500 flex items-center gap-1'>
                    <svg class='w-3.5 h-3.5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path d='M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'/><path d='M15 11a3 3 0 11-6 0 3 3 0 016 0z'/></svg>
                    {$p['khu_vuc']}
                </p>
                <div class='mt-4 pt-4 border-t border-gray-50 flex justify-between items-center'>
                    <span class='text-xs font-medium text-gray-400'>Cập nhật vừa xong</span>
                    <span class='text-red-500 text-sm font-bold group-hover:underline'>Xem chi tiết →</span>
                </div>
            </div>
        </a>";
    }
    echo '</div>';
}
?>
</main>

<script>
    // SLIDER LOGIC
    const slides=document.querySelectorAll('.slide');
    let i=0;
    function show(n){
        slides.forEach(s=>s.style.opacity=0);
        slides[n].style.opacity=1;
    }
    show(0);
    setInterval(()=>{i=(i+1)%slides.length;show(i)},5000);
</script>

</body>
</html>