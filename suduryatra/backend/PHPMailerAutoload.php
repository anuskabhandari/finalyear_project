<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer install भएमा

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Gmail SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'bhandarirekha652@gmail.com';       // Gmail account
    $mail->Password = 'dnoi nbum omya sftf';          // Gmail App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    //Recipients
    $mail->setFrom('your_email@gmail.com', 'Your Name');
    $mail->addAddress('recipient_email@gmail.com', 'Recipient Name');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email using <b>PHPMailer</b>.';
    $mail->AltBody = 'This is the plain text body.';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>