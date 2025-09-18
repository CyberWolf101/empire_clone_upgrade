<?php
include 'header.php';
include 'mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
    $to      = $_POST['to'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    if (sendEmail($to, $subject, $message)) {
        echo "<p style='color:green;'>✅ Email sent successfully.</p>";
    } else {
        echo "<p style='color:red;'>❌ Failed to send email.</p>";
    }
}
?>

<form method="post">
    <label>To:</label><br>
    <input type="email" name="to" value="test@example.com" required><br><br>

    <label>Subject:</label><br>
    <input type="text" name="subject" value="Hello from MailHog!" required><br><br>

    <label>Message:</label><br>
    <textarea name="message" rows="5" cols="40" required>Hello, this is a test email.</textarea><br><br>

    <button type="submit" name="send_email">Send Email</button>
</form>
