<?php
session_start();

// Destroy all session data
$_SESSION = array();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logged Out - Tourist Guide</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .logout-box {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .logout-box h2 {
            margin-bottom: 20px;
            color: #00796b;
        }
        .logout-box a {
            text-decoration: none;
            background-color: #00796b;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }
        .logout-box a:hover {
            background-color: #005f66;
        }
    </style>
</head>
<body>
    <div class="logout-box">
        <h2>You have been logged out.</h2>
        <a href="login.php">Go to Login</a>
    </div>
</body>
</html>
