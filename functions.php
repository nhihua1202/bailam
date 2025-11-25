<?php
require_once "db.php";

if (!function_exists("esc")) {
    function esc($s) {
        return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists("currentUser")) {
    function currentUser() {
        return $_SESSION['user'] ?? null;
    }
}

/* ---- ROLE CHECKERS ---- */

if (!function_exists("isAdmin")) {
    function isAdmin() {
        $u = currentUser();
        return $u && ($u['role'] ?? '') === 'admin';
    }
}

if (!function_exists("isLandlord")) {
    function isLandlord() {
        $u = currentUser();
        return $u && ($u['role'] ?? '') === 'landlord';
    }
}

if (!function_exists("isRenter")) {
    function isRenter() {
        $u = currentUser();
        return $u && ($u['role'] ?? '') === 'renter';
    }
}

/* ---- IMAGE URL HELPER ---- */

if (!function_exists("getImageUrl")) {
    function getImageUrl($image) {
        // Nếu không có ảnh → trả về ảnh mặc định
        if (!$image || $image === '') {
            return "/hihih/assets/no-image.png";
        }

        // Ảnh nằm trong folder uploads
        return "/hihih/uploads/" . $image;
    }
}
