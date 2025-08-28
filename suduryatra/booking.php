<?php
session_start();
require_once 'backend/func.php';
$conn = dbConnect();

// Check user
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Fetch email
$userStmt = $conn->prepare("SELECT email FROM user WHERE user_id = ?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result()->fetch_assoc();
$email = $userResult['email'];
$userStmt->close();

// Fetch places with packages
$places = [];
$result = $conn->query("
    SELECT pl.id AS place_id, pl.name AS place_name,
           p.package_id, p.title AS package_name, p.price AS package_price, p.duration
    FROM place pl
    LEFT JOIN package p ON pl.id = p.place_id
    WHERE pl.status = 'active'
    ORDER BY pl.name, p.title
");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $place_id = $row['place_id'];
        if (!isset($places[$place_id])) {
            $places[$place_id] = [
                'name' => $row['place_name'],
                'packages' => []
            ];
        }
        if ($row['package_id']) {
            $places[$place_id]['packages'][] = [
                'id' => $row['package_id'],
                'name' => $row['package_name'],
                'price' => $row['package_price'],
                'duration' => $row['duration']
            ];
        }
    }
}

// Form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $package_id   = $_POST['package_id'];
    $name         = trim($_POST['name']);
    $email_post   = trim($_POST['email']);
    $place_id     = $_POST['place'];
    $people       = intval($_POST['people']);
    $from_date    = $_POST['from_date'];
    $to_date      = $_POST['to_date'];

    // Fetch package details
    $pkg_stmt = $conn->prepare("SELECT price FROM package WHERE package_id = ?");
    $pkg_stmt->bind_param("i", $package_id);
    $pkg_stmt->execute();
    $pkg_stmt->bind_result($package_price);
    $pkg_stmt->fetch();
    $pkg_stmt->close();

    // Calculate number of days (minimum 1)
    $fromDate = new DateTime($from_date);
    $toDate = new DateTime($to_date);
    $days = max(1, $toDate->diff($fromDate)->days + 1);

    // Backend total price calculation
    $total_price = $package_price * $people * $days;

    // Insert booking
    $stmt = $conn->prepare("INSERT INTO bookings 
        (package_id, name, email, user_id, destination, date, to_date, people, total_price) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ississsid",
        $package_id,
        $name,
        $email_post,
        $user_id,
        $place_id,
        $from_date,
        $to_date,
        $people,
        $total_price
    );

    if ($stmt->execute()) {
        echo "<script>
                alert('Booking confirmed! Redirecting to payment...');
                window.location.href='payment.php?booking_id=".$stmt->insert_id."';
              </script>";
        exit();
    } else {
        echo "<script>alert('Booking failed. Please try again.');</script>";
    }

    $stmt->close();
}

$conn->close();
$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Book Your Trip - Sudur Yatra</title>
<style>
body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f4f4f4; }
header { background: linear-gradient(to right, #00796b, #43a047); padding: 20px; text-align: center; color: white; font-size: 26px; }
.container { max-width: 600px; margin: 40px auto; background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
h2 { color: #00796b; margin-bottom: 20px; text-align: center; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-weight: bold; margin-bottom: 6px; }
.form-group input, .form-group select { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc; }
.submit-btn { background-color: #00796b; color: white; padding: 12px; width: 100%; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; }
.submit-btn:hover { background-color: #004d40; }
.back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #00796b; font-weight: bold; }
.back-link:hover { text-decoration: underline; }
</style>
</head>
<body>
<header>Trip Booking - Tourist Guide</header>

<div class="container">
<h2>Book Your Adventure</h2>
<form method="POST" action="booking.php">

    <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" placeholder="Enter your full name" required />
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" readonly />
    </div>

    <div class="form-group">
        <label>Place</label>
        <select name="place" id="placeSelect" required>
            <option value="">-- Select Place --</option>
            <?php foreach ($places as $id => $pl): ?>
                <option value="<?= $id ?>"><?= htmlspecialchars($pl['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Package</label>
        <select name="package_id" id="packageSelect" required>
            <option value="">-- Select Package --</option>
        </select>
    </div>

    <div class="form-group">
        <label>Number of People</label>
        <input type="number" name="people" id="peopleInput" min="1" value="1" required />
    </div>

    <div class="form-group">
        <label>Price per Person (NPR)</label>
        <input type="text" id="pricePerPerson" readonly />
    </div>

    <div class="form-group">
        <label>Total Price (NPR)</label>
        <input type="text" id="totalPrice" readonly />
    </div>

    <div class="form-group">
        <label>From Date</label>
        <input type="date" name="from_date" id="fromDate" min="<?= $today ?>" required />
    </div>

    <div class="form-group">
        <label>To Date</label>
        <input type="date" name="to_date" id="toDate" min="<?= $today ?>" required />
    </div>

    <button type="submit" class="submit-btn">Confirm Booking</button>
</form>

<a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

<script>
const placeSelect = document.getElementById('placeSelect');
const packageSelect = document.getElementById('packageSelect');
const peopleInput = document.getElementById('peopleInput');
const pricePerPersonInput = document.getElementById('pricePerPerson');
const totalPriceInput = document.getElementById('totalPrice');
const fromDateInput = document.getElementById('fromDate');
const toDateInput = document.getElementById('toDate');

const packagesData = <?= json_encode($places) ?>;

placeSelect.addEventListener('change', function() {
    const placeId = this.value;
    packageSelect.innerHTML = '<option value="">-- Select Package --</option>';
    if (packagesData[placeId] && packagesData[placeId].packages) {
        packagesData[placeId].packages.forEach(pkg => {
            const opt = document.createElement('option');
            opt.value = pkg.id;
            opt.textContent = pkg.name + ' (Rs. ' + pkg.price + ')';
            opt.dataset.price = pkg.price;
            opt.dataset.duration = pkg.duration;
            packageSelect.appendChild(opt);
        });
    }
    pricePerPersonInput.value = '';
    totalPriceInput.value = '';
});

packageSelect.addEventListener('change', updatePrice);
peopleInput.addEventListener('input', updatePrice);
fromDateInput.addEventListener('change', updatePrice);
toDateInput.addEventListener('change', updatePrice);

function updatePrice() {
    const selectedOption = packageSelect.options[packageSelect.selectedIndex];
    if (!selectedOption || !selectedOption.dataset.price) {
        pricePerPersonInput.value = '';
        totalPriceInput.value = '';
        return;
    }

    const pricePerPerson = parseFloat(selectedOption.dataset.price || 0);
    const people = parseInt(peopleInput.value) || 1;

    let days = 1;
    if (fromDateInput.value && toDateInput.value) {
        const fromDate = new Date(fromDateInput.value);
        const toDate = new Date(toDateInput.value);
        if (toDate >= fromDate) {
            days = Math.ceil((toDate - fromDate) / (1000*60*60*24)) + 1;
        }
    }

    pricePerPersonInput.value = pricePerPerson.toFixed(2);
    totalPriceInput.value = (pricePerPerson * people * days).toFixed(2);
}

fromDateInput.addEventListener('change', function() {
    toDateInput.min = this.value;
    if (toDateInput.value < this.value) toDateInput.value = this.value;
});
</script>
</body>
</html>
