<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php"); 
    exit();
}
require_once '../backend/func.php';
$conn = dbConnect();

// Fetch pending tour operators
$result = $conn->query("SELECT * FROM user WHERE role_id=3 AND status='pending'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Manage Tour Operators</title>
<link rel="stylesheet" href="../assets/css/dashboard.css" />
<style>
.cards { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 20px; }
.card { background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); flex:1 1 250px; font-size:0.85rem; }
.card svg { width:24px; height:24px; margin-bottom:8px; fill:#2f855a; }
.card h3 { margin:5px 0; font-size:1rem; }
.card p { font-size:0.8rem; margin-bottom:10px; color:#555; }
.card a { font-size:0.8rem; color:#2f855a; text-decoration:none; font-weight:500; }
.card a:hover { text-decoration:underline; }

.table-container { margin-top:20px; overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:0.85rem; background:#fff; }
th, td { border:1px solid #ddd; padding:6px 8px; text-align:center; }
th { background-color:#2f855a; color:#fff; font-weight:500; }
button { font-size:0.75rem; padding:4px 8px; border-radius:4px; border:none; cursor:pointer; }
.approve { background-color:#38a169; color:white; }
.reject { background-color:#e53e3e; color:white; }
</style>
</head>
<body>
<div class="container">
  <nav class="sidebar" id="sidebar">
    <div class="logo">Admin</div>
    <div class="nav-links">
      <a href="dashboard.php">
        <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        <span>Dashboard</span>
      </a>
      <a href="manage_users.php">
        <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2 0-6 1-6 3v2h12v-2c0-2-4-3-6-3zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 2.06 1.97 3.45v2h6v-2c0-2-4-3-6-3z"/></svg>
        <span>Manage Users</span>
      </a>
      <a href="manage_places.php">
        <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
        <span>Manage Places</span>
      </a>
      <a href="manage_packages.php">
        <svg viewBox="0 0 24 24"><path d="M20 8V6h-4V4h-4v2H4v2H2v4h2v2h4v2h4v-2h4v-2h2v-4h-2z"/></svg>
        <span>Manage Packages</span>
      </a>
      <a href="manage_bookings.php">
        <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM5 21V8h14v13H5z"/></svg>
        <span>Manage Bookings</span>
      </a>
      <a href="adminmanage_reviews.php">
        <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21 16.54 13.97 22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
        <span>Manage Reviews</span>
      </a>
      <a href="manage_touroperator.php" class="active">
        <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2 0-6 1-6 3v2h12v-2c0-2-4-3-6-3zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 2.06 1.97 3.45v2h6v-2c0-2-4-3-6-3z"/></svg>
        <span>Manage Operators</span>
      </a>
      <a href="../logout.php" onclick="return confirm('Are you sure you want to logout?')">
        <svg viewBox="0 0 24 24"><path d="M16 13v-2H7V8l-5 4 5 4v-3h9zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
        <span>Logout</span>
      </a>
    </div>
    <button class="toggle-btn" id="toggleBtn">&#9776;</button>
  </nav>

  <main class="main-content">
    <h1>Pending Tour Operators</h1>

    <div class="cards">
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="card">
          <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2 0-6 1-6 3v2h12v-2c0-2-4-3-6-3zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 2.06 1.97 3.45v2h6v-2c0-2-4-3-6-3z"/></svg>
          <h3><?= $row['first_name'] . " " . $row['last_name'] ?></h3>
          <p>Email: <?= $row['email'] ?></p>
          <div>
            <form method="post" action="approve_operator.php" style="display:inline;">
              <input type="hidden" name="id" value="<?= $row['user_id'] ?>">
              <button class="approve">✅ Approve</button>
            </form>
            <form method="post" action="reject_operator.php" style="display:inline;">
              <input type="hidden" name="id" value="<?= $row['user_id'] ?>">
              <button class="reject">❌ Reject</button>
            </form>
          </div>
        </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p style="margin:20px;">No pending tour operators.</p>
      <?php endif; ?>
    </div>
  </main>
</div>
<script src="../assets/js/hamburgermenu.js"></script>
</body>
</html>
