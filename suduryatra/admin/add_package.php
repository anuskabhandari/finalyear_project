<?php
session_start();
require_once '../backend/func.php';
$conn = dbConnect();

// Show PHP errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Fetch all places
$places_result = $conn->query("SELECT id, name FROM place");

// Fetch all operators (role_id = 3)
$operators_result = $conn->query("SELECT user_id, CONCAT(first_name,' ',last_name) AS fullname FROM user WHERE role_id = 3");

$error = null;

// Handle form submission
if (isset($_POST['submit'])) {
    // --- sanitize/normalize ---
    $title = trim($_POST['title'] ?? '');
    $place_id = intval($_POST['place_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $duration = intval($_POST['duration'] ?? 0);
    $places_covered = trim($_POST['places_covered'] ?? '');
    $status = $_POST['status'] ?? 'active';
    $available_slots = intval($_POST['available_slots'] ?? 0);
    $operator_id = intval($_POST['operator_id'] ?? 0);

    if ($title === '' || $place_id <= 0 || $operator_id <= 0 || $duration <= 0 || $price < 0) {
        $error = "Please fill all required fields correctly.";
    } else {
        // ---- 1) PRE-CHECK: prevent same title for the same place ----
        $check = $conn->prepare("SELECT 1 FROM package WHERE place_id = ? AND title = ? LIMIT 1");
        $check->bind_param("is", $place_id, $title);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "A package named '{$title}' already exists for the selected place.";
        }
        $check->close();

        if (!$error) {
            // ---- Insert attempt ----
            $stmt = $conn->prepare("
                INSERT INTO package 
                (title, place_id, description, price, duration, places_covered, status, available_slots, operator_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            // types: s i s d i s s i i
            $stmt->bind_param(
                "sisdsisii",
                $title,
                $place_id,
                $description,
                $price,
                $duration,
                $places_covered,
                $status,
                $available_slots,
                $operator_id
            );

            if ($stmt->execute()) {
                header("Location: manage_packages.php");
                exit;
            } else {
                // ---- 2) CATCH DUPLICATE KEY (DB-level unique index) ----
                if ($stmt->errno == 1062) {
                    $error = "Duplicate: A package with this name already exists for this place.";
                } else {
                    $error = "Error adding package: " . $stmt->error;
                }
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add Package</title>
<link rel="stylesheet" href="../assets/css/dashboard.css"/>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="container flex">
    <!-- Sidebar -->
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
      <a href="../logut.php" onclick="return confirm('Are you sure you want to logout?')">
        <svg viewBox="0 0 24 24"><path d="M16 13v-2H7V8l-5 4 5 4v-3h9zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
        <span>Logout</span>
      </a>
    </div>
    <button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">&#9776;</button>
  </nav>

    <!-- Main Content -->
    <main class="main-content w-full p-8 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Add New Package</h2>
            <?php if($error) echo "<p class='text-red-500 mb-4'>".htmlspecialchars($error)."</p>"; ?>

            <form method="POST" class="space-y-5" autocomplete="off">
                <div>
                    <label>Package Name</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div>
                    <label>Place</label>
                    <select name="place_id" required class="w-full px-4 py-2 border rounded-lg">
                        <option value="">Select Place</option>
                        <?php while($row = $places_result->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label>Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" required class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label>Duration (days)</label>
                        <input type="number" name="duration" required class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>

                <div>
                    <label>Places Covered</label>
                    <input type="text" name="places_covered" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div>
                    <label>Available Slots</label>
                    <input type="number" name="available_slots" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div>
                    <label>Status</label>
                    <select name="status" class="w-full px-4 py-2 border rounded-lg">
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div>
                    <label>Operator</label>
                    <select name="operator_id" required class="w-full px-4 py-2 border rounded-lg">
                        <option value="">Select Operator</option>
                        <?php while($op = $operators_result->fetch_assoc()): ?>
                            <option value="<?= $op['user_id'] ?>"><?= htmlspecialchars($op['fullname']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <button type="submit" name="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700">
                        ➕ Add Package
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
<script src="../assets/js/hamburgermenu.js"></script>
</body>
</html>
