<?php
// auth.php 

function is_logged_in() {
return !empty($_SESSION['user']);
}

function require_login() {
if (!is_logged_in()) {
header('Location: /public/login.php');
exit;
}
}

function current_user() {
return $_SESSION['user'] ?? null;
}

function require_admin() {
$u = current_user();
if (!$u || $u['role'] !== 'admin') {
http_response_code(403);
echo "Forbidden: admin only.";
exit;
}
}