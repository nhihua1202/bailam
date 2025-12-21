<?php
session_start();
require '../db.php';
require '../functions.php';

/* -------------------------
   KIỂM TRA QUYỀN ADMIN
-------------------------- */
if (!isAdmin()) {
    header("Location: ../index.php");
    exit;
}

/* -------------------------
   XỬ LÝ XÓA BÀI ĐĂNG
-------------------------- */
if (isset($_GET['id'])) {
    $post_id = (int)$_GET['id'];

    try {
        // 1. Lấy tên file ảnh từ cột 'image' để xóa file vật lý
        $stmt_img = $pdo->prepare("SELECT image FROM posts WHERE id = ?");
        $stmt_img->execute([$post_id]);
        $post = $stmt_img->fetch();

        if ($post) {
            // Xóa file ảnh trong thư mục uploads nếu tồn tại
            if (!empty($post['image'])) {
                $file_path = "../uploads/" . $post['image'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            // 2. Xóa các dữ liệu liên quan ở bảng post_images (nếu có)
            // Vì bạn có bảng post_images liên kết với post_id
            $stmt_extra_imgs = $pdo->prepare("SELECT filename FROM post_images WHERE post_id = ?");
            $stmt_extra_imgs->execute([$post_id]);
            $extra_imgs = $stmt_extra_imgs->fetchAll();
            
            foreach ($extra_imgs as $img) {
                $extra_path = "../uploads/" . $img['filename'];
                if (file_exists($extra_path)) {
                    unlink($extra_path);
                }
            }
            
            $pdo->prepare("DELETE FROM post_images WHERE post_id = ?")->execute([$post_id]);

            // 3. Cuối cùng xóa bài đăng trong bảng posts
            $stmt_del = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt_del->execute([$post_id]);
        }
    } catch (PDOException $e) {
        die("Lỗi khi xóa bài đăng: " . $e->getMessage());
    }
}

// Quay lại trang quản lý
header("Location: manage_posts.php");
exit;