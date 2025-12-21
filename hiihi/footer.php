<?php
/**
 * FILE: footer.php
 * Project: Hanoi Rental - Nhóm 12
 */
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* CSS TỔNG THỂ CHO FOOTER */
    .hn-footer {
        background-color: #1a1a1a; /* Màu nền đen xám giống mẫu */
        color: #ffffff;
        padding: 50px 0 20px 0;
        font-family: 'Segoe UI', Arial, sans-serif;
        line-height: 1.6;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        padding: 0 20px;
    }

    .footer-column {
        flex: 1;
        min-width: 280px;
        margin-bottom: 30px;
    }

    /* KHU VỰC LOGO & BRAND */
    .logo-container {
        margin-bottom: 20px;
    }

    .footer-logo-img {
        width: 100px; /* Độ rộng logo */
        height: auto;
        border-radius: 8px; /* Bo góc cho ảnh logo */
        margin-bottom: 15px;
        display: block;
        background: #fff; /* Tạo nền trắng nhẹ cho logo nổi bật */
        padding: 5px;
    }

    .brand-name {
        color: #c1d72e; /* Màu vàng xanh đặc trưng của mẫu */
        font-size: 26px;
        font-weight: bold;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .brand-desc {
        font-size: 14px;
        color: #aaaaaa;
        padding-right: 40px;
        margin-top: 15px;
        text-align: justify;
    }

    /* TIÊU ĐỀ CÁC CỘT */
    .column-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 25px;
        color: #ffffff;
        text-transform: uppercase;
        position: relative;
    }

    /* THÔNG TIN LIÊN HỆ & LINKS */
    .footer-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-list li {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
        color: #aaaaaa;
        font-size: 14px;
    }

    .footer-list i {
        color: #c1d72e; /* Màu icon đồng bộ với logo */
        margin-right: 12px;
        margin-top: 4px;
        width: 16px;
        text-align: center;
    }

    .footer-list a {
        color: #aaaaaa;
        text-decoration: none;
        transition: 0.3s;
    }

    .footer-list a:hover {
        color: #c1d72e;
        padding-left: 5px;
    }

    /* MẠNG XÃ HỘI */
    .social-box {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .social-item {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        color: white;
        text-decoration: none;
        font-size: 18px;
        transition: 0.3s;
    }

    .bg-facebook { background-color: #3b5998; }
    .bg-zalo { background-color: #0084ff; font-weight: bold; font-size: 13px; }
    .bg-github { background-color: #333333; }
    
    .social-item:hover {
        transform: translateY(-3px);
        opacity: 0.9;
    }

    /* BẢN QUYỀN (FOOTER BOTTOM) */
    .footer-bottom {
        text-align: center;
        border-top: 1px solid #333;
        margin-top: 40px;
        padding-top: 20px;
        font-size: 13px;
        color: #777777;
    }

    .footer-bottom b {
        color: #c1d72e;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .footer-column {
            min-width: 100%;
        }
        .brand-desc {
            padding-right: 0;
        }
    }
</style>

<footer class="hn-footer">
    <div class="footer-container">
        
        <div class="footer-column">
            <div class="logo-container">
                <img src="https://png.pngtree.com/template/20190217/ourmid/pngtree-real-estate-logohome-logohouse-logosimple-design-image_56618.jpg" 
                     alt="Hanoi Rental Logo" 
                     class="footer-logo-img">
                <h2 class="brand-name">HANOI RENTAL</h2>
            </div>
            <p class="brand-desc">
                Hệ thống hỗ trợ thuê trọ và quản lý căn hộ dịch vụ chuyên nghiệp. 
                Chúng tôi mang đến giải pháp tìm kiếm chỗ ở nhanh chóng, uy tín và minh bạch tại khu vực Hà Nội.
            </p>
            <div class="social-box">
                <a href="#" class="social-item bg-facebook" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-item bg-zalo" title="Zalo">Zalo</a>
                <a href="https://github.com" class="social-item bg-github" title="Github"><i class="fab fa-github"></i></a>
            </div>
        </div>

        <div class="footer-column">
            <h3 class="column-title">Thông tin liên hệ</h3>
            <ul class="footer-list">
                <li><i class="fas fa-map-marker-alt"></i> 31 Dịch Vọng Hậu, Cầu Giấy, Hà Nội</li>
                <li><i class="fas fa-phone-alt"></i> 0123456789</li>
                <li><i class="fas fa-envelope"></i> phanmemthuetro@gmail.com</li>
            </ul>
        </div>

        <div class="footer-column">
            <h3 class="column-title">Góc nhìn thuê trọ</h3>
            <ul class="footer-list">
                <li><i class="fas fa-chevron-right"></i> <a href="#">Kinh nghiệm thuê phòng trọ tránh lừa đảo</a></li>
                <li><i class="fas fa-chevron-right"></i> <a href="#">Review khu vực trọ sinh viên Cầu Giấy</a></li>
                <li><i class="fas fa-chevron-right"></i> <a href="#">Thủ tục đăng ký tạm trú cho người thuê</a></li>
                <li><i class="fas fa-chevron-right"></i> <a href="#">Xem thêm...</a></li>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        <p>© Bản quyền thuộc về <b>Nhóm 12</b> | Xin chân thành cảm ơn </p>
    </div>
</footer>