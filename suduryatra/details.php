<?php
session_start();
require_once 'backend/func.php';
$conn = dbConnect();

// Get place ID
$placeId = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$placeId) {
    die("No place ID specified.");
}

// Fetch place data
$sql = $conn->prepare("SELECT * FROM place WHERE id = ?");
$sql->bind_param("i", $placeId);
$sql->execute();
$result = $sql->get_result();
if ($result->num_rows > 0) {
    $placeData = $result->fetch_assoc();
} else {
    die("Place not found.");
}

// Fetch first package for the place
$stmt = $conn->prepare("SELECT package_id, price FROM package WHERE place_id = ? LIMIT 1");
$stmt->bind_param("i", $placeId);
$stmt->execute();
$result = $stmt->get_result();
$packageData = $result->fetch_assoc();
$packageId = $packageData ? $packageData['package_id'] : null;
$packagePrice = $packageData ? $packageData['price'] : 0;
$stmt->close();

// ------------------------
// Handle Login
// ------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, email, password, first_name, last_name FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
        } elseif ($password === $user['password']) {
            $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE user SET password = ? WHERE user_id = ?");
            $updateStmt->bind_param("si", $newHashedPassword, $user['user_id']);
            $updateStmt->execute();
            $updateStmt->close();
            $_SESSION['loggedin'] = true;
        } else {
            $error = "Invalid login credentials.";
        }

        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['fullname'] = $user['first_name'] . ' ' . $user['last_name'];
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($placeId));
            exit;
        }

    } else {
        $error = "Invalid login credentials.";
    }
    $stmt->close();
}

// ------------------------
// Handle Logout
// ------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . urlencode($placeId));
    exit;
}

// ------------------------
// Handle Booking
// ------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    if (!isset($_SESSION['user_id'])) {
        $bookingError = "You must be logged in to book.";
    } else {
        $user_id = $_SESSION['user_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $destination = $_POST['place'];
        $from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];

        $people = intval($_POST['people']);
        $total_price = floatval($_POST['amount']);
        $status = "Pending";

        $stmt = $conn->prepare("INSERT INTO bookings (name, email, user_id, destination, date, to_date, people, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisssids", $name, $email, $user_id, $destination, $from_date, $to_date, $people, $total_price, $status);


        if ($stmt->execute()) {
            header("Location: payment.php?place=" . urlencode($destination) . "&amount=" . urlencode($total_price));
            exit;
        } else {
            $bookingError = "Error saving booking: " . $stmt->error;
        }

        $stmt->close();
    }
}

