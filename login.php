<?php
require_once __DIR__ . '/config.php';

session_start(); 

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } else {
        if ($email === 'user@example.com' && $password === 'password123') {
            $_SESSION['user'] = $email;
            header('Location: dashboard.php'); 
            exit(); 
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>

<header>
  <h1>Login Page</h1>
</header>

<main class="container">
  <section>

    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <fieldset>
        <legend>User Login</legend>

        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" required minlength="6"><br><br>

        <button type="submit">Log in</button>
      </fieldset>
    </form>

  </section>
</main>

</body>
</html>

</main>

</body>
</html>
