<?php
session_start();
require "func.php";

$conn = dbConnect();

$email = $_POST['email'] ?? '';
$otp = $_POST['otp'] ?? '';

if (empty($email) || empty($otp)) {
    echo json_encode(['success'=>false, 'message'=>'Email and OTP are required.']);
    exit;
}

// Get user
$stmt = $conn->prepare("SELECT user_id FROM user WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows===0) {
    echo json_encode(['success'=>false,'message'=>'Invalid email.']);
    exit;
}
$user = $result->fetch_assoc();
$user_id = $user['user_id'];

// Check OTP
$stmt2 = $conn->prepare("SELECT * FROM password_resets WHERE user_id=? AND reset_code=? AND created_at >= NOW() - INTERVAL 15 MINUTE");
$stmt2->bind_param("is",$user_id,$otp);
$stmt2->execute();
$res = $stmt2->get_result();

if ($res->num_rows===0) {
    echo json_encode(['success'=>false,'message'=>'Invalid or expired OTP.']);
    exit;
}

// OTP verified, delete OTP
$conn->query("DELETE FROM password_resets WHERE user_id=$user_id");

// Set session to allow password reset
$_SESSION['reset_user_id'] = $user_id;

echo json_encode(['success'=>true]);
$conn->close();