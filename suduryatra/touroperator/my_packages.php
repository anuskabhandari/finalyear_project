<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    header('Location: ../login.php');
    exit();
}
require_once '../backend/func.php';
$conn = dbConnect();


$tour_operator_id = $_SESSION['tour_operator_id'] ?? 0;
$message = "";

// Handle Add Package Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $place_id = $_POST['place_id'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $slots = $_POST['available_slots'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO package (title, place_id, price, duration, available_slots, description, operator_id) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sidiisi", $title, $place_id, $price, $duration, $slots, $description, $tour_operator_id);

    if ($stmt->execute()) {
        $message = "<p class='mt-4 text-green-600 font-medium'>✅ Package added successfully!</p>";
    } else {
        $message = "<p class='mt-4 text-red-600 font-medium'>❌ Failed to add package. Please try again.</p>";
    }
}

// Fetch packages
$sql = "SELECT p.*, pl.name AS place_name 
        FROM package AS p
        LEFT JOIN place AS pl ON p.place_id = pl.id
        WHERE p.operator_id = ? 
        ORDER BY p.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tour_operator_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all places for dropdown
$places_result = $conn->query("SELECT id, name FROM place ORDER BY name");
$places = [];
while($p = $places_result->fetch_assoc()){
    $places[] = $p;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Tour Operator Dashboard</title>
<link rel="stylesheet" href="../../assets/css/dashboard.css"/>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</head>
<body>
<div class="container">
  <nav class="sidebar" id="sidebar">
    <div class="logo">TourOperator</div>
    <div class="nav-links">
      <a href="#">
        <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        <span>Dashboard</span>
      </a>
      <a href="my_packages.php" class="active">
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

    <!-- Success / Error Message -->
    <?= $message ?>

    <!-- Package Table -->
    <div class="bg-white rounded-xl shadow p-6 overflow-x-auto">
      <table class="w-full text-sm text-left text-gray-700">
        <thead class="bg-emerald-100 text-emerald-900 uppercase text-sm">
          <tr>
            <th class="p-4 border-b">Title</th>
            <th class="p-4 border-b">Place</th>
            <th class="p-4 border-b">Price</th>
            <th class="p-4 border-b">Duration (days)</th>
            <th class="p-4 border-b">Slots</th>
            <th class="p-4 border-b text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
          <tr class="border-t hover:bg-gray-50 transition">
            <td class="p-4"><?= htmlspecialchars($row['title']) ?></td>
            <td class="p-4"><?= htmlspecialchars($row['place_name'] ?? 'N/A') ?></td>
            <td class="p-4">Rs. <?= number_format($row['price'], 2) ?></td>
            <td class="p-4"><?= htmlspecialchars($row['duration']) ?></td>
            <td class="p-4"><?= htmlspecialchars($row['available_slots']) ?></td>
            <td class="p-4 text-center">
              <a href="edit_package.php?id=<?= $row['package_id'] ?>" class="text-blue-600 hover:text-blue-800 font-medium mr-4">
    <i class="fas fa-pen"></i> Edit
</a>
<a href="delete_package.php?id=<?= $row['package_id'] ?>" class="text-red-600 hover:text-red-800 font-medium">
    <i class="fas fa-trash"></i> Delete
</a>

            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Add Package Form -->
    <div id="add-package-form" class="mt-12 bg-white rounded-xl shadow p-8">
      <h3 class="text-2xl font-bold mb-6 text-emerald-800">Add a New Package</h3>
      <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block mb-1 font-medium">Title</label>
          <input type="text" name="title" class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-emerald-500" required />
        </div>
        <div>
          <label class="block mb-1 font-medium">Place</label>
          <select name="place_id" class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            <option value="">Choose Place</option>
            <?php foreach($places as $p): ?>
              <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block mb-1 font-medium">Price (NPR)</label>
          <input type="number" step="0.01" name="price" class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-emerald-500" required />
        </div>
        <div>
          <label class="block mb-1 font-medium">Duration (days)</label>
          <input type="number" name="duration" class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-emerald-500" required />
        </div>
        <div>
          <label class="block mb-1 font-medium">Available Slots</label>
          <input type="number" name="available_slots" class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-emerald-500" required />
        </div>
        <div class="md:col-span-2">
          <label class="block mb-1 font-medium">Description</label>
          <textarea name="description" class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-emerald-500" rows="4"></textarea>
        </div>
        <div class="md:col-span-2 text-right">
          <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-2 rounded shadow-md">
            <i class="fas fa-check-circle mr-2"></i> Submit Package
          </button>
        </div>
      </form>
    </div>
  </main>
</div>

<script src="../../assets/js/hamburgermenu.js"></script>
</body>
</html>
