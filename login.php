<?php

require_once __DIR__ . '/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email and password are required.';
    } else {
       
        $error = '';
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

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="login.php">
      <fieldset>
        <legend>User Login</legend>

        <label>Email</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required minlength="6"><br><br>

        <button type="submit">Log in</button>
      </fieldset>
    </form>

  </section>
</main>

</body>
</html>
