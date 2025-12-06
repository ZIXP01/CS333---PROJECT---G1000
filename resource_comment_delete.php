<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/resources_helpers.php";

deleteComment($conn, $_GET['id'], $_SESSION['role'], $_SESSION['user_id']);
header("Location: resource_view.php?id=" . $_GET['res']);
?>
