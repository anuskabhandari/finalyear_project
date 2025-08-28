<?php
session_start();
require_once 'backend/func.php';
$conn = dbConnect();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all bookings of this user
$sql = "
    SELECT b.id, b.name, b.email, b.destination, b.date, b.to_date, 
           b.people, b.total_price, p.title AS package_name, pl.name AS place_name
    FROM bookings b
    JOIN package p ON b.package_id = p.package_id
    JOIN place pl ON b.destination = pl.id
    WHERE b.user_id = ?
    ORDER BY b.date DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Bookings - Sudur Yatra</title>
<style>
body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f4f4f4; }
header { background: linear-gradient(to right, #00796b, #43a047); padding: 20px; text-align: center; color: white; font-size: 26px; }
.container { max-width: 900px; margin: 40px auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
h2 { text-align: center; color: #00796b; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
table, th, td { border: 1px solid #ddd; }
th, td { padding: 10px; text-align: center; }
th { background-color: #00796b; color: white; }
tr:nth-child(even) { background-color: #f9f9f9; }
.back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #00796b; font-weight: bold; }
.back-link:hover { text-decoration: underline; }
</style>
</head>
<body>
<header>My Bookings</header>
<div class="container">
<h2>Your Booking Summary</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Place</th>
        <th>Package</th>
        <th>From</th>
        <th>To</th>
        <th>People</th>
        <th>Total Price (NPR)</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['place_name']) ?></td>
        <td><?= htmlspecialchars($row['package_name']) ?></td>
        <td><?= $row['date'] ?></td>
        <td><?= $row['to_date'] ?></td>
        <td><?= $row['people'] ?></td>
        <td><?= number_format($row['total_price'], 2) ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>
</body>
</html>
