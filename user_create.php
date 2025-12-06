<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if (!check_csrf($_POST['csrf'] ?? '')) {
$errors[] = 'Invalid CSRF token.';
} else {
$name = trim($_POST['name'] ?? '');
$student_id = trim($_POST['student_id'] ?? null);
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?

$errors = []? '' : ''; 
$role = $_POST['role'] === 'admin' ? 'admin' : 'student';

if ($name === '' || $email === '' || $password === '') $errors[] = 'Name, email and password are required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';

if (empty($errors)) {
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (name, student_id, email, password, role) VALUES (?, ?, ?, ?, ?)');
try {
$stmt->execute([$name, $student_id, $email, $hash, $role]);
header('Location: users.php'); exit;
} catch (PDOException $e) {
$errors[] = 'Could not create user: ' . $e->getMessage();
}
}
}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Create Student - ITCS333</title>
<link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<main class="container">
<h2>Add Student</h2>
<?php if ($errors): ?><div class="error"><?=htmlspecialchars(implode("; ", $errors))?></div><?php endif; ?>
<form method="post" action="">
<input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
<label>Name:<br><input name="name" required></label><br>
<label>Student ID:<br><input