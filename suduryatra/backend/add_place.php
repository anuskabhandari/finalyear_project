<?php
require 'func.php';
$conn = dbConnect();

// Collect form data
$place_id    = intval($_POST['place_id'] ?? 0); // 0 = new
$name        = $conn->real_escape_string($_POST['name']);
$province_id = intval($_POST['province'] ?? 0);
$district_id = intval($_POST['district'] ?? 0);
$municipality_id = intval($_POST['municipality'] ?? 0);
$description = $conn->real_escape_string($_POST['description']);
$latitude    = $_POST['lat'];
$longitude   = $_POST['lng'];
$address     = $conn->real_escape_string($_POST['address'] ?? '');

// Handle image upload
$image_path = '';
if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image_path = 'place_'.time().'.'.$ext;
    move_uploaded_file($_FILES['image']['tmp_name'], '../images/'.$image_path);
}

// Validation
if(!$name || !$province_id || !$district_id || !$municipality_id){
    die("Please fill all required fields.");
}

// Add or Update
if($place_id > 0){
    // Update existing place
    if($image_path){
        $stmt = $conn->prepare("UPDATE place SET name=?, province_id=?, district_id=?, municipality_id=?, description=?, latitude=?, longitude=?, address=?, image_path=? WHERE id=?");
        $stmt->bind_param("siiiissssi", $name, $province_id, $district_id, $municipality_id, $description, $latitude, $longitude, $address, $image_path, $place_id);
    } else {
        $stmt = $conn->prepare("UPDATE place SET name=?, province_id=?, district_id=?, municipality_id=?, description=?, latitude=?, longitude=?, address=? WHERE id=?");
        $stmt->bind_param("siiiisssi", $name, $province_id, $district_id, $municipality_id, $description, $latitude, $longitude, $address, $place_id);
    }
    $stmt->execute();
} else {
    // Insert new place (keep as-is)
}

header("Location: ../admin/manage_places.php");
exit();
?>
