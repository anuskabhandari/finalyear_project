<?php
session_start();

// Check if the user is logged in and is an user
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: ../login.php"); // Redirect to login
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Dashboard - Tourist Guide</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #f0f4f4;
      color: #333;
    }

    header {
      background: linear-gradient(to right, #00796b, #43a047);
      color: white;
      padding: 20px;
      text-align: center;
      font-size: 24px;
      position: relative;
    }

    .logout-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background: #b71c1c;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .logout-btn:hover {
      background: #b71c1c;
    }

    .container {
      max-width: 960px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h2 {
      color: #00796b;
    }

    .actions {
      margin-top: 30px;
    }

    .actions a {
      display: inline-block;
      margin: 10px;
      text-decoration: none;
      background: #00796b;
      color: white;
      padding: 12px 18px;
      border-radius: 6px;
    }

    .actions a:hover {
      background: #004d40;
    }
  </style>
</head>
<body>

<header>
  Welcome to Tourist Guide Dashboard
  <form method="post" action="logout.php" style="display:inline;">
    <button class="logout-btn" type="submit">Logout</button>
  </form>
</header>

<div class="container">
  <h2>Hello, Traveler!</h2>
  <p>You are successfully logged in. What would you like to do?</p>

  <div class="actions">
    <a href="booking.php">Book a Trip</a>
    <a href="destinations.php">View Destinations</a>
    <a href="index.php">Back to Home</a>
  </div>
</div>

</body>
</html>
