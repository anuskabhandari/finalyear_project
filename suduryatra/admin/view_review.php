<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: ../login.php"); 
    exit();
}
require_once '../backend/func.php';
$conn = dbConnect();

$reviewId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$reviewId) {
    die("No review ID specified.");
}

// Fetch review with user and package info
$stmt = $conn->prepare("
    SELECT r.review_id, r.rating, r.comment, r.created_at,
           CONCAT(u.first_name, ' ', u.last_name) AS user_fullname,
           p.title AS package_name
    FROM review r
    LEFT JOIN user u ON r.user_id = u.user_id
    LEFT JOIN package p ON r.package_id = p.package_id
    WHERE r.review_id = ?
");
$stmt->bind_param("i", $reviewId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Review not found.");
}
$review = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Review</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center p-6">
  <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
    
    <h2 class="text-3xl font-bold text-emerald-800 mb-6 border-b-2 border-emerald-500 pb-3">
      Review Details
    </h2>

    <div class="space-y-4 text-gray-700">
      <p><span class="font-semibold">Review ID:</span> <span class="text-gray-900"><?= $review['review_id'] ?></span></p>
      
      <p><span class="font-semibold">User:</span> <span class="text-gray-900"><?= htmlspecialchars($review['user_fullname']) ?></span></p>
      
      <p><span class="font-semibold">Package:</span> <span class="text-gray-900"><?= htmlspecialchars($review['package_name'] ?? '') ?></span></p>
      
      <p><span class="font-semibold">Rating:</span> 
        <span class="text-yellow-500 font-bold">
          <?= htmlspecialchars($review['rating']) ?> / 5 ⭐
        </span>
      </p>
      
      <div>
        <span class="font-semibold">Comment:</span>
        <div class="mt-2 bg-gray-50 p-4 rounded-lg border border-gray-200 text-gray-800">
          <?= nl2br(htmlspecialchars($review['comment'])) ?>
        </div>
      </div>
      
      <p class="text-sm text-gray-500">Created At: <?= $review['created_at'] ?></p>
    </div>

    <div class="mt-8 flex justify-end">
      <a href="adminmanage_reviews.php" 
         class="px-5 py-2 bg-emerald-600 text-white font-medium rounded-lg shadow hover:bg-emerald-700 transition">
        ← Back to Reviews
      </a>
    </div>
  </div>
</div>

</body>
</html>
