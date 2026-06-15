<?php
// Set PHP timezone
date_default_timezone_set('Africa/Lagos');

// 1. Include your database connection and custom email function files
include "connect.php";
include "mailer.php"; 

// Set MySQL session timezone
mysqli_query($con, "SET time_zone = '+01:00'") or die("Cannot set timezone: " . mysqli_error($con));

// Create a DateTime object for the current runtime clock
$now = new DateTime();
$today = $now->format("Y-m-d");

// echo "<h3>Cron Engine Active - Current Server Time: " . $now->format("Y-m-d H:i:s") . "</h3>";

// 2. Fetch all paid academy bookings
$reminder_sql = "SELECT s.*, 
    (a.training) as real_training_id,
    (SELECT c.unique_id FROM customers c WHERE c.name = s.name) as customer_id,
    (SELECT td.reminder_interval FROM training_dates td WHERE td.training_id_from_saloon_orders = s.id) as reminder_interval,
    (SELECT td.reminder_unit FROM training_dates td WHERE td.training_id_from_saloon_orders = s.id) as reminder_unit 
    FROM saloon_orders s 
    LEFT JOIN academy_cart a ON s.id = a.id 
    WHERE s.pay_status='paid' 
      AND s.section='academy'
    ORDER BY s.id DESC";

$reminder_query = mysqli_query($con, $reminder_sql);

