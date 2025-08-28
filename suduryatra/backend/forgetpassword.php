<?php
session_start();
require_once "func.php";
require_once __DIR__ . '/../vendor/autoload.php';  // ✅ correct autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// header('Content-Type: application/json');  // ✅ ensure JSON

$conn = dbConnect();

$email = $_POST['email'] ?? '';
$resend = $_POST['resend'] ?? 0;

if (empty($email)) {
    echo json_encode(['success'=>false, 'message'=>'Please enter your email.']);
    exit;
}

// Check user exists
$stmt = $conn->prepare("SELECT user_id, first_name FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success'=>false, 'message'=>'Email does not exist.']);
    exit;
}
$user = $result->fetch_assoc();
$user_id = $user['user_id'];
$first_name = $user['first_name'];

// Generate OTP
$otp = rand(100000, 999999);

// If resend, delete old OTP
if ($resend) {
    $stmtDel = $conn->prepare("DELETE FROM password_resets WHERE user_id=?");
    $stmtDel->bind_param("i", $user_id);
    $stmtDel->execute();
}

// Insert OTP
$stmt2 = $conn->prepare("INSERT INTO password_resets (user_id, reset_code, created_at) VALUES (?, ?, NOW())");
$stmt2->bind_param("is", $user_id, $otp);
$stmt2->execute();

// Send OTP email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'pankaj101chaudhary@gmail.com'; // ✅ Gmail account
    $mail->Password = 'lbal qicb meyu uzkr';          // ✅ App password (not Gmail password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('pankaj101chaudhary@gmail.com', 'Suduryatra OTP');
    $mail->addAddress($email, $first_name);

    $mail->isHTML(true);
    $mail->Subject = 'Your OTP Code';
    $mail->Body = "Hello $first_name,<br><br>Your OTP code is: <b>$otp</b><br><br>Do not share this with anyone.";

    $mail->send();
    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    echo json_encode(['success'=>false, 'message'=>"OTP generated but email not sent. {$mail->ErrorInfo}"]);
}
$conn->close();
