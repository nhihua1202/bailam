<?php
require 'header.php';
require 'functions.php';
require 'db.php';
require 'includes/districts.php';

/* ================= GIỮ NGUYÊN BIẾN TÌM KIẾM CỦA BẠN ================= */
$type        = $_GET['type'] ?? '';
$khu_vuc     = $_GET['khu_vuc'] ?? '';
$price_range = $_GET['price_range'] ?? 'all';
$q           = trim($_GET['q'] ?? '');

function sel($v,$c){ return $v===$c?'selected':''; }

/* ================= ẢNH BANNER CHÍNH ================= */
$bannerImages = [
  'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688',
  'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2',
  'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267'
];

/* ================= ẢNH QUẢNG CÁO 2 BÊN ================= */
$adsLeft = [
    'https://i.pinimg.com/736x/73/c0/f8/73c0f81bc33f47287581c64871affacc.jpg'
];

$adsRight = [
    'https://i.pinimg.com/736x/83/6e/01/836e015e8c784798353efc27156314c2.jpg'
];

/* ================= LOGIC XỬ LÝ TÌM KIẾM ================= */
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
.line-clamp-2{
  display:-webkit-box;
  -webkit-line-clamp:2;
  -webkit-box-orient:vertical;
  overflow:hidden;
}
</style>
    <style>
        /* MÀU BE ĐẬM HƠN ĐỂ NHÌN RÕ RỆT */
        body { 
            background-color: #F2E8DA !important; 
        }

        select { appearance: none; -webkit-appearance: none; }
        
        .page-wrapper {
            position: relative;
            max-width: 1280px;
            margin: 0 auto;
        }

        /* Banner quảng cáo */
        .side-banner {
            position: absolute;
            top: 20px;
            width: 160px;
            z-index: 10;
            display: none;
        }
        @media (min-width: 1600px) { .side-banner { display: block; } }
        .banner-left { right: 100%; margin-right: 40px; }
        .banner-right { left: 100%; margin-left: 40px; }

        .sticky-ads {
            position: sticky;
            top: 20px;
            height: 600px;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .ad-img {
            position: absolute; inset: 0; width: 100%; height: 100%;
            object-fit: cover; opacity: 0; transition: opacity 1s;
        }
        .ad-img.active { opacity: 1; }
    </style>
</head>
<body class="bg-[#F2E8DA]">

<div class="pb-16">
    <div class="relative h-[420px] overflow-hidden">
        <div id="slider" class="absolute inset-0">
            <?php foreach($bannerImages as $img): ?>
                <img src="<?=$img?>" class="slide absolute inset-0 w-full h-full object-cover scale-105 opacity-0 transition-all duration-1000">
            <?php endforeach ?>
        </div>
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white pb-10">
            <h1 class="text-4xl font-bold drop-shadow-lg mb-2 uppercase tracking-wide">Hanoi Rental</h1>
            <p class="text-lg opacity-90 drop-shadow-md">Tìm kiếm phòng trọ, căn hộ ưng ý tại Hà Nội</p>
        </div>
    </div>

    <div class="relative z-30 -mt-8 px-4">
        <form method="get" class="mx-auto w-[95%] max-w-5xl">
            <div class="bg-white border border-orange-200 rounded-full shadow-[0_10px_40px_rgba(0,0,0,0.15)] p-1.5 flex items-center transition-all hover:shadow-2xl">
                <div class="flex-1 flex items-center pl-6">
                    <svg class="w-4 h-4 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" name="q" value="<?=htmlspecialchars($q)?>" placeholder="Nhập khu vực, tên đường..." class="w-full bg-transparent border-none focus:ring-0 text-sm py-2 text-gray-700 outline-none">
                </div>

                <div class="flex items-center">
                    <div class="hidden md:flex items-center px-4 border-l border-gray-100">
                        <select name="type" class="bg-transparent border-none text-[13px] font-bold text-gray-600 focus:ring-0 cursor-pointer py-1">
                            <option value="">🏠 Loại nhà</option>
                            <option value="Phòng trọ" <?=sel('Phòng trọ',$type)?>>Phòng trọ</option>
                            <option value="Nhà nguyên căn" <?=sel('Nhà nguyên căn',$type)?>>Nhà nguyên căn</option>
                            <option value="Căn hộ mini" <?=sel('Căn hộ mini',$type)?>>Căn hộ mini</option>
                        </select>
                    </div>
                    <div class="hidden lg:flex items-center px-4 border-l border-gray-100">
                        <select name="khu_vuc" class="bg-transparent border-none text-[13px] font-bold text-gray-600 focus:ring-0 cursor-pointer py-1">
                            <option value="">📍 Khu vực</option>
                            <?php foreach($districts_hanoi as $g=>$ds): foreach($ds as $d): ?>
                                <option value="<?=$d?>" <?=sel($d,$khu_vuc)?>><?=$d?></option>
                            <?php endforeach; endforeach; ?>
                        </select>
                    </div>
                    <div class="hidden sm:flex items-center px-4 border-l border-gray-100">
                        <select name="price_range" class="bg-transparent border-none text-[13px] font-bold text-gray-600 focus:ring-0 cursor-pointer py-1">
                            <option value="all">💰 Mức giá</option>
                            <option value="1-3" <?=sel('1-3',$price_range)?>>1-3 tr</option>
                            <option value="3-5" <?=sel('3-5',$price_range)?>>3-5 tr</option>
                            <option value="5-10" <?=sel('5-10',$price_range)?>>5-10 tr</option>
                            <option value="15+" <?=sel('15+',$price_range)?>>Trên 15tr</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white h-11 px-8 rounded-full font-black ml-2 transition-all shadow-md uppercase text-xs tracking-widest">
                    Tìm kiếm
                </button>
            </div>
            
            <div class="flex justify-center mt-4">
                <a href="index.php" class="flex items-center gap-2 px-5 py-2 bg-white/90 rounded-full border border-orange-100 shadow-sm text-[10px] font-black text-gray-500 uppercase tracking-widest hover:text-red-600 transition-all hover:bg-white">
                    Làm mới bộ lọc
                </a>
            </div>
        </form>
    </div>
</div>

<div class="page-wrapper mt-4">
    <div class="side-banner banner-left">
        <div class="sticky-ads" id="ad-l-container">
            <?php foreach($adsLeft as $idx => $url): ?>
                <img src="<?=$url?>" class="ad-img <?=$idx===0?'active':''?>">
            <?php endforeach; ?>
        </div>
    </div>
    <div class="side-banner banner-right">
        <div class="sticky-ads" id="ad-r-container">
            <?php foreach($adsRight as $idx => $url): ?>
                <img src="<?=$url?>" class="ad-img <?=$idx===0?'active':''?>">
            <?php endforeach; ?>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-6 pt-2 pb-20">

<?php
$sql="SELECT p.*, 
     (SELECT filename FROM post_images WHERE post_id=p.id LIMIT 1) thumbnail
     FROM posts p 
     WHERE p.status='approved' AND p.status_rent=0";

$params=[];
if($type){$sql.=" AND p.type=:t";$params[':t']=$type;}
if($khu_vuc){$sql.=" AND p.khu_vuc=:kv";$params[':kv']=$khu_vuc;}
if($q_text){$sql.=" AND (p.title LIKE :q OR p.khu_vuc LIKE :q)";$params[':q']="%$q_text%";}

$st=$pdo->prepare($sql);
$st->execute($params);

$rooms=[];
foreach($st as $p){
    $price=$p['price']>1000?$p['price']/1000000:(float)$p['price'];
    if ($price_range==='1-3' && ($price<1||$price>3)) continue;
    if ($price_range==='3-5' && ($price<3||$price>5)) continue;
    if ($price_range==='5-10' && ($price<5||$price>10)) continue;
    if ($price_range==='15+' && $price<=15) continue;
    $rooms[]=$p;
}
?>

<div class="grid grid-cols-12 gap-6">

    <!-- ================== DANH SÁCH PHÒNG ================== -->
    <div class="col-span-12 lg:col-span-8 space-y-6">

    <?php
    if(!$rooms){
        echo "<div class='bg-white p-12 rounded-xl text-center text-gray-500 font-bold'>
              Không tìm thấy phòng phù hợp
              </div>";
    } else {
        foreach($rooms as $p){
            $price=$p['price']>1000?$p['price']/1000000:$p['price'];
            $m=rtrim(rtrim(number_format($price,1),'0'),'.');
            $thumb=$p['thumbnail']?'uploads/'.$p['thumbnail']:'assets/no-image.png';

            echo "
            <a href='post.php?id={$p['id']}'
               class='flex bg-white rounded-xl shadow hover:shadow-xl transition border overflow-hidden'>

                <!-- ẢNH -->
                <div class='w-[260px] h-[190px] relative flex-shrink-0'>
                    <img src='{$thumb}' class='w-full h-full object-cover'>
                    <span class='absolute top-3 left-3 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded'>
                        {$m} triệu/tháng
                    </span>
                </div>

                <!-- NỘI DUNG -->
                <div class='flex-1 p-4 flex flex-col justify-between'>
                    <div>
                        <h3 class='text-red-600 font-bold uppercase text-sm mb-1 hover:underline line-clamp-2'>
                            {$p['title']}
                        </h3>

                        <div class='text-yellow-400 text-xs mb-2'>★★★★★</div>

                        <p class='text-sm text-gray-700 mb-2 line-clamp-2'>
                            {$p['description']}
                        </p>

                        <div class='flex flex-wrap gap-4 text-sm text-gray-600'>
                            <span>📍 {$p['khu_vuc']}</span>
                            <span>🏠 {$p['type']}</span>
                        </div>
                    </div>

                    <div class='flex justify-between items-center mt-3 pt-3 border-t text-xs text-gray-500'>
                        <span class='text-green-600 font-bold'>☎ {$p['phone']}</span>
                    </div>
                </div>
            </a>";
        }
    }
    ?>
    </div>

    <!-- ================== TIN MỚI ĐĂNG ================== -->
    <aside class="col-span-12 lg:col-span-4">
        <div class="bg-white rounded-xl shadow p-4 sticky top-24">
            <h3 class="font-bold text-gray-700 mb-4 uppercase text-sm border-b pb-2">
                Tin mới đăng
            </h3>

            <?php
            $new=$pdo->query("SELECT id,title,price,
                     (SELECT filename FROM post_images WHERE post_id=posts.id LIMIT 1) thumb
                     FROM posts 
                     WHERE status='approved'
                     ORDER BY created_at DESC
                     LIMIT 6");

            foreach($new as $n){
                $price=$n['price']>1000?$n['price']/1000000:$n['price'];
                $m=rtrim(rtrim(number_format($price,1),'0'),'.');
                $thumb=$n['thumb']?'uploads/'.$n['thumb']:'assets/no-image.png';

                echo "
                <a href='post.php?id={$n['id']}' class='flex gap-3 mb-3 hover:bg-gray-50 p-2 rounded'>
                    <img src='{$thumb}' class='w-16 h-14 object-cover rounded'>
                    <div class='flex-1'>
                        <h4 class='text-xs font-bold text-gray-700 line-clamp-2'>
                            {$n['title']}
                        </h4>
                        <span class='text-red-600 text-xs font-bold'>
                            {$m} triệu/tháng
                        </span>
                    </div>
                </a>";
            }
            ?>
        </div>
    </aside>

</div>
</main>

</div>

<script>
    const slides=document.querySelectorAll('.slide');
    let i=0;
    function show(n){ slides.forEach(s=>s.style.opacity=0); slides[n].style.opacity=1; }
    if(slides.length > 0) { show(0); setInterval(()=>{i=(i+1)%slides.length;show(i)},5000); }

    function runAds(containerId) {
        const ads = document.querySelectorAll(`#${containerId} .ad-img`);
        let cur = 0;
        if(ads.length > 0) {
            setInterval(() => {
                ads[cur].classList.remove('active');
                cur = (cur + 1) % ads.length;
                ads[cur].classList.add('active');
            }, 8000);
        }
    }
    runAds('ad-l-container');
    runAds('ad-r-container');
</script>
<?php require 'footer.php'; ?>
</body>
</html>