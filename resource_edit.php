<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/resources_helpers.php";

if ($_SESSION['role'] !== 'admin') exit;

$id = $_GET['id'];
$res = getResource($conn, $id);

if ($_POST) {
    updateResource($conn, $id, $_POST['title'], $_POST['description']);
    header("Location: resources.php");
}
?>
<form method="post">
<input name="title" value="<?= $res['title'] ?>">
<textarea name="description"><?= $res['description'] ?></textarea>
<button type="submit">Save</button>
</form>
