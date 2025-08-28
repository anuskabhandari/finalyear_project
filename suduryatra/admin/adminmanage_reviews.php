<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php"); // Redirect to login
    exit();
}
// Include your DB connection
require_once '../backend/func.php';
$conn = dbConnect();

// Fetch reviews with user and package info
$sql = "SELECT 
  r.review_id, 
  CONCAT(u.first_name, ' ', u.last_name) AS user_fullname, 
   p.title AS package_name, 
  r.rating
FROM review r
LEFT JOIN user u ON r.user_id = u.user_id
LEFT JOIN package p ON r.package_id = p.package_id
ORDER BY r.review_id DESC;
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Reviews</title>
  <link rel="stylesheet" href="/assets/css/dashboard.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
      <a href="manage_bookings.php">
        <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM5 21V8h14v13H5z"/></svg>
        <span>Manage Bookings</span>
      </a>
      <a href="adminmanage_reviews.php" class="active">
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

  <!-- Sidebar Nav Here -->

  <main class="flex-1 p-8">
    <h2 class="text-3xl font-bold text-emerald-900 mb-6">Manage Reviews</h2>

    <div class="bg-white rounded shadow p-6 overflow-x-auto">
      <table class="min-w-full text-left border-collapse">
        <thead>
          <tr class="bg-emerald-100 text-emerald-900">
            <th class="p-3 border-b">Review ID</th>
            <th class="p-3 border-b">User</th>
            <th class="p-3 border-b">Package</th>
            <th class="p-3 border-b">Rating</th>
            <th class="p-3 border-b text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr class="hover:bg-gray-50 transition">
                <td class="p-3 border-b"><?= $row['review_id'] ?></td>
                <td class="p-3 border-b"><?= htmlspecialchars($row['user_fullname'] ?? 'Unknown') ?></td>
                <td class="p-3 border-b"><?= htmlspecialchars($row['package_name'] ?? 'Unknown') ?></td>
                <td class="p-3 border-b"><?= htmlspecialchars($row['rating'] ?? 'N/A') ?></td>
                <td class="p-3 border-b text-center">
                  <a href="view_review.php?id=<?= $row['review_id'] ?>" class="text-blue-600 hover:underline mr-4">
                    <i class="fas fa-eye"></i> View
                  </a>
              
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center py-4 text-gray-500">No reviews found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<script src="/assets/js/hamburgermenu.js"></script>
</body>
</html>

<?php $conn->close(); ?>
