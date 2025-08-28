<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php"); // Redirect to login
    exit();
}
require_once '../backend/func.php';
$conn = dbConnect();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Check current status
    $res = $conn->query("SELECT status FROM bookings WHERE id=$id");
    $row = $res->fetch_assoc();

    if ($row['status'] === 'Approved') {
        header("Location: manage_bookings.php?msg=Booking already approved");
        exit;
    } elseif ($row['status'] === 'Cancelled') {
        header("Location: manage_bookings.php?msg=Booking already cancelled, cannot approve");
        exit;
    }

    // Approve booking
    $conn->query("UPDATE bookings SET status='Approved' WHERE id=$id");
    header("Location: manage_bookings.php?msg=Booking approved successfully");
    exit;
}
?>
