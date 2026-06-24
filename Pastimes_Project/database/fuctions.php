<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isVerifiedSeller() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'seller' && $_SESSION['is_verified_seller'] == 1;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>