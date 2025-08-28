<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit();
}

require_once '../backend/func.php';
$con = dbConnect();
if (!$con) die("Database connection failed");

// Map role_id to role name
$roleNames = [
    1 => 'Admin',
    2 => 'User',
    3 => 'Tour Operator'
];

// ----------------- Pagination Setup -----------------
$limit = 10; // users per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Fetch total number of users
$totalResult = $con->query("SELECT COUNT(*) as total FROM user");
$totalRow = $totalResult->fetch_assoc();
$totalUsers = $totalRow['total'];
$totalPages = ceil($totalUsers / $limit);

// Fetch users for current page
$query = "SELECT user_id, first_name, last_name, email, role_id 
          FROM user 
          ORDER BY user_id DESC 
          LIMIT $limit OFFSET $offset";
$result = $con->query($query);
if (!$result) die("Query failed: " . $con->error);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard - Manage Users</title>
<link rel="stylesheet" href="/assets/css/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>
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
      <a href="manage_users.php" class="active">
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
  <main class="flex-1 p-8">
    <h2 class="text-3xl font-bold mb-6">Manage Users</h2>

    <div class="bg-white rounded shadow p-6 overflow-x-auto">
      <table class="min-w-full text-left border-collapse">
        <thead>
          <tr class="bg-emerald-100 text-emerald-900">
            <th class="p-3 border-b">Full Name</th>
            <th class="p-3 border-b">Email</th>
            <th class="p-3 border-b">Role</th>
            <th class="p-3 border-b text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr class="hover:bg-gray-50 transition">
                <td class="p-3 border-b"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td class="p-3 border-b"><?= htmlspecialchars($row['email']) ?></td>
                <td class="p-3 border-b"><?= $roleNames[$row['role_id']] ?? 'User' ?></td>
                <td class="p-3 border-b text-center">
                  <a href="view_user.php?id=<?= $row['user_id'] ?>" 
                     class="text-blue-600 hover:underline">
                    <i class="fa-solid fa-eye"></i> View
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="4" class="p-3 text-center">No users found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-4 flex justify-center gap-2">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page-1 ?>" class="px-3 py-1 border rounded bg-white hover:bg-emerald-200">&laquo; Prev</a>
        <?php endif; ?>
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
          <a href="?page=<?= $p ?>" class="px-3 py-1 border rounded <?= $p == $page ? 'bg-emerald-500 text-white' : 'bg-white hover:bg-emerald-200' ?>"><?= $p ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page+1 ?>" class="px-3 py-1 border rounded bg-white hover:bg-emerald-200">Next &raquo;</a>
        <?php endif; ?>
      </div>
    </div>
  </main>
</div>
<script src="/assets/js/hamburgermenu.js"></script>
</body>
</html>
