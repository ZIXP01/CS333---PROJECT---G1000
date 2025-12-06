<?php
session_start();
require_once "../includes/auth.php";
require_once "../includes/resources_helpers.php";

if ($_SESSION['role'] !== 'admin') exit;

deleteResource($conn, $_GET['id']);
header("Location: resources.php");
?>
