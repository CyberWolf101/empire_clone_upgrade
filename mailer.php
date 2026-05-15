<?php
// mailer.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// 4Ew5939xu8QzwbICPC
require 'vendor/autoload.php';

function sendEmail($to, $subject, $message, $from = 'no-reply@example.com')
{
    $mail = new PHPMailer(true);

    try {
        // Detect environment: local vs production
        $isLocal = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);

        if ($isLocal) {
            // Local: MailHog (default runs on port 1025)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 1025;
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = false;
        } else {
            // Production: real SMTP (adjust these settings to your host)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'corporatehair.sales@gmail.com';
            $mail->Password = 'yjasvosugikipzyj';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
        }
        // 587
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        // $mail->Host = 'mail.chbluxuryempire.com';

        // Email settings
        $mail->setFrom($from, 'Empire Clone');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->isHTML(true);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
