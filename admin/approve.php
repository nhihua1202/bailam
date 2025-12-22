<?php
// admin/approve_post.php
// FILE XỬ LÝ - KHÔNG ĐƯỢC REQUIRE header.php

require '../db.php';
require '../functions.php';

session_start();
$u = currentUser();

// chỉ ADMIN được duyệt
if (!$u || $u['role'] !== 'admin') {
    die('Không có quyền');
}

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = $_GET['action'] ?? '';

if ($id <= 0) {
    header('Location: manage_posts.php');
    exit;
}

if ($action === 'approve') {
    $stmt = $pdo->prepare("UPDATE posts SET status='approved' WHERE id=?");
    $stmt->execute([$id]);
}

if ($action === 'reject') {
    $stmt = $pdo->prepare("UPDATE posts SET status='rejected' WHERE id=?");
    $stmt->execute([$id]);
}

// QUAY LẠI TRANG QUẢN LÝ
header('Location: manage_posts.php');
exit;
