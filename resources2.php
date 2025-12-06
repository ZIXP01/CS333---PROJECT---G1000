<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/resources_helpers.php";

$resources = getAllResources($conn);
?>
<h1>Resources</h1>
<ul>
<?php foreach ($resources as $r): ?>
<li>
    <a href="resource_view.php?id=<?= $r['id'] ?>"><?= $r['title'] ?></a>
</li>
<?php endforeach; ?>
</ul>
