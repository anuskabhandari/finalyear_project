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

// Check if ID is valid
if ($package_id <= 0) {
    $_SESSION['msg'] = "❌ No package selected.";
    header("Location: my_packages.php");
    exit;
}

// Verify package belongs to this operator
$check = $conn->prepare("SELECT * FROM package WHERE package_id = ? AND operator_id = ?");
$check->bind_param("ii", $package_id, $tour_operator_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    $_SESSION['msg'] = "❌ You cannot delete this package.";
    header("Location: my_packages.php");
    exit;
}

// Delete package
$delete = $conn->prepare("DELETE FROM package WHERE package_id = ? AND operator_id = ?");
$delete->bind_param("ii", $package_id, $tour_operator_id);

if ($delete->execute()) {
    $_SESSION['msg'] = "✅ Package deleted successfully!";
} else {
    $_SESSION['msg'] = "❌ Failed to delete package.";
}

header("Location: my_packages.php");
exit;
?>
