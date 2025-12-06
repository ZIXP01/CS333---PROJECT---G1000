<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/resources_helpers.php";

if ($_SESSION['role'] !== 'admin') exit;

if ($_POST) {
    createResource($conn, $_POST['title'], $_POST['description']);
    header("Location: resources.php");
}
?>
<form method="post">
<input name="title" placeholder="Title">
<textarea name="description"></textarea>
<button type="submit">Create</button>
</form>
