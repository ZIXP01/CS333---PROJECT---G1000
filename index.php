<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ITCS333 Course Page</title>
<link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<main class="container">
<h1>Welcome to ITCS333</h1>
<p>This is the course landing page. Please <a href="/public/login.php">log in</a> to access course features.</p>
<?php if (is_logged_in()): ?>
<p>Logged in as: <strong><?=htmlspecialchars(current_user()['name'])?></strong>
<?php if (current_user()['role'] === 'admin'): ?>
— <a href="/admin/dashboard.php">Admin Dashboard</a>
<?php endif; ?>
</p>
<?php endif; ?>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>