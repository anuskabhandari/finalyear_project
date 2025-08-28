<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php"); // Redirect to login
    exit();
}
require_once '../backend/func.php';
$conn = dbConnect();

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM package WHERE package_id=$id");
}
header("Location: manage_packages.php");
exit;
?>
