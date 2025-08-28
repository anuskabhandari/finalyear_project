<?php
session_start();
require 'backend/func.php'; // DB connection
require 'vendor/autoload.php'; // Dompdf autoload
use Dompdf\Dompdf;

$conn = dbConnect();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = $_GET['booking_id'] ?? 0;

// Fetch booking by booking_id
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

$success = '';
$error = '';
$transaction_id = '';
$payment_method = '';
$pdfFile = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['method']; 
    $cvv = $_POST['cvv']; 
    $status = 'Completed';                   
    $transaction_id = 'TXN' . time();        

    $stmt = $conn->prepare("INSERT INTO payment (booking_id, user_id, amount, method, status, transaction_id) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iidsss", 
        $booking['id'], 
        $booking['user_id'], 
        $booking['total_price'], 
        $payment_method, 
        $status, 
        $transaction_id
    );

    if ($stmt->execute()) {
        // update booking status
        $conn->query("UPDATE bookings SET status='Paid' WHERE id=" . $booking['id']);
        $success = "Payment successful!";   

        // -------------------------
        // ✅ Generate PDF Bill
        // -------------------------
        $html = "
        <h2 style='text-align:center;'>Tourist Guide Booking - Payment Bill</h2>
        <hr>
        <p><strong>Booking ID:</strong> {$booking['id']}</p>
        <p><strong>Customer:</strong> {$booking['name']}</p>
        <p><strong>Email:</strong> {$booking['email']}</p>
        <p><strong>Booking Date:</strong> {$booking['date']}</p>
        <p><strong>Payment Method:</strong> {$payment_method}</p>
        <p><strong>Transaction ID:</strong> {$transaction_id}</p>
        <h3 style='color:green;'>Total Amount Paid: Rs. " . number_format($booking['total_price'], 2) . "</h3>
        <hr>
        <p style='text-align:center;'>Thank you for booking with us!</p>
        ";

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Save PDF in /bills/ folder
        $pdfDir = __DIR__ . "/bills/";
        if (!file_exists($pdfDir)) {
            mkdir($pdfDir, 0777, true);
        }
        $pdfFile = "bills/Bill_{$booking['id']}.pdf";
        file_put_contents($pdfFile, $dompdf->output());

    } else {
        $error = "Payment failed. Please try again.";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment - Tourist Guide Booking</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="max-w-2xl mx-auto my-10 p-4 flex flex-col gap-8">

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <?php if($success): ?>
            <!-- Success Message -->
            <h2 class="text-2xl font-bold text-green-700 mb-4"><?= $success ?></h2>
            <p class="mb-4">Thank you <?= htmlspecialchars($booking['name']) ?>! Your booking ID <b><?= $booking['id'] ?></b> has been confirmed.</p>
            
            <!-- Download Bill Button -->
            <?php if($pdfFile): ?>
                <a href="<?= $pdfFile ?>" download 
                   class="block w-full text-center bg-blue-600 text-white py-3 rounded hover:bg-blue-700 transition mb-4">
                   Download Bill (PDF)
                </a>
            <?php endif; ?>

            <a href="index.php" class="block w-full text-center bg-gray-600 text-white py-3 rounded hover:bg-gray-700 transition">
                Back to Home
            </a>

        <?php else: ?>
            <!-- Payment Form -->
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Payment Method</h2>
            <?php if($error): ?>
                <div class="text-red-600 font-semibold mb-4"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-4">

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="card_name" value="<?= htmlspecialchars($booking['name']) ?>" 
                           maxlength="50" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Payment Method</label>
                    <select name="method" class="w-full border border-gray-300 rounded px-3 py-2" required>
                        <option value="">-- Select --</option>
                        <option value="Card" <?= ($payment_method=='Card')?'selected':'' ?>>Card</option>
                        <option value="eSewa" <?= ($payment_method=='eSewa')?'selected':'' ?>>eSewa</option>
                        <option value="Khalti" <?= ($payment_method=='Khalti')?'selected':'' ?>>Khalti</option>
                        <option value="Fonepay" <?= ($payment_method=='Fonepay')?'selected':'' ?>>Fonepay</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Card Number</label>
                    <input type="tel" name="card_number" placeholder="1234567812345678" 
                           maxlength="16" inputmode="numeric" pattern="\d*" 
                           oninput="this.value=this.value.replace(/\D/g,'')" 
                           class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block font-medium text-gray-700 mb-1">Expiry Date</label>
                        <input type="month" name="expiry_date" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    </div>
                    <div class="flex-1">
                        <label class="block font-medium text-gray-700 mb-1">CVV</label>
                        <input type="tel" name="cvv" maxlength="3" placeholder="123" 
                               inputmode="numeric" pattern="\d*" 
                               oninput="this.value=this.value.replace(/\D/g,'')" 
                               class="w-full border border-gray-300 rounded px-3 py-2" required>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="agreeTerms" required class="mr-2">
                    <label for="agreeTerms" class="text-gray-700">I agree to the <a href="#" class="text-blue-600 underline">Terms & Conditions</a></label>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded hover:bg-blue-700 transition">Pay Now</button>
                <div class="mb-4">
                    <a href="index.php" class="w-full inline-block text-center bg-gray-600 text-white py-3 rounded hover:bg-gray-700 transition">
                        Back to Home
                    </a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
