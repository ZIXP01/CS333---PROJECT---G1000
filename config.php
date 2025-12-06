<?php
// config.php
session_start();

// update these settings to match your environment
$db_host = '127.0.0.1';
$db_name = 'itcs333';
$db_user = 'root';
$db_pass = '';
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

$options = [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
PDO::ATTR_EMULATE_PREPARES => false,
];

try {
$pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
http_response_code(500);
echo 'Database connection failed.';
exit;
}

// CSRF helper
if (!function_exists('csrf_token')) {
function csrf_token() {
if (empty($_SESSION['csrf_token'])) {
$_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
return $_SESSION['csrf_token'];
}
}

function check_csrf($token) {
return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}