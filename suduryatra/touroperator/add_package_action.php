<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    header('Location: ../login.php');
    exit();
}
require_once '../backend/func.php';
$conn = dbConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $places_covered = $_POST['places_covered'];
    $available_slots = $_POST['available_slots'];
    $place_id = $_POST['place_id'];
    $created_by = 1; // TODO: get from session (tour operator id)
    $operator_id = 1; // same as above

    $stmt = $conn->prepare("INSERT INTO package 
        (created_by, place_id, title, description, price, duration, places_covered, operator_id, available_slots, status, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW(), NOW())");

    $stmt->bind_param("iissdisii", $created_by, $place_id, $title, $description, $price, $duration, $places_covered, $operator_id, $available_slots);

    if ($stmt->execute()) {
        header("Location: my_packages.php?success=1");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
