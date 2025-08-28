<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    header('Location: ../login.php');
    exit();
}
require_once '../backend/func.php';
$conn = dbConnect();

$tour_operator_id = $_SESSION['tour_operator_id'] ?? 0;

// Get package_id from URL
$package_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch package details
$stmt = $conn->prepare("SELECT * FROM package WHERE package_id = ? AND operator_id = ?");
$stmt->bind_param("ii", $package_id, $tour_operator_id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();

if (!$package) {
    die("Package not found or you don't have permission to edit it.");
}

// Handle update form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $place_id = $_POST['place_id'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $slots = $_POST['available_slots'];
    $description = $_POST['description'];

    $update = $conn->prepare("UPDATE package 
                              SET title=?, place_id=?, price=?, duration=?, available_slots=?, description=?, updated_at=NOW() 
                              WHERE package_id=? AND operator_id=?");
    $update->bind_param("sidiisii", $title, $place_id, $price, $duration, $slots, $description, $package_id, $tour_operator_id);

    if ($update->execute()) {
        $message = "<p class='text-green-600 font-medium'>✅ Package updated successfully!</p>";
        // refresh package data
        $stmt->execute();
        $package = $stmt->get_result()->fetch_assoc();
    } else {
        $message = "<p class='text-red-600 font-medium'>❌ Failed to update package!</p>";
    }
}

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
  <meta charset="UTF-8">
  <title>Edit Package</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
  <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-6 text-emerald-800">Edit Package</h2>
    <?= $message ?>
    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block mb-1 font-medium">Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($package['title']) ?>" class="w-full p-3 border rounded" required>
      </div>
      <div>
        <label class="block mb-1 font-medium">Place</label>
        <select name="place_id" class="w-full p-3 border rounded" required>
          <?php foreach($places as $p): ?>
            <option value="<?= $p['id'] ?>" <?= $p['id']==$package['place_id'] ? "selected" : "" ?>>
              <?= htmlspecialchars($p['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block mb-1 font-medium">Price (NPR)</label>
        <input type="number" step="0.01" name="price" value="<?= $package['price'] ?>" class="w-full p-3 border rounded" required>
      </div>
      <div>
        <label class="block mb-1 font-medium">Duration (days)</label>
        <input type="number" name="duration" value="<?= $package['duration'] ?>" class="w-full p-3 border rounded" required>
      </div>
      <div>
        <label class="block mb-1 font-medium">Available Slots</label>
        <input type="number" name="available_slots" value="<?= $package['available_slots'] ?>" class="w-full p-3 border rounded" required>
      </div>
      <div class="md:col-span-2">
        <label class="block mb-1 font-medium">Description</label>
        <textarea name="description" rows="4" class="w-full p-3 border rounded"><?= htmlspecialchars($package['description']) ?></textarea>
      </div>
      <div class="md:col-span-2 text-right">
        <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-2 rounded shadow-md">
          <i class="fas fa-check-circle mr-2"></i> Update Package
        </button>
      </div>
    </form>
    <a href="my_packages.php" class="mt-4 inline-block text-blue-600 hover:underline">← Back to My Packages</a>
  </div>
</body>
</html>
