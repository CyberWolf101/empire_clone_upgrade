<?php
session_start();
include "header.php"; // Assumes $con is defined
require_once "EmailSender.php";

$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailSender = new EmailSender($con);
    $recipientEmail = mysqli_real_escape_string($con, $_POST['recipient_email']);
    $recipientName = mysqli_real_escape_string($con, $_POST['recipient_name']);
    
    if (!empty($_POST['order_id'])) {
        // Send order-specific email
        $orderId = mysqli_real_escape_string($con, $_POST['order_id']);
        $result = $emailSender->sendOrderEmail($orderId, $recipientEmail, $recipientName);
    } else {
        // Send custom email
        $subject = "Test Email from Your Company";
        $body = "<h2>Hello $recipientName,</h2><p>This is a test email sent from the EmailSender component.</p><p>Best regards,<br>Your Company Name</p>";
        $altBody = "Hello $recipientName,\n\nThis is a test email sent from the EmailSender component.\n\nBest regards,\nYour Company Name";
        $result = $emailSender->sendCustomEmail($recipientEmail, $recipientName, $subject, $body, $altBody);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email Sender</title>
    <!-- Bootstrap CSS (assumes header.php includes it, otherwise add manually) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 600px; margin-top: 20px; }
        .btn-buya {
            display: inline-block;
            padding: 6px !important;
            border: none;
            color: #fff;
            font-size: 14px !important;
            text-transform: uppercase;
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            transition: 0.3s;
            background: #FEBF01;
        }
        .btn-buya:hover { background: #000; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="h3 mb-4 text-gray-800">Test Email Sender</h1>
        
        <?php if ($result): ?>
            <div class="alert <?php echo $result['success'] ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo htmlspecialchars($result['message']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Send Test Email</h6>
            </div>
            <div class="card-body">
                <form method="post" action="test_email.php">
                    <div class="mb-3">
                        <label for="recipient_email" class="form-label">Recipient Email</label>
                        <input type="email" class="form-control" id="recipient_email" name="recipient_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="recipient_name" class="form-label">Recipient Name</label>
                        <input type="text" class="form-control" id="recipient_name" name="recipient_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="order_id" class="form-label">Order ID (Optional, for order-specific email)</label>
                        <input type="number" class="form-control" id="order_id" name="order_id" placeholder="Leave blank for custom email">
                    </div>
                    <button type="submit" class="btn btn-buya">Send Email</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (assumes header.php includes it, otherwise add manually) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include "footer.php"; ?>