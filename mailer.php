<?php
// mailer.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendEmail($to, $subject, $message, $from = 'no-reply@example.com')
{
    $mail = new PHPMailer(true);

    try {
        // Safe environment check: Default to local if $_SERVER is not set (like in CLI/Cron environments)
        $serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
        $isLocal = in_array($serverName, ['localhost', '127.0.0.1']);

        if ($isLocal) {
            // Local fallback config: Connect directly to live Gmail via TLS to bypass MailHog if not installed
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'corporatehair.sales@gmail.com';
            $mail->Password = 'yjasvosugikipzyj';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
        } else {
            // Production: real SMTP 
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'corporatehair.sales@gmail.com';
            $mail->Password = 'yjasvosugikipzyj';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
        }

        // Email settings
        $mail->setFrom($from, 'Empire Clone');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->isHTML(true);

        $mail->send();
        
        return true; // ✔️ FIXED: Crucial return status flag added back
        
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        
        return false; 
    }
}