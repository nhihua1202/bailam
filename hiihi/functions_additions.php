<?php
// functions_additions.php
// Add these helper functions into your existing functions.php

function isAdmin() {
    return isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin';
}
function isLandlord() {
    return isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'owner';
}
function isTenant() {
    return isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'renter';
}
?>