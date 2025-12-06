<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/resources_helpers.php";

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$resources = getAllResources($conn);
?>
<h1>Resources (Admin)</h1>
<a href="resource_create.php">Add Resource</a>
<ul>
<?php foreach ($resources as $r): ?>
<li>
    <?= $r['title'] ?>
    <a href="resource_edit.php?id=<?= $r['id'] ?>">Edit</a>
    <a href="resource_delete.php?id=<?= $r['id'] ?>">Delete</a>
</li>
<?php endforeach; ?>
</ul>
