<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust path based on PHPMailer installation

class EmailSender {
    private $mailer;
    private $dbConnection;

    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;

        // Initialize PHPMailer
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.mailtrap.io'; // Replace with your SMTP host (e.g., Mailtrap for testing)
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'your-mailtrap-username'; // Replace with your SMTP username
        $this->mailer->Password = 'your-mailtrap-password'; // Replace with your SMTP password
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587; // Adjust based on your SMTP service
        $this->mailer->setFrom('no-reply@yourdomain.com', 'Your Company Name');
        $this->mailer->isHTML(true);
    }

    public function sendOrderEmail($orderId, $recipientEmail, $recipientName) {
        try {
            // Fetch order details
            $orderId = mysqli_real_escape_string($this->dbConnection, $orderId);
            $orderSql = "SELECT * FROM event_orders WHERE id = '$orderId'";
            $orderResult = mysqli_query($this->dbConnection, $orderSql);
            if (!$orderResult || mysqli_num_rows($orderResult) == 0) {
                return ['success' => false, 'message' => 'Order not found.'];
            }
            $orderRow = mysqli_fetch_assoc($orderResult);
            $customerName = htmlspecialchars($orderRow['customer_name']);
            $totalAmount = $orderRow['edited_fee'] !== null ? number_format($orderRow['edited_fee'], 2) : number_format($orderRow['total_amount'], 2);

            // Fetch order items
            $itemsSql = "SELECT * FROM event_order_items WHERE orderid = '$orderId'";
            $itemsResult = mysqli_query($this->dbConnection, $itemsSql);
            $itemsTable = "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            $itemsTable .= "<tr><th>Item</th><th>Quantity</th><th>Unit Price</th><th>Total</th></tr>";
            while ($itemRow = mysqli_fetch_assoc($itemsResult)) {
                $itemsTable .= "<tr>";
                $itemsTable .= "<td>" . htmlspecialchars($itemRow['item']) . "</td>";
                $itemsTable .= "<td>" . $itemRow['quantity'] . "</td>";
                $itemsTable .= "<td>&#8358;" . number_format($itemRow['unitprice'], 2) . "</td>";
                $itemsTable .= "<td>&#8358;" . number_format($itemRow['totalprice'], 2) . "</td>";
                $itemsTable .= "</tr>";
            }
            $itemsTable .= "</table>";

            // Generate payment link
            $paymentLink = "http://" . $_SERVER['HTTP_HOST'] . "/payment.php?order=" . $orderId;

            // Set email details
            $this->mailer->addAddress($recipientEmail, $recipientName);
            $this->mailer->Subject = "Your Event Order Quote - ID: $orderId";
            $this->mailer->Body = "
                <h2>Dear $customerName,</h2>
                <p>Thank you for your event order request. Below is your quote:</p>
                $itemsTable
                <p><strong>Total: &#8358;$totalAmount</strong></p>
                <p>Please proceed to payment here: <a href='$paymentLink'>Pay Now</a></p>
                <p>Best regards,<br>Your Company Name</p>";
            $this->mailer->AltBody = "Dear $customerName,\n\nThank you for your event order request. Your order ID is $orderId.\nTotal: ₦$totalAmount\nPlease proceed to payment here: $paymentLink\n\nBest regards,\nYour Company Name";

            // Send email
            $this->mailer->send();
            return ['success' => true, 'message' => "Email sent successfully to $recipientEmail!"];
        } catch (Exception $e) {
            error_log("Failed to send email to $recipientEmail for order $orderId: " . $this->mailer->ErrorInfo);
            return ['success' => false, 'message' => "Error sending email: " . $this->mailer->ErrorInfo];
        }
    }

    // Optional: Add method for custom emails (without order context)
    public function sendCustomEmail($recipientEmail, $recipientName, $subject, $body, $altBody = '', $attachments = []) {
        try {
            $this->mailer->addAddress($recipientEmail, $recipientName);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            $this->mailer->AltBody = $altBody ?: strip_tags($body);

            // Add attachments if provided
            foreach ($attachments as $attachment) {
                if (file_exists($attachment)) {
                    $this->mailer->addAttachment($attachment);
                }
            }

            $this->mailer->send();
            return ['success' => true, 'message' => "Email sent successfully to $recipientEmail!"];
        } catch (Exception $e) {
            error_log("Failed to send custom email to $recipientEmail: " . $this->mailer->ErrorInfo);
            return ['success' => false, 'message' => "Error sending email: " . $this->mailer->ErrorInfo];
        }
    }
}
?>