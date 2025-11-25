<?php
require 'header.php';
require 'functions.php';
require 'db.php';
require 'includes/districts.php';
?>
<main class="max-w-7xl mx-auto p-4">

  <h1 class="text-2xl font-semibold mb-4 text-center">DuongDomic</h1>

  <div class="flex flex-col md:flex-row gap-6 mb-6">

    <!-- ================= BỘ LỌC ================= -->
    <div class="w-full md:w-72 bg-white p-4 rounded shadow">
      <h3 class="font-semibold mb-3">Lọc</h3>

      <form method="get" id="filterForm">

        <!-- LOẠI PHÒNG -->
        <div class="mb-4">
          <?php 
            $t = $_GET['type'] ?? '';
            function sel($v,$t){ return ($v==$t) ? "selected" : ""; }
          ?>
          <label class="block text-sm font-medium mb-1">Loại phòng</label>
          <select name="type" class="p-2 border rounded w-full">
            <option value="" <?= sel("",$t) ?>>Tất cả</option>
            <option value="Phòng trọ" <?= sel("Phòng trọ",$t) ?>>Phòng trọ</option>
            <option value="Nhà nguyên căn" <?= sel("Nhà nguyên căn",$t) ?>>Nhà nguyên căn</option>
            <option value="Căn hộ chung cư" <?= sel("Căn hộ chung cư",$t) ?>>Căn hộ chung cư</option>
            <option value="Căn hộ mini" <?= sel("Căn hộ mini",$t) ?>>Căn hộ mini</option>
          </select>
        </div>

        <!-- KHU VỰC -->
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Khu vực</label>
          <select name="khu_vuc" class="mt-1 p-2 border rounded w-full">
            <option value="">Tất cả khu vực</option>

            <?php
              $selected_district = $_GET['khu_vuc'] ?? '';
              foreach ($districts_hanoi as $group => $districts) {
                  echo "<optgroup label='$group'>";
                  foreach ($districts as $d) {
                      $sel = ($d == $selected_district) ? 'selected' : '';
                      echo "<option value='$d' $sel>$d</option>";
                  }
                  echo "</optgroup>";
              }
            ?>
          </select>
        </div>

        <!-- MỨC GIÁ -->
        <div class="mb-4 text-sm">
          <?php 
            $pr = $_GET['price_range'] ?? 'all';
            function ck($v,$pr){ return ($v==$pr) ? "checked" : ""; }
          ?>
          <label><input type="radio" name="price_range" value="all" <?= ck('all',$pr) ?>> Tất cả mức giá</label><br>
          <label><input type="radio" name="price_range" value="0-1" <?= ck('0-1',$pr) ?>> Dưới 1 triệu</label><br>
          <label><input type="radio" name="price_range" value="1-3" <?= ck('1-3',$pr) ?>> 1 - 3 triệu</label><br>
          <label><input type="radio" name="price_range" value="3-5" <?= ck('3-5',$pr) ?>> 3 - 5 triệu</label><br>
          <label><input type="radio" name="price_range" value="5-7" <?= ck('5-7',$pr) ?>> 5 - 7 triệu</label><br>
          <label><input type="radio" name="price_range" value="7-10" <?= ck('7-10',$pr) ?>> 7 - 10 triệu</label><br>
          <label><input type="radio" name="price_range" value="10-13" <?= ck('10-13',$pr) ?>> 10 - 13 triệu</label><br>
          <label><input type="radio" name="price_range" value="13-15" <?= ck('13-15',$pr) ?>> 13 - 15 triệu</label><br>
          <label><input type="radio" name="price_range" value="15+" <?= ck('15+',$pr) ?>> Trên 15 triệu</label>
        </div>

        <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded w-full">
          Lọc
        </button>
      </form>
    </div>

    <!-- ================= DANH SÁCH BÀI VIẾT ================= -->
    <div class="flex-1">

      <!-- Ô tìm kiếm -->
      <form method="get" class="mb-4">
        <input type="text" name="q"
               placeholder="Tìm kiếm (VD: Cầu Giấy, gần đại học...)"
               value="<?= esc($_GET['q'] ?? '') ?>"
               class="w-full p-2 border rounded" />
      </form>

      <?php
      // ================= XÂY DỰNG SQL ==================
      $sql = "
        SELECT p.*, 
          (SELECT filename
           FROM post_images 
           WHERE post_id = p.id 
           ORDER BY id ASC 
           LIMIT 1) AS thumbnail
        FROM posts p
        WHERE p.status = 'approved'
          AND p.status_rent = 0
      ";

      $params = [];

      // LỌC LOẠI PHÒNG
      if (!empty($_GET['type'])) {
          $sql .= " AND p.type = :ctg";
          $params[':ctg'] = $_GET['type'];
      }

      // LỌC KHU VỰC
      if (!empty($_GET['khu_vuc'])) {
          $sql .= " AND p.khu_vuc = :kv";
          $params[':kv'] = $_GET['khu_vuc'];
      }

      // LỌC TỪ KHÓA
      if (!empty($_GET['q'])) {
          $sql .= " AND (p.title LIKE :q OR p.description LIKE :q OR p.khu_vuc LIKE :q)";
          $params[':q'] = "%" . $_GET['q'] . "%";
      }

      $sql .= " ORDER BY p.created_at DESC";

      $st = $pdo->prepare($sql);
      $st->execute($params);
      $posts = $st->fetchAll();

      // ================= LỌC GIÁ ==================
      $price_range = $_GET['price_range'] ?? 'all';
      $filtered = [];

      foreach ($posts as $p) {
          $raw = $p['price'];

          if (is_numeric($raw) && $raw > 1000) {
              $num = $raw / 1000000;
          } else {
              $num = floatval($raw);
          }

          if ($price_range !== 'all') {
              if ($price_range === '15+' && $num <= 15) continue;
              elseif (strpos($price_range, '-') !== false) {
                  list($min,$max) = explode('-', $price_range);
                  if (!($num >= $min && $num <= $max)) continue;
              }
          }
          $filtered[] = $p;
      }

      $posts = $filtered;

      // ================= HIỂN THỊ ==================
      if (!$posts) {
          echo '<p class="text-center text-gray-500">Không có tin nào hiển thị.</p>';
      } else {

          echo '<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">';

          foreach($posts as $p) {

              // FIX ẢNH — đúng thư mục uploads/
              $thumb = $p['thumbnail']
                       ? "uploads/" . $p['thumbnail']
                       : "assets/no-image.png";

              // Xử lý giá
              $raw = $p['price'];
              if (is_numeric($raw) && $raw > 1000) {
                  $m = $raw / 1000000;
              } else {
                  $m = floatval($raw);
              }
              $m = rtrim(rtrim(number_format($m, 1), '0'), '.');

              echo '
              <div class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition">
                <div class="w-full h-48 overflow-hidden rounded">
                  <img src="'.esc($thumb).'" class="w-full h-full object-cover">
                </div>

                <h3 class="font-semibold mt-3 text-lg">'.esc($p['title']).'</h3>
                <p class="text-sm text-gray-600">Khu vực: '.esc($p['khu_vuc']).'</p>

                <div class="flex items-center justify-between mt-2">
                  <a href="post.php?id='.$p['id'].'" class="text-blue-600 text-sm font-medium">Xem chi tiết</a>
                  <span class="text-sm font-medium text-green-700">'.$m.' triệu</span>
                </div>
              </div>
              ';
          }

          echo '</div>';
      }
      ?>
    </div>
  </div>

</main>
</body>
</html>
