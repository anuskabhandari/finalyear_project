<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    header('Location: ../login.php');
    exit();
}
require_once '../backend/func.php'; 
$conn = dbConnect();  

// Logged-in tour operator ID
$tourOperatorId = $_SESSION['user_id'];

// Get total packages created by tour operator
$sqlPackages = "SELECT COUNT(*) AS total_packages FROM package WHERE created_by = ?";
$stmtPackages = $conn->prepare($sqlPackages);
$stmtPackages->bind_param("i", $tourOperatorId);
$stmtPackages->execute();
$resultPackages = $stmtPackages->get_result()->fetch_assoc();
$totalPackages = $resultPackages['total_packages'] ?? 0;

// Get total bookings received for this operator's packages
$sqlBookings = "SELECT COUNT(*) AS total_bookings 
                FROM bookings b
                JOIN package p ON b.package_id = p.package_id
                WHERE p.created_by = ?";
$stmtBookings = $conn->prepare($sqlBookings);
$stmtBookings->bind_param("i", $tourOperatorId);
$stmtBookings->execute();
$resultBookings = $stmtBookings->get_result()->fetch_assoc();
$totalBookings = $resultBookings['total_bookings'] ?? 0;

// Get total reviews for this operator's packages
$sqlReviews = "SELECT COUNT(*) AS total_reviews 
               FROM review r
               JOIN package p ON r.package_id = p.package_id
               WHERE p.created_by = ?";
$stmtReviews = $conn->prepare($sqlReviews);
$stmtReviews->bind_param("i", $tourOperatorId);
$stmtReviews->execute();
$resultReviews = $stmtReviews->get_result()->fetch_assoc(); 
$totalReviews = $resultReviews['total_reviews'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Tour Operator Dashboard - Final Year Project</title>
<link rel="stylesheet" href="../assets/css/dashboard.css"/>
</head>
<body>

<div class="container">
  <nav class="sidebar" id="sidebar">
    <div class="logo">TourOperator</div>
    <div class="nav-links">
      <a href="#" class="active">
        <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        <span>Dashboard</span>
      </a>
      <a href="my_packages.php">
        <svg viewBox="0 0 24 24"><path d="M20 8V6h-4V4h-4v2H4v2H2v4h2v2h4v2h4v-2h4v-2h2v-4h-2z"/></svg>
        <span>My Packages</span>
      </a>
      <a href="bookings_received.php">
        <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM5 21V8h14v13H5z"/></svg>
        <span>Bookings Received</span>
</a>
      <a href="profile_settings.php">
        <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        <span>Profile Settings</span>
      </a>
      <a href="../logut.php" onclick="return confirm('Are you sure you want to logout?')">
  <svg viewBox="0 0 24 24">
    <path d="M16 13v-2H7V8l-5 4 5 4v-3h9zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
  </svg>
  <span>Logout</span>
</a>
 
    </div>
    <button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">&#9776;</button>
  </nav>

  <main class="main-content">
    <h1>Tour Operator Dashboard</h1>
    <div class="cards">
      <div class="card" tabindex="0">
        <svg viewBox="0 0 24 24"><path d="M20 8V6h-4V4h-4v2H4v2H2v4h2v2h4v2h4v-2h4v-2h2v-4h-2z"/></svg>
        <h3>My Packages</h3>
        <p>Create and manage your travel packages.</p>
        <a href="my_packages.php">Go to Packages</a>
      </div>

      <div class="card" tabindex="0">
        <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM5 21V8h14v13H5z"/></svg>
        <h3>Bookings Received</h3>
        <p>View and manage bookings from customers.</p>
        <a href="bookings_received.php">Go to Bookings</a>
      </div>
      <div class="card" tabindex="0">
        <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        <h3>Profile Settings</h3>
        <p>Update your profile and account details.</p>
        <a href="profile_settings.php">Go to Profile</a>
      </div>
    </div>
  </main>
</div>

<script src="../../assets/js/hamburgermenu.js"></script>

</body>
</html>
