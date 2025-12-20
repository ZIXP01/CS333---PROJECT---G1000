<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$stmt = $pdo->prepare("SELECT id, name, student_id, email, role, created_at FROM users ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <p><a href="user_create.php">Add New Student</a></p>
  </section>

  <section>
    <h2>Users List</h2>

    <form action="#" method="post">
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
          <?php foreach ($users as $u): ?>
          <tr>
            <td><?= htmlspecialchars($u['id']) ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['student_id']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td>
              <a href="user_edit.php?id=<?= urlencode($u['id']) ?>">Edit</a>
              <?php if ($u['role'] !== 'admin'): ?>
                | <a
                    href="user_delete.php?id=<?= urlencode($u['id']) ?>&csrf=<?= urlencode(csrf_token()) ?>"
                    onclick="return confirm('Delete this user?')"
                  >
                    Delete
                  </a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </form>

  </section>

</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<script src="/public/js/manage_users.js" defer></script>

</body>
</html>
