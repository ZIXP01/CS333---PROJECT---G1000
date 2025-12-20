<!doctype html>
<html>
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<main class="container">
<h2>Login</h2>
<?php if ($err): ?><div class="error"><?=htmlspecialchars($err)?></div><?php endif; ?>
<form method="post" action="">
<input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
<label>Email:<br><input type="email" name="email" required></label><br>
<label>Password:<br><input type="password" name="password" required></label><br>
<button type="submit">Log in</button>
</form>
<p>Default admin: <code>admin@course.local</code> / <code>ChangeMe123!</code></p>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$token = $_POST['csrf'] ?? '';
if (!check_csrf($token)) {
$err = 'Invalid CSRF token.';
} else {
$stmt = $pdo->prepare('SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();
if ($user && password_verify($password, $user['password'])) {
unset($user['password']);
$_SESSION['user'] = $user;
header('Location: /public/index.php');
exit;
} else {
$err = 'Invalid credentials.';
}
}
}
?>

