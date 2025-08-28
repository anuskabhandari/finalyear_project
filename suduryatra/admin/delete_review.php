<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php"); // Redirect to login
    exit();
}
require_once '../backend/func.php';
$conn = dbConnect();

$reviewId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$reviewId) {
    die("No review ID specified.");
}

// Delete the review
$stmt = $conn->prepare("DELETE FROM review WHERE review_id = ?");
$stmt->bind_param("i", $reviewId);
if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: adminmanage_reviews.php?msg=Review+deleted+successfully");
    exit;
} else {
    $error = "Error deleting review: " . $stmt->error;
    $stmt->close();
    $conn->close();
    die($error);
}
