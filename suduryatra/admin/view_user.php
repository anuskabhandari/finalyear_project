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

// Get user_id from query string
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$userId) {
    die("Invalid user ID.");
}

// Fetch user details
$stmt = $con->prepare("SELECT user_id, first_name, last_name, email, role_id FROM user WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("User not found.");
}
$user = $result->fetch_assoc();
$stmt->close();

// Map role_id to role name
$roleNames = [
    1 => 'Admin',
    2 => 'User',
    3 => 'Tour Operator'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>View User - <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></title>
<link rel="stylesheet" href="/assets/css/dashboard.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <div class="container">
    <main class="flex-1 p-8">
      <h2 class="text-3xl font-bold mb-6">User Details</h2>
      
      <div class="bg-white rounded shadow p-6">
        <table class="min-w-full text-left border-collapse">
          <tr>
            <th class="p-3 border-b w-1/3">Full Name</th>
            <td class="p-3 border-b"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
          </tr>
          <tr>
            <th class="p-3 border-b">Email</th>
            <td class="p-3 border-b"><?= htmlspecialchars($user['email']) ?></td>
          </tr>
          <tr>
            <th class="p-3 border-b">Role</th>
            <td class="p-3 border-b"><?= $roleNames[$user['role_id']] ?? 'User' ?></td>
          </tr>
          <tr>
            <th class="p-3 border-b">User ID</th>
            <td class="p-3 border-b"><?= $user['user_id'] ?></td>
          </tr>
        </table>

        <div class="mt-4">
          <a href="manage_users.php" class="px-4 py-2 bg-emerald-500 text-white rounded hover:bg-emerald-600">← Back to Users</a>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
