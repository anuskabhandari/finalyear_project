<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php"); // Redirect to login
    exit();
}
require_once '../backend/func.php';
$conn = dbConnect();

// Fetch all bookings
$result = $conn->query("SELECT * FROM bookings ORDER BY booking_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Bookings</title>
<link rel="stylesheet" href="/assets/css/dashboard.css"/>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
  <nav class="sidebar" id="sidebar">
    <div class="logo">Admin</div>
    <div class="nav-links">
      <a href="#">
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
      <a href="manage_bookings.php" class="active">
        <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM5 21V8h14v13H5z"/></svg>
        <span>Manage Bookings</span>
      </a>
      <a href="adminmanage_reviews.php">
        <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21 16.54 13.97 22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
        <span>Manage Reviews</span>
      </a>

      <!-- New Manage Operators Link -->
      <a href="manage_touroperator.php">
        <svg viewBox="0 0 24 24">
          <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2 0-6 1-6 3v2h12v-2c0-2-4-3-6-3zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 2.06 1.97 3.45v2h6v-2c0-2-4-3-6-3z"/>
        </svg>
        <span>Manage Operators</span>
      </a>

      <a href="../logout.php" onclick="return confirm('Are you sure you want to logout?')">
        <svg viewBox="0 0 24 24">
          <path d="M16 13v-2H7V8l-5 4 5 4v-3h9zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
        </svg>
        <span>Logout</span>
      </a>
    </div>
    <button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">&#9776;</button>
  </nav>

  <!-- Main Content -->
  <main class="flex-1 p-10">
    <h2 class="text-3xl font-bold text-emerald-900 mb-6">Manage Bookings</h2>
    <div class="bg-white rounded-2xl shadow-lg p-6 overflow-x-auto">
      <table class="min-w-full text-left text-sm divide-y divide-gray-200">
        <thead class="bg-emerald-100 text-emerald-900 text-sm uppercase">
          <tr>
            <th class="p-3">ID</th>
            <th class="p-3">Name</th>
            <th class="p-3">Email</th>
            <th class="p-3">Destination</th>
            <th class="p-3">People</th>
            <th class="p-3">Date</th>
            <th class="p-3">Total Price</th>
            <th class="p-3">Booking Date</th>
            <th class="p-3 text-center">Status</th>
            <th class="p-3 text-center">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <?php 
                $status = $row['status'] ?? 'Pending';
                $statusColor = $status == 'Pending' ? 'yellow' : ($status == 'Approved' ? 'green' : 'red');
              ?>
              <tr class="hover:bg-gray-50 transition">
                <td class="p-3"><?= $row['id']; ?></td>
                <td class="p-3"><?= htmlspecialchars($row['name']); ?></td>
                <td class="p-3"><?= htmlspecialchars($row['email']); ?></td>
                <td class="p-3"><?= htmlspecialchars($row['destination']); ?></td>
                <td class="p-3"><?= $row['people']; ?></td>
                <td class="p-3"><?= $row['date']; ?></td>
                <td class="p-3"><?= $row['total_price'] !== null ? $row['total_price'] : 'N/A'; ?></td>
                <td class="p-3"><?= $row['booking_date']; ?></td>
                <td class="p-3 text-center">
                  <span class="bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800 px-3 py-1 rounded-full text-xs font-semibold"><?= $status ?></span>
                </td>
                <td class="p-3 text-center">
                  <a href="<?php if($status == 'Approved') echo 'javascript:void(0)'; else echo 'approve_booking.php?id='.$row['id']; ?>" 
                     onclick="<?php if($status == 'Approved') echo 'alert(\'Already Approved\'); return false;'; else echo 'return confirm(\'Are you sure you want to approve this booking?\');'; ?>" 
                     class="inline-block bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded-full font-medium mr-2">
                     <i class="fas fa-check"></i> Approve
                  </a>
                  <a href="<?php if($status == 'Cancelled') echo 'javascript:void(0)'; else echo 'cancel_booking.php?id='.$row['id']; ?>" 
                     onclick="<?php if($status == 'Cancelled') echo 'alert(\'Already Cancelled\'); return false;'; else echo 'return confirm(\'Are you sure you want to cancel this booking?\');'; ?>" 
                     class="inline-block bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded-full font-medium">
                     <i class="fas fa-times"></i> Reject
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="10" class="text-center py-4 text-gray-500">No bookings found</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

</div>
</body>
</html>
