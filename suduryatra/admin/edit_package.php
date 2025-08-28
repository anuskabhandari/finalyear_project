<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php"); // Redirect to login
    exit();
}
require_once '../backend/func.php';
$conn = dbConnect();

// 1. Check if ID is provided
if (!isset($_GET['id'])) {
    die("No package ID provided.");
}

$id = intval($_GET['id']);

// 2. Fetch package data
$result = $conn->query("SELECT * FROM package WHERE package_id = $id");
$package = $result->fetch_assoc();

if (!$package) {
    die("Package not found.");
}

// 3. Fetch all places for the dropdown
$places = $conn->query("SELECT id, name FROM place");

// 4. Handle form submission
if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $place_id = $_POST['place_id'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $operator_id = $_POST['operator_id']; // assuming you store operator ID

    $stmt = $conn->prepare("UPDATE package SET title=?, place_id=?, price=?, duration=?, operator_id=? WHERE package_id=?");
    $stmt->bind_param("sidiii", $title, $place_id, $price, $duration, $operator_id, $id);
    $stmt->execute();

    header("Location: manage_packages.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Package</title>
<link rel="stylesheet" href="../assets/css/dashboard.css"/>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="container" style="display:flex;">
  <!-- Sidebar -->
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
      <a href="../logout.php" onclick="return confirm('Are you sure you want to logout?')">
        <svg viewBox="0 0 24 24"><path d="M16 13v-2H7V8l-5 4 5 4v-3h9zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
        <span>Logout</span>
      </a>
    </div>
    <button class="toggle-btn" id="toggleBtn">&#9776;</button>
  </nav>

  <h2 class="text-2xl font-bold mb-6">Edit Package</h2>
  <form method="POST" class="space-y-4 max-w-lg">
    <input type="text" name="title" value="<?= htmlspecialchars($package['title']) ?>" required class="w-full p-2 border rounded">
    
    <select name="place_id" required class="w-full p-2 border rounded">
        <option value="">Select Destination</option>
        <?php while($row = $places->fetch_assoc()){ ?>
            <option value="<?= $row['id'] ?>" <?= $row['id'] == $package['place_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['name']) ?>
            </option>
        <?php } ?>
    </select>

    <input type="number" name="price" value="<?= htmlspecialchars($package['price']) ?>" required class="w-full p-2 border rounded">
    <input type="text" name="duration" value="<?= htmlspecialchars($package['duration']) ?>" required class="w-full p-2 border rounded">
    
    <input type="number" name="operator_id" value="<?= htmlspecialchars($package['operator_id']) ?>" required class="w-full p-2 border rounded">

    <button type="submit" name="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Package</button>
  </form>
</div>
</body>
</html>
