<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    header('Location: ../login.php');
    exit();
}

// Connect to database
require_once '../backend/func.php';
$conn = dbConnect();

// Logged in operator ID
$operator_id = $_SESSION['user_id'];

// Fetch bookings belonging to this operator's packages
    $sql = "
    SELECT 
        b.id AS booking_id,
        b.name AS customer_name,   -- use name from bookings
        b.email AS customer_email, -- use email from bookings
        p.title AS package_name,
        b.booking_date,
        b.date AS travel_date,
        b.to_date AS travel_end,
        b.status
    FROM bookings b
    JOIN package p ON b.package_id = p.package_id
    WHERE p.operator_id = ?
    ORDER BY b.booking_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $operator_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Bookings Received</title>
<link rel="stylesheet" href="../../assets/css/dashboard.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="container">
  <!-- Sidebar -->
  <nav class="sidebar" id="sidebar">
    <div class="logo">TourOperator</div>
    <div class="nav-links">
      <a href="tour_operatordashboard.php">
        <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        <span>Dashboard</span>
      </a>
      <a href="my_packages.php">
        <svg viewBox="0 0 24 24"><path d="M20 8V6h-4V4h-4v2H4v2H2v4h2v2h4v2h4v-2h4v-2h2v-4h-2z"/></svg>
        <span>My Packages</span>
      </a>
      <a href="bookings_received.php" class="active">
        <svg viewBox="0 0 24 24"><path d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM5 21V8h14v13H5z"/></svg>
        <span>Bookings Received</span>
      </a>
      <a href="profile_settings.php">
        <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        <span>Profile Settings</span>
      </a>
      <a href="../logout.php" onclick="return confirm('Are you sure you want to logout?')">
        <svg viewBox="0 0 24 24"><path d="M16 13v-2H7V8l-5 4 5 4v-3h9zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
        <span>Logout</span>
      </a>
    </div>
    <button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">&#9776;</button>
  </nav>

  <!-- Main -->
  <main class="flex-1 p-8">
    <h2 class="text-3xl font-bold text-emerald-900 mb-6">Bookings Received</h2>

    <div class="bg-white rounded-xl shadow p-6 overflow-x-auto">
      <table class="w-full text-sm text-left text-gray-700">
        <thead class="bg-emerald-100 text-emerald-900 uppercase text-sm">
          <tr>
            <th class="p-4 border-b">Customer Name</th>
            <th class="p-4 border-b">Package</th>
            <th class="p-4 border-b">Booking Date</th>
            <th class="p-4 border-b">Travel Start</th>
            <th class="p-4 border-b">Travel End</th>
            <th class="p-4 border-b">Status</th>
            <th class="p-4 border-b text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
              <?php
                $status = $row['status'];
                $color = $status == 'Pending' ? 'yellow' : ($status == 'Approved' ? 'green' : 'red');
              ?>
              <tr class="border-t hover:bg-gray-50 transition">
                <td class="p-4"><?= htmlspecialchars($row['customer_name']) ?></td>
                <td class="p-4"><?= htmlspecialchars($row['package_name']) ?></td>
                <td class="p-4"><?= htmlspecialchars($row['booking_date']) ?></td>
<td class="p-4"><?= htmlspecialchars($row['travel_date']) ?></td>
<td class="p-4"><?= htmlspecialchars($row['travel_end']) ?></td>

                <td class="p-4">
                  <span class="bg-<?= $color ?>-100 text-<?= $color ?>-800 px-3 py-1 rounded-full text-xs">
                    <?= htmlspecialchars($status) ?>
                  </span>
                </td>
                <td class="p-4 text-center">
                  <?php if($status == 'Pending'): ?>
                    <a href="approve_bookings.php?id=<?= $row['booking_id'] ?>" class="bg-green-100 text-green-800 px-3 py-1 rounded hover:bg-green-200 mr-2">
                      <i class="fas fa-check-circle"></i> Approve
                    </a>
                    <a href="delete_bookings.php?id=<?= $row['booking_id'] ?>" class="bg-red-100 text-red-800 px-3 py-1 rounded hover:bg-red-200">
                      <i class="fas fa-times-circle"></i> Reject
                    </a>
                  <?php else: ?>
                    <button class="bg-gray-100 text-gray-500 px-3 py-1 rounded cursor-not-allowed mr-2">
                      <i class="fas fa-check-circle"></i> Approve
                    </button>
                    <button class="bg-gray-100 text-gray-500 px-3 py-1 rounded cursor-not-allowed">
                      <i class="fas fa-times-circle"></i> Reject
                    </button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-gray-600 py-6">No bookings received yet.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<script src="../../assets/js/hamburgermenu.js"></script>
</body>
</html>