if ($reminder_query && mysqli_num_rows($reminder_query) > 0) {
    $processed_count = 0;

    while ($academy_row = mysqli_fetch_array($reminder_query)) {
        $booking_id = $academy_row['id']; 
        $student_email = $academy_row['email'];
        $student_name = $academy_row['name'];
        $class_name = $academy_row['bookingname'] ?? 'Academy Session'; 
        $class_time = $academy_row['added_on'] ?? ''; 

        if (empty($class_time)) {
            $class_time = $academy_row['date'] ?? '';
        }

        if (empty($class_time)) {
            continue;
        }

        $interval = isset($academy_row['reminder_interval']) ? (int)$academy_row['reminder_interval'] : 2;
        $db_unit = isset($academy_row['reminder_unit']) ? strtolower(trim($academy_row['reminder_unit'])) : 'd';

        switch ($db_unit) {
            case 's': $unit = "seconds"; break;
            case 'h': $unit = "hours"; break;
            case 'd': $unit = "days"; break;
            case 'w': $unit = "weeks"; break;
            case 'm': $unit = "months"; break;
            case 'y': $unit = "years"; break;
            default:  $unit = "days"; break;
        }

        try {
            $class_datetime = new DateTime(trim($class_time));
        } catch (Exception $e) {
            // echo "Skipping booking ID: $booking_id due to unparseable date/time string.<br>";
            continue;
        }

        // Subtract the interval rule from the class time to get the execution target
        $target_datetime = clone $class_datetime;
        $target_datetime->modify("-{$interval} {$unit}");

        $should_send = false;
        $milestone_string = "";

        // Subtract the interval rule from the class time to get the execution target
        $target_datetime = clone $class_datetime;
        $target_datetime->modify("-{$interval} {$unit}");

        $should_send = false;
        $milestone_string = "";

        // Calculate differences for repeating intervals
        $time_diff_seconds = $now->getTimestamp() - $class_datetime->getTimestamp();

        // 3. True Repeating Windows based on Unit
        if ($unit === 'seconds') {
            // For testing seconds: Checks if the time elapsed since the class time 
            // is a perfect multiple of your interval (e.g., every 300 seconds)
            if ($interval > 0 && ($time_diff_seconds % $interval) === 0) {
                $should_send = true;
                // Unique milestone tracking key down to the exact matching second
                $milestone_string = $booking_id . "_repeating_s_" . $now->getTimestamp();
            }
        } elseif ($unit === 'hours') {
            // HOURLY REPEATING: Checks if the current minute is 00 (top of the hour)
            // and if the hour difference divides evenly by the interval
            $time_diff_hours = floor($time_diff_seconds / 3600);
            if ($interval > 0 && ($time_diff_hours % $interval) === 0 && $now->format('i') === '00') {
                $should_send = true;
                $milestone_string = $booking_id . "_repeating_h_" . $now->format('Y-m-d_H');
            }
        } else {
            // DAILY/WEEKLY/MONTHLY REPEATING: Matches if today is the exact calculated calendar day window
            if ($now->format('Y-m-d') === $target_datetime->format('Y-m-d')) {
                $should_send = true;
                $milestone_string = $booking_id . "_" . $target_datetime->format('Y-m-d');
            }
        }

        // 4. Verify against our log system to ensure repetitive crons do not cause duplicate emails inside the same window
        if ($should_send && !empty($milestone_string)) {
            $check_log = mysqli_query($con, "SELECT id FROM reminder_logs WHERE booking_id='$booking_id' AND milestone_sent='$milestone_string'");
            
            if (mysqli_num_rows($check_log) > 0) {
                $should_send = false; 
                // echo "Testing ID: $booking_id | Target matches, but this repeat milestone was already dispatched: ($milestone_string)<br>";
            }
        } else {
            // echo "Testing ID: $booking_id | Class: " . $class_datetime->format('Y-m-d H:i:s') . 
            //      " | Current Clock: " . $now->format('Y-m-d H:i:s') . 
            //      " | Diff: " . $time_diff_seconds . "s | Match Status: <span style='color:red;'>NO MATCH</span><br>";
        }

        // 5. Fire off communication if criteria is clean and clear
        if ($should_send) {
            $reminder_subject = "Reminder: Your Academy Class is coming up! - CHBLUXURYEMPIRE";
            
            $reminder_message = "
            <div style='background-color:#000000; color:#fff !important; padding:10px 20px; font-family: Arial, sans-serif;'>
                <p style='text-align:left;'>
                    <img src='http://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='60px;' style='margin-top:13px;'>
                    <span style='float:right; font-size:15px; color:#FFFFFF; padding-right:6px; margin-top:13px;'>Date: $today</span>
                </p>
                <h5 style='color:#FFC700;'>UPCOMING CLASS REMINDER</h5>
                <p style='color:white;'>Hello $student_name,</p>
                <p style='color:white;'>This is a friendly reminder that your session for <strong>$class_name</strong> is scheduled for <strong>" . $class_datetime->format('Y-m-d H:i') . "</strong>.</p>
                <br><br>
                <p style='text-align:center;'><a href='http://chbluxuryempire.com' style='color:#FFC700; text-decoration:none;'>CHBLUXURYEMPIRE</a></p>
            </div>";

            $reminder_headers = "From: \"CHBLUXURYEMPIRE\" <noreply@chbluxuryempire.com>\r\n";
            $reminder_headers .= "Reply-To: noreply@chbluxuryempire.com\r\n";
            $reminder_headers .= "MIME-Version: 1.0\r\n";
            $reminder_headers .= "Content-type: text/html; charset=UTF-8\r\n";

            // --- LOCAL TESTING OVERRIDE ---
            // Comment out the actual mail dispatching during local XAMPP development
            
            if (function_exists('sendEmail')) {
                $mail_sent = sendEmail($student_email, $reminder_subject, $reminder_message, $reminder_headers);
            } else {
                $mail_sent = mail($student_email, $reminder_subject, $reminder_message, $reminder_headers);
            }
            
            // $mail_sent = true; // Force success status for local testing
            // ------------------------------

            if ($mail_sent) {
                // Log this specific milestone execution instantly so it loops safely without duplicate spamming
                $current_now_string = $now->format('Y-m-d H:i:s');
                mysqli_query($con, "INSERT INTO reminder_logs (booking_id, milestone_sent, sent_at) VALUES ('$booking_id', '$milestone_string', '$current_now_string')");
                
                $processed_count++;
                // echo "Testing ID: $booking_id | <span style='color:green;'><strong>✔️ MATCH & DISPATCHED! Logged milestone: $milestone_string</strong></span><br>";
            } else {
                // echo "<span style='color:red;'>❌ Mail handling subsystem failure.</span><br><br>";
            }
        }
    }
    // echo "<br><strong>Execution Finished.</strong> Total reminders processed during this pass: " . $processed_count;
} else {
    // echo "No pending paid academy entries found.";
}
?>