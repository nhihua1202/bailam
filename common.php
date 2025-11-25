<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'header.php';
function render_header(){ /* header already printed by include */ }
function render_footer(){ include 'footer.php'; }
?>