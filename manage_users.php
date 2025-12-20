<?php
require_once __DIR__ . '/config.php';

$users = [
    [
        'id' => 1,
        'name' => 'Test User',
        'student_id' => '20240001',
        'email' => 'test@example.com',
        'role' => 'Student'
    ]
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users</title>
  <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>

<header>
  <h1>User Management</h1>
</header>

<main class="container">

  <section>
    <h2>Actions</h2>
    <p><a href="#">Add New Student</a></p>
  </section>

  <section>
    <h2>Users List</h2>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Student ID</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= htmlspecialchars($user['name']) ?></td>
          <td><?= htmlspecialchars($user['student_id']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= htmlspecialchars($user['role']) ?></td>
          <td>
            <a href="#">Edit</a> |
            <a href="#">Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </section>

</main>

</body>
</html>