// ------------------------
// Handle Reviews
// ------------------------
$reviews = [];
$reviewSuccess = $reviewError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) {
        $reviewError = "You must be logged in to submit a review.";
    } else {
        $userId = $_SESSION['user_id'];
        $rating = filter_var($_POST['rating'], FILTER_VALIDATE_FLOAT);
        $comment = trim($_POST['comment']);

        // Validate rating 1-5
        if ($rating === false || $rating < 1 || $rating > 5) {
            $reviewError = "Rating must be between 1 and 5.";
        } else {
            // Insert review
            $stmt = $conn->prepare("INSERT INTO review (user_id, place_id, package_id, rating, comment) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiids", $userId, $placeId, $packageId, $rating, $comment);
            if ($stmt->execute()) {
                $reviewSuccess = "Review submitted successfully!";
            } else {
                $reviewError = "Error saving review: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Fetch reviews for this place
$stmt = $conn->prepare("
    SELECT r.rating, r.comment, r.created_at, u.first_name, u.last_name
    FROM review r
    JOIN user u ON r.user_id = u.user_id
    WHERE r.place_id = ?
    ORDER BY r.created_at DESC
");
$stmt->bind_param("i", $placeId);
$stmt->execute();
$result = $stmt->get_result();
$reviews = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
// Calculate average rating & total reviews
$summaryStmt = $conn->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews FROM review WHERE place_id = ?");
$summaryStmt->bind_param("i", $placeId);
$summaryStmt->execute();
$summaryResult = $summaryStmt->get_result()->fetch_assoc();
$summaryStmt->close();

$avgRating = $summaryResult['avg_rating'] ? round($summaryResult['avg_rating'], 1) : 0;
$totalReviews = $summaryResult['total_reviews'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($placeData['name']); ?> - Details</title>
<link rel="stylesheet" href="/assets/css/places.css"/>
<style>
.modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); justify-content:center; align-items:center; }
.modal-content { background: #fff; padding: 20px; border-radius:10px; width: 100%; max-width: 400px; }
</style>
</head>
<body>

<header><?php echo htmlspecialchars($placeData['name']); ?> - Details</header>

<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
<div style="text-align:right; padding: 10px;">
  <form method="post" style="display:inline;">
    <div class="auth-status" id="authStatus">

  </div>
   <button type="submit" name="logout">Logout</button>
  </form>
</div>
<?php endif; ?>

<div class="container">
  <div class="carousel">
    <img src="images/<?php echo htmlspecialchars($placeData['image_path']); ?>" alt="<?php echo htmlspecialchars($placeData['name']); ?> Image" width="500" />
  </div>

  <h2>Overview</h2>
  <p class="description"><?php echo nl2br(htmlspecialchars($placeData['description'])); ?></p>

  <h2>Details</h2>
  <table>
    <tr><td><strong>Location:</strong></td><td><?php echo htmlspecialchars($placeData['address']); ?></td></tr>
    <tr><td><strong>Entry Fee:</strong></td><td>Rs. <?php echo htmlspecialchars($placeData['entry_fee']); ?> per person</td></tr>
    <tr><td><strong>Best Time to Visit:</strong></td><td><?php echo htmlspecialchars($placeData['best_time']); ?></td></tr>
    <tr><td><strong>Main Attractions:</strong></td><td><?php echo htmlspecialchars($placeData['main_attractions']); ?></td></tr>
    <tr><td><strong>Recommended Duration:</strong></td><td><?php echo htmlspecialchars($placeData['recommended_duration']); ?></td></tr>
  </table>

  <div class="auth-status" id="authStatus">
  <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
        echo "Welcome, " . htmlspecialchars($_SESSION['fullname']) . "!";
    } else {
        echo "You are not logged in.";
    }
  ?>
  </div>

  <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
    <button onclick="document.getElementById('loginModal').style.display='flex'">Login to Book</button>
  <?php else: ?>
    <?php
$bookingUrl = "booking.php?destination=" . urlencode($placeData['name']) . "&price=" . urlencode($packagePrice);
?>
<button onclick="window.location.href='<?= $bookingUrl ?>'">Book Now</button>

  <?php endif; ?>
</div>

<!-- Login Modal -->
<div id="loginModal" class="modal" style="display:<?php echo isset($error) ? 'flex' : 'none'; ?>;">
  <div class="modal-content">
    <h3>Visitor Login</h3>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
      <input type="text" name="username" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
      <button type="button" onclick="document.getElementById('loginModal').style.display='none'">Cancel</button>
    </form>
  </div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="modal">
  <div class="modal-content">
    <h3>Book <?php echo htmlspecialchars($placeData['name']); ?> Tour</h3>
    <form method="post">
      <input type="hidden" name="place" value="<?php echo htmlspecialchars($placeData['name']); ?>">
      <input type="hidden" name="amount" value="<?php echo htmlspecialchars($packagePrice); ?>">

      <input type="text" name="name" placeholder="Your Name"
             value="<?php echo isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : ''; ?>"
             required <?php echo isset($_SESSION['fullname']) ? 'readonly' : ''; ?>>

      <input type="email" name="email" placeholder="Email Address"
             value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>"
             required <?php echo isset($_SESSION['email']) ? 'readonly' : ''; ?>>

    <div class="date-range">
  <label>From Date:</label>
  <input type="date" name="from_date" id="fromDate" required
         min="<?php echo date('Y-m-d'); ?>"
         max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>">

  <label>To Date:</label>
  <input type="date" name="to_date" id="toDate" required
         min="<?php echo date('Y-m-d'); ?>"
         max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>">
</div>

<script>
  const fromInput = document.getElementById('fromDate');
  const toInput = document.getElementById('toDate');

  fromInput.addEventListener('change', () => {
      toInput.min = fromInput.value;
  });
</script>

      <input type="number" name="people" placeholder="Number of People" required>
      <textarea name="requests" placeholder="Special Requests (Optional)"></textarea>

      <button type="submit" name="book">Confirm Booking</button>
      <button type="button" onclick="closeBooking()">Cancel</button>
    </form>
  </div>
</div>

<?php if (!empty($placeData['latitude']) && !empty($placeData['longitude'])): ?>
<div class="map">
  <iframe
    width="100%"
    height="300"
    style="border:0"
    loading="lazy"
    allowfullscreen
    referrerpolicy="no-referrer-when-downgrade"
    src="https://www.google.com/maps?q=<?php echo $placeData['latitude']; ?>,<?php echo $placeData['longitude']; ?>&hl=es&z=14&output=embed">
  </iframe>
</div>
<?php endif; ?>

<!-- Reviews -->
<h2>Review Summary</h2>
<p>
  Overall Rating: <?= $avgRating ?>/5 
  (<?= $totalReviews ?> reviews)
</p>

<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
    <?php if ($reviewSuccess) echo "<p style='color: green;'>$reviewSuccess</p>"; ?>
    <?php if ($reviewError) echo "<p style='color: red;'>$reviewError</p>"; ?>

    <form class="review-form" method="post">
        <label>Rating (1 to 5):</label>
        <input type="number" name="rating" min="1" max="5" step="0.1" required oninput="validity.valid||(value='');">
        <label>Your Comment:</label>
        <textarea name="comment" rows="4" placeholder="Share your experience..." required></textarea>
        <button type="submit" name="submit_review">Submit Review</button>
    </form>
<?php else: ?>
    <p>Please log in to submit a review.</p>
<?php endif; ?>

<h3>Reviews</h3>
<?php if (!empty($reviews)): ?>
    <ul class="review-list">
    <?php foreach ($reviews as $rev): ?>
        <li>
            <strong><?= htmlspecialchars($rev['first_name'] . ' ' . $rev['last_name']); ?></strong>
            <span>(<?= htmlspecialchars($rev['rating']); ?>/5)</span>
            <p><?= nl2br(htmlspecialchars($rev['comment'])); ?></p>
            <small><?= htmlspecialchars($rev['created_at']); ?></small>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No reviews yet.</p>
<?php endif; ?>

<a href="destinations.php" class="back-link">← Back to Destinations</a>

<script>
function openBooking() { document.getElementById('bookingModal').style.display = 'flex'; }
function closeBooking() { document.getElementById('bookingModal').style.display = 'none'; }
</script>

</body>
</html>
