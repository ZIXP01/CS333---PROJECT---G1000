<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/resources_helpers.php";

$id = $_GET['id'];
$res = getResource($conn, $id);
$comments = getComments($conn, $id);

if ($_POST) {
    addComment($conn, $id, $_SESSION['user_id'], $_POST['comment']);
    header("Location: resource_view.php?id=$id");
}
?>
<h1><?= $res['title'] ?></h1>
<p><?= $res['description'] ?></p>

<h2>Comments</h2>
<ul>
<?php foreach ($comments as $c): ?>
<li>
    <b><?= $c['name'] ?>:</b> <?= $c['comment'] ?>
    <?php if ($_SESSION['role']=='admin' || $c['user_id']==$_SESSION['user_id']): ?>
        <a href="resource_comment_delete.php?id=<?= $c['id'] ?>&res=<?= $id ?>">Delete</a>
    <?php endif; ?>
</li>
<?php endforeach; ?>
</ul>

<form method="post">
<textarea name="comment"></textarea>
<button>Add Comment</button>
</form>
