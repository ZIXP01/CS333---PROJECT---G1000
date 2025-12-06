<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Dashboard - ITCS333</title>
<link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<main class="container">
<h2>Admin Dashboard</h2>
<ul>
<li><a href="users.php">Manage Students</a></li>
<li><a href="/public/index.php">View Public Site</a></li>
</ul>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>