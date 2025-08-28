<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    header('Location: ../login.php');
    exit();
}
require_once '../backend/func.php'; 
$conn = dbConnect();  
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$tourOperatorId = $_SESSION['user_id'];

// -------------------- PROFILE UPDATE --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $agency = trim($_POST['agency']);
    $address = trim($_POST['address']);
    $profileImage = $_FILES['profile_image']['name'] ?? '';

    // Handle profile image upload
    if (!empty($_FILES['profile_image']['name']) && $_FILES['profile_image']['error'] === 0) {
        $targetDir = "../../suduryatra/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $filename = time() . "_" . basename($_FILES['profile_image']['name']);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
            $profileImage = $filename;
        } else {
            $profileError = "❌ Failed to upload image.";
        }
    } else {
        // Keep old image if no new image uploaded
        $stmtOld = $conn->prepare("SELECT profile_image FROM operators WHERE operator_id=?");
        $stmtOld->bind_param("i", $tourOperatorId);
        $stmtOld->execute();
        $resOld = $stmtOld->get_result()->fetch_assoc();
        $profileImage = $resOld['profile_image'] ?? 'operator-default.png';
        $stmtOld->close();
    }

    // Update operator info
    $stmtUpdate = $conn->prepare("UPDATE operators SET fullname=?, email=?, phone=?, agency=?, address=?, profile_image=? WHERE operator_id=?");
    $stmtUpdate->bind_param("ssssssi", $fullname, $email, $phone, $agency, $address, $profileImage, $tourOperatorId);

    if ($stmtUpdate->execute()) {
        $_SESSION['profileSuccess'] = "✅ Profile updated successfully!";
        header("Location: profile_settings.php");
        exit;
    } else {
        $profileError = "❌ Update failed: " . $stmtUpdate->error;
    }
    $stmtUpdate->close();
}

    $operator = [
        'fullname' => '',
        'email' => '',
        'phone' => '',
        'agency' => '',
        'address' => '',
        'profile_image' => 'operator-default.png',
        'password' => ''
    ];


// -------------------- DISPLAY SUCCESS MESSAGE --------------------
$profileSuccess = $_SESSION['profileSuccess'] ?? '';
unset($_SESSION['profileSuccess']);

// -------------------- PASSWORD CHANGE --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $dbPassword = $operator['password'];
    $isValid = false;

    if (!empty($dbPassword) && password_verify($old_password, $dbPassword)) {
        $isValid = true;
    } elseif ($old_password === $dbPassword) {
        $isValid = true;
        $new_hashed = password_hash($dbPassword, PASSWORD_DEFAULT);
        $stmtHash = $conn->prepare("UPDATE operators SET password=? WHERE operator_id=?");
        $stmtHash->bind_param("si", $new_hashed, $tourOperatorId);
        $stmtHash->execute();
        $stmtHash->close();
    }

    if (!$isValid) {
        $passwordError = "❌ Current password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $passwordError = "❌ New password and confirm password do not match.";
    } else {
        $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmtNew = $conn->prepare("UPDATE operators SET password=? WHERE operator_id=?");
        $stmtNew->bind_param("si", $new_hashed, $tourOperatorId);
        if ($stmtNew->execute()) {
            $passwordSuccess = "✅ Password updated successfully!";
        } else {
            $passwordError = "❌ Password update failed: " . $stmtNew->error;
        }
        $stmtNew->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Profile Settings - Tour Operator</title>
<link rel="stylesheet" href="../../assets/css/dashboard.css">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100">
<div class="container">

    <!-- Sidebar -->
    <nav class="sidebar w-64 bg-white h-screen p-6">
        <div class="logo font-bold text-xl mb-8">TourOperator</div>
        <div class="nav-links flex flex-col gap-4">
            <a href="tour_operatordashboard.php" class="flex items-center gap-2"><i class="fas fa-home"></i> Dashboard</a>
            <a href="my_packages.php" class="flex items-center gap-2"><i class="fas fa-box"></i> My Packages</a>
            <a href="bookings_received.php" class="flex items-center gap-2"><i class="fas fa-book"></i> Bookings Received</a>
            <a href="profile_settings.php" class="flex items-center gap-2 bg-emerald-700 text-white p-2 rounded"><i class="fas fa-user-cog"></i> Profile Settings</a>
            <a href="../logut.php" onclick="return confirm('Are you sure you want to logout?')">
                <svg viewBox="0 0 24 24"><path d="M16 13v-2H7V8l-5 4 5 4v-3h9zM20 3h-8v2h8v14h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                <span>Logout</span>
            </a>
        </div>
    </nav>

    <!-- Main content -->
    <main class="flex-1 p-10">
        <h2 class="text-3xl font-bold text-emerald-900 mb-8">Profile Settings</h2>

        <!-- Profile Update -->
        <div class="bg-white rounded-xl shadow p-6 mb-12 md:flex md:gap-8">
            <form method="POST" enctype="multipart/form-data" class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                <input type="hidden" name="update_profile" value="1">

                <?php if(isset($profileSuccess)) echo "<p class='col-span-2 text-green-600'>$profileSuccess</p>"; ?>
                <?php if(isset($profileError)) echo "<p class='col-span-2 text-red-600'>$profileError</p>"; ?>

                <div class="mb-6 md:mb-0">
                    <img id="previewImg" src="../../suduryatra/<?= htmlspecialchars($operator['profile_image'] ?? 'operator-default.png') ?>" class="w-32 h-32 object-cover border-2 border-emerald-600 mb-4">
                    <input type="file" name="profile_image" class="block w-full text-sm" onchange="previewImage(event)">
                </div>

                <div>
                    <label class="block font-medium mb-1">Full Name</label>
                    <input type="text" name="fullname" value="<?= htmlspecialchars($operator['fullname'] ?? '') ?>" class="w-full p-3 border rounded" required>
                </div>
                <div>
                    <label class="block font-medium mb-1">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($operator['email'] ?? '') ?>" class="w-full p-3 border rounded" required>
                </div>
                <div>
                    <label class="block font-medium mb-1">Phone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($operator['phone'] ?? '') ?>" class="w-full p-3 border rounded">
                </div>
                <div>
                    <label class="block font-medium mb-1">Agency/Company</label>
                    <input type="text" name="agency" value="<?= htmlspecialchars($operator['agency'] ?? '') ?>" class="w-full p-3 border rounded">
                </div>
                <div class="md:col-span-2">
                    <label class="block font-medium mb-1">Address</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($operator['address'] ?? '') ?>" class="w-full p-3 border rounded">
                </div>
                <div class="md:col-span-2 text-right">
                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-2 rounded shadow">Save Changes</button>
                </div>
            </form>
        </div>

<script src="../../assets/js/hamburgermenu.js"></script>
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById('previewImg').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
</body>
</html>
