<?php
session_start();
require "func.php";

/**
 * Generate a 6-digit OTP
 * @return string The generated OTP
 */
function generateOTP() {
    return sprintf("%06d", mt_rand(0, 999999));
}

/**
 * Send OTP via email (using PHP mail function)
 * @param string $email Recipient email address
 * @param string $otp The OTP to send
 * @return bool True if email sent successfully
 */
function sendOTP($email, $otp) {
    $subject = "Your Sudur Yatra Login OTP";
    $message = "
    <html>
    <head>
        <title>Sudur Yatra OTP</title>
    </head>
    <body>
        <h2>Your Login OTP</h2>
        <p>Your One-Time Password (OTP) for Sudur Yatra login is:</p>
        <h1 style='color: #00796b; font-size: 32px;'>$otp</h1>
        <p>This OTP is valid for 10 minutes.</p>
        <p>If you didn't request this, please ignore this email.</p>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@suduryatra.com" . "\r\n";
    
    return mail($email, $subject, $message, $headers);
}

/**
 * Store OTP in database with expiration
 * @param string $email User email
 * @param string $otp The OTP to store
 * @return bool True if stored successfully
 */
function storeOTP($email, $otp) {
    $mysqli = dbConnect();
    
    // Delete any existing OTP for this email
    $delete = $mysqli->prepare("DELETE FROM otp_storage WHERE email = ?");
    $delete->bind_param("s", $email);
    $delete->execute();
    $delete->close();
    
    // Store new OTP with 10-minute expiration
    $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    $stmt = $mysqli->prepare("INSERT INTO otp_storage (email, otp, expiry_time) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $otp, $expiry);
    
    $result = $stmt->execute();
    $stmt->close();
    $mysqli->close();
    
    return $result;
}

/**
 * Verify OTP from database
 * @param string $email User email
 * @param string $otp The OTP to verify
 * @return bool True if OTP is valid
 */
function verifyOTP($email, $otp) {
    $mysqli = dbConnect();
    
    $stmt = $mysqli->prepare("SELECT otp, expiry_time FROM otp_storage WHERE email = ? AND otp = ?");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $expiry_time = strtotime($row['expiry_time']);
        $current_time = time();
        
        if ($current_time <= $expiry_time) {
            // OTP is valid, delete it after use
            $delete = $mysqli->prepare("DELETE FROM otp_storage WHERE email = ?");
            $delete->bind_param("s", $email);
            $delete->execute();
            $delete->close();
            
            $stmt->close();
            $mysqli->close();
            return true;
        }
    }
    
    $stmt->close();
    $mysqli->close();
    return false;
}

/**
 * Check if user exists in database
 * @param string $email User email
 * @return bool True if user exists
 */
function userExists($email) {
    $mysqli = dbConnect();
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    
    $stmt->close();
    $mysqli->close();
    
    return $count > 0;
}

// Handle OTP generation request
if (isset($_POST['generate_otp'])) {
    $email = trim($_POST['email']);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: ../login.php");
        exit();
    }
    
    if (!userExists($email)) {
        $_SESSION['error'] = "Email not found in our system";
        header("Location: ../login.php");
        exit();
    }
    
    $otp = generateOTP();
    
    if (storeOTP($email, $otp)) {
        if (sendOTP($email, $otp)) {
            $_SESSION['otp_email'] = $email;
            $_SESSION['success'] = "OTP has been sent to your email";
            header("Location: ../otp-verification.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed to send OTP email";
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Failed to generate OTP";
        header("Location: ../login.php");
        exit();
    }
}

// Handle OTP verification
if (isset($_POST['verify_otp'])) {
    $email = $_SESSION['otp_email'] ?? '';
    $otp = trim($_POST['otp']);
    
    if (empty($email)) {
        $_SESSION['error'] = "Session expired. Please try again";
        header("Location: ../login.php");
        exit();
    }
    
    if (verifyOTP($email, $otp)) {
        // OTP verified, log user in
        $mysqli = dbConnect();
        $stmt = $mysqli->prepare("SELECT user_id, role_id FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role_id'] = $row['role_id'];
            
            $stmt->close();
            $mysqli->close();
            
            // Redirect based on role
            switch ($row['role_id']) {
                case 1:
                    header("Location: admin/admin_dashboard.php");
                    break;
                case 2:
                    header("Location: dashboard.php");
                    break;
                case 3:
                    header("Location: touroperator/tour_operatordashboard.php");
                    break;
                default:
                    header("Location: dashboard.php");
            }
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid or expired OTP";
        header("Location: ../otp-verification.php");
        exit();
    }
}
?>
