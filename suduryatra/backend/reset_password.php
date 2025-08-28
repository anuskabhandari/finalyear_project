<?php
session_start();
require "func.php"; // Your database connection function

header('Content-Type: application/json');

$conn = dbConnect(); // Make sure this returns a valid MySQLi connection
if(!$conn){
    echo json_encode(['success'=>false,'message'=>'Database connection failed']);
    exit;
}

// Check if reset_user_id is set in session
if(!isset($_SESSION['reset_user_id'])){
    echo json_encode(['success'=>false,'message'=>'Unauthorized access.']);
    exit;
}

$user_id = $_SESSION['reset_user_id'];
$password = $_POST['password'] ?? '';

if(empty($password)){
    echo json_encode(['success'=>false,'message'=>'Password is required.']);
    exit;
}

// Hash the new password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Update password in user table
$stmt = $conn->prepare("UPDATE user SET password=? WHERE user_id=?");
$stmt->bind_param("si", $hashed_password, $user_id);

if($stmt->execute()){
    // Optionally delete/reset the reset_code if you have a password_reset table
    if(isset($_SESSION['reset_code'])){
        $reset_code = $_SESSION['reset_code'];
        $conn->query("DELETE FROM password_reset WHERE reset_code='$reset_code'");
        unset($_SESSION['reset_code']);
    }

    // Clear session after successful reset
    unset($_SESSION['reset_user_id']);

    echo json_encode(['success'=>true,'message'=>'Password reset successfully! Redirecting to login...']);
} else {
    echo json_encode(['success'=>false,'message'=>'Failed to reset password: '.$stmt->error]);
}

$conn->close();
?>