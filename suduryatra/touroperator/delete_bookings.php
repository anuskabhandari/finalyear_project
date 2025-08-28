<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    header('Location: ../login.php');
    exit();
}
require_once '../backend/func.php';
$conn = dbConnect();

if (isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);
    
    // Update status to Rejected
    $stmt = $conn->prepare("UPDATE bookings SET status = 'Rejected' WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    
    if ($stmt->execute()) {
        header("Location: bookings_received.php?msg=rejected");
        exit;
    } else {
        echo "Error updating booking.";
    }
} else {
    echo "Invalid booking ID.";
}
?>
