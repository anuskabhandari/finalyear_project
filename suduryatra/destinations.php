<?php
require_once 'backend/func.php';
$conn = dbConnect();

// Get filter/search/sort inputs
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$placeFilter = isset($_GET['destination']) ? (int)$_GET['destination'] : 0;
$packageFilter = isset($_GET['package']) ? (int)$_GET['package'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';

// Fetch dropdowns
$placesDropdown = $conn->query("SELECT id, name FROM place ORDER BY name ASC");
$packagesDropdown = $conn->query("SELECT package_id, title FROM package ORDER BY title ASC");

// Main query for places
$sqlPlaces = "SELECT p.*, IFNULL(AVG(r.rating),0) AS avg_rating, COUNT(r.review_id) AS review_count
              FROM place p
              LEFT JOIN review r ON p.id = r.place_id
              WHERE 1=1";

if ($search !== '') $sqlPlaces .= " AND p.name LIKE '%$search%'";
if ($placeFilter > 0) $sqlPlaces .= " AND p.id = $placeFilter";
if ($packageFilter > 0) $sqlPlaces .= " AND p.id IN (SELECT place_id FROM package WHERE package_id=$packageFilter)";

$sqlPlaces .= " GROUP BY p.id";
$sqlPlaces .= ($sort === 'most_reviewed') ? " ORDER BY review_count DESC" : " ORDER BY p.id DESC";

$placesResult = $conn->query($sqlPlaces);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Destinations | Tourist Guide</title>
<link rel="stylesheet" href="assets/css/destination.css"/>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<header class="text-center text-3xl font-bold text-emerald-900 p-4">Tourist Guide - Explore Destinations</header>

<div class="container flex gap-6">

  <!-- Sidebar Filters -->
  <aside class="sidebar w-64 p-4 bg-white rounded shadow">
    <form method="GET">
      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search..." class="w-full p-2 border rounded mb-4"/>

      <label for="destination-select" class="font-medium">Destinations</label>
      <select name="destination" id="destination-select" class="w-full p-2 border rounded mb-4">
        <option value="0">All Destinations</option>
        <?php while ($pl = $placesDropdown->fetch_assoc()): ?>
          <option value="<?= $pl['id'] ?>" <?= $placeFilter==$pl['id']?'selected':'' ?>><?= htmlspecialchars($pl['name']) ?></option>
        <?php endwhile; ?>
      </select>

      <label for="package-select" class="font-medium">Packages</label>
      <select name="package" id="package-select" class="w-full p-2 border rounded mb-4">
        <option value="0">All Packages</option>
        <?php while ($pk = $packagesDropdown->fetch_assoc()): ?>
          <option value="<?= $pk['package_id'] ?>" <?= $packageFilter==$pk['package_id']?'selected':'' ?>><?= htmlspecialchars($pk['title']) ?></option>
        <?php endwhile; ?>
      </select>

      <label for="sort-select" class="font-medium">Sort By</label>
      <select name="sort" id="sort-select" class="w-full p-2 border rounded mb-4">
        <option value="latest" <?= $sort=='latest'?'selected':'' ?>>Latest</option>
        <option value="most_reviewed" <?= $sort=='most_reviewed'?'selected':'' ?>>Most Reviewed</option>
      </select>

      <button type="submit" class="w-full bg-emerald-600 text-white p-2 rounded hover:bg-emerald-700 transition">
        Apply S
      </button>
    </form>
  </aside>

  <!-- Main Content -->
  <div class="main-content flex-1">
<div class="mb-4">
  <a href="index.php" class="text-emerald-700 hover:text-emerald-900 font-medium">
    ← Back to Home
  </a>
</div>
    <!-- Places Section -->
    <h2 class="text-2xl font-bold mb-4 text-emerald-900">Places</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php if ($placesResult && $placesResult->num_rows > 0): ?>
        <?php while ($row = $placesResult->fetch_assoc()): ?>
          <div class="destination-card border rounded shadow overflow-hidden">
            <div class="image-container relative">
              <img src="images/<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-full h-48 object-cover"/>
              <a href="details.php?id=<?= $row['id'] ?>" class="absolute bottom-2 right-2 bg-emerald-700 text-white px-3 py-1 rounded hover:bg-emerald-800">View Details</a>
            </div>
            <div class="p-4">
              <h3 class="font-bold text-lg"><?= htmlspecialchars($row['name']) ?></h3>
              <p class="text-gray-700"><?= htmlspecialchars($row['description']) ?></p>
              <p class="font-medium mt-2">Entry Fee: Rs.<?= $row['entry_fee']>0?$row['entry_fee']:'Free' ?></p>
              <?php
$avg = $row['avg_rating'];
$fullStars = floor($avg);
$halfStar = ($avg - $fullStars >= 0.5) ? 1 : 0;
$emptyStars = 5 - $fullStars - $halfStar;
?>
<div class="mt-2 text-yellow-500 flex items-center gap-1">
  <?= str_repeat("★", $fullStars) ?>
  <?= $halfStar ? "⯨" : "" ?> <!-- Half star symbol (replace with icon if you want) -->
  <?= str_repeat("☆", $emptyStars) ?>
  <span class="text-gray-700 ml-2">(<?= $avg ?>/5, <?= $row['review_count'] ?> reviews)</span>
</div>

            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No places found.</p>
      <?php endif; ?>
    </div>

  </div>
</div>
</body>
</html>
