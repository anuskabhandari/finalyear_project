<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php"); // Redirect to login
    exit();
}
require_once '../backend/func.php';
$conn = dbConnect();
session_start();

if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    $_SESSION['msg'] = "❌ Access denied!";
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $conn->query("UPDATE user SET status='rejected' WHERE user_id=$id AND role_id=3");
    $_SESSION['msg'] = "❌ Tour operator rejected.";
}

header("Location: manage_touroperator.php");
exit;
?>
