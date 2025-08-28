<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php"); // Redirect to login
    exit();
}
require_once '../backend/func.php';
$conn = dbConnect();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Packages</title>
<link rel="stylesheet" href="/assets/css/dashboard.css"/>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
  <nav class="sidebar" id="sidebar">
    <div class="logo">Admin</div>
    <div class="nav-links">
      <a href="admin_dashboard.php">
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
      <a href="manage_packages.php" class="active">
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
      <a href="manage_touroperator.php">
        <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2 0-6 1-6 3v2h12v-2c0-2-4-3-6-3zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 2.06 1.97 3.45v2h6v-2c0-2-4-3-6-3z"/></svg>
        <span>Manage Operators</span>
      </a>
      <a href="../logut.php" onclick="return confirm('Are you sure you want to logout?')">
        <svg viewBox="0 0 24 24"><path d="M16 13v-2H7V8l-5 4 5 4v-3h9zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
        <span>Logout</span>
      </a>
    </div>
    <button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">&#9776;</button>
  </nav>

  <!-- Sidebar code here (if any) -->

  <main class="flex-1 p-10">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-bold text-emerald-900">All Travel Packages</h2>
      <a href="add_package.php" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">Add New Package</a>
    </div>

    <div class="bg-white rounded shadow p-6 overflow-x-auto">
      <table class="min-w-full text-left text-sm">
        <thead class="bg-emerald-100 text-emerald-900">
          <tr>
            <th class="p-3">Package Name</th>
            <th class="p-3">Location</th>
            <th class="p-3">Price</th>
            <th class="p-3">Duration</th>
            <th class="p-3">Operator</th>
            <th class="p-3 text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Join package with place and user to get location and operator
          $sql = "
          SELECT 
              p.package_id, p.title, p.price, p.duration,
              pl.name AS location,                       
              CONCAT(u.first_name, ' ', u.last_name) AS operator 
          FROM package p
          LEFT JOIN place pl ON p.place_id = pl.id
          LEFT JOIN user u ON p.operator_id = u.user_id
          ORDER BY p.package_id DESC
          ";
          
          $result = $conn->query($sql);

          if($result->num_rows > 0){
              while($row = $result->fetch_assoc()){
                  echo "<tr class='hover:bg-gray-50 border-t'>";
                  echo "<td class='p-3'>{$row['title']}</td>";
                  echo "<td class='p-3'>{$row['location']}</td>";
                  echo "<td class='p-3'>Rs. {$row['price']}</td>";
                  echo "<td class='p-3'>{$row['duration']}</td>";
                  echo "<td class='p-3'>{$row['operator']}</td>";
                  echo "<td class='p-3 text-center'>
                          <a href='edit_package.php?id={$row['package_id']}' class='text-blue-600 hover:text-blue-800 mr-3'><i class='fas fa-edit'></i> Edit</a>
                          <a href='delete_package.php?id={$row['package_id']}' class='text-red-600 hover:text-red-800'><i class='fas fa-trash'></i> Delete</a>
                        </td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='6' class='p-3 text-center'>No packages found.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>
