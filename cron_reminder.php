<?php
// Set PHP timezone
date_default_timezone_set('Africa/Lagos');

// 1. Include core files sequentially
include_once "connect.php";
include_once "mailer.php"; 

// Set MySQL session timezone
mysqli_query($con, "SET time_zone = '+01:00'") or die("Cannot set timezone: " . mysqli_error($con));

$now = new DateTime();
$today = $now->format("Y-m-d");

echo "<h3>Cron Engine Active - Current Server Time: " . $now->format("Y-m-d H:i:s") . "</h3>";

// 2. Fetch all paid academy bookings
$reminder_sql = "SELECT 
                    s.*,
                    t.reminder_interval,
                    t.reminder_unit,
                    t.start_date AS real_class_date 
                 FROM saloon_orders s 
                 LEFT JOIN training_dates t ON s.id = t.training_id_from_saloon_orders 
                 WHERE s.section = 'academy' 
                   AND s.pay_status = 'paid'
                 ORDER BY s.id DESC";

$reminder_query = mysqli_query($con, $reminder_sql);

if ($reminder_query) {
    $processed_count = 0;

    while ($academy_row = mysqli_fetch_array($reminder_query)) {
        $booking_id = $academy_row['id']; 
        $student_email = isset($academy_row['email']) ? trim($academy_row['email']) : ''; 
        $student_name = isset($academy_row['name']) ? trim($academy_row['name']) : 'Student';
        $class_name = $academy_row['bookingname'] ?? 'Academy Session'; 
        $class_time = $academy_row['real_class_date'] ?? ''; 

        if (empty($class_time)) {
            echo "Booking ID: $booking_id | <span style='color:orange;'>Skipped: Missing class date.</span><br>";
            continue;
        }

        if (empty($student_email) || !filter_var($student_email, FILTER_VALIDATE_EMAIL)) {
            echo "Booking ID: $booking_id | <span style='color:orange;'>Skipped: Invalid email layout ($student_email).</span><br>";
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
            continue;
        }

        // If the class date has passed, close it down out of the engine cycle
        if ($now >= $class_datetime) {
            echo "Booking ID: $booking_id | Status: Finished (Class date has already passed).<br>";
            continue;
        }

        $should_send = false;
        $milestone_string = "";

        // ==========================================================
        // 🔁 REPAIRING REPEATING TIME PROCESSING LOGIC
        // ==========================================================
        if ($unit === 'seconds' || $unit === 'hours') {
            $time_to_class = $class_datetime->getTimestamp() - $now->getTimestamp();
            $interval_in_seconds = ($unit === 'hours') ? $interval * 3600 : $interval;

            // FIX: Use a window-block division method. 
            // It divides the remaining time into numerical blocks so the exact millisecond doesn't matter.
            if ($time_to_class > 0) {
                $window_block = floor($time_to_class / $interval_in_seconds);
                $should_send = true;
                $milestone_string = $booking_id . "_repeating_" . $unit . "_block_" . $window_block;
            }
        } else {
            // Days, Weeks, Months, Years calculation parameters
            $date_diff = $now->diff($class_datetime);
            $days_remaining = (int)$date_diff->format('%a');

            $interval_in_days = $interval;
            if ($unit === 'weeks')  { $interval_in_days = $interval * 7; }
            if ($unit === 'months') { $interval_in_days = $interval * 30; }
            if ($unit === 'years')  { $interval_in_days = $interval * 365; }

            if ($interval_in_days <= 0) { $interval_in_days = 1; }

            if ($days_remaining > 0 && ($days_remaining % $interval_in_days === 0)) {
                $should_send = true;
                $milestone_string = $booking_id . "_recurring_" . $unit . "_day_" . $days_remaining;
            }
        }
        // ==========================================================

        // 4. Verify against log system to block duplicates inside the current time-block window
        if ($should_send && !empty($milestone_string)) {
            $check_log = mysqli_query($con, "SELECT id FROM reminder_logs WHERE booking_id='$booking_id' AND milestone_sent='$milestone_string'");
            
            if (mysqli_num_rows($check_log) > 0) {
                $should_send = false; 
                echo "Booking ID: $booking_id | status: <span style='color:blue;'>Waiting (Milestone '$milestone_string' already sent for this interval).</span><br>";
            }
        }

        // 5. Fire off communication if criteria matches cleanly
        if ($should_send) {
            echo "Booking ID: $booking_id | <span style='color:purple;'>Match found! Connecting to mailer endpoint...</span><br>";
            
            $reminder_subject = "Reminder: Your Academy Class is coming up! - CHBLUXURYEMPIRE";
            $clean_student_name = htmlspecialchars($student_name, ENT_QUOTES, 'UTF-8');
            $clean_class_name = htmlspecialchars($class_name, ENT_QUOTES, 'UTF-8');

            $reminder_message = "
            <div style='background-color:; color:; padding:10px 20px; font-family: Arial, sans-serif;'>
                <p style='text-align:left;'>
                    <img src='http://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='60px;' style='margin-top:13px;'>
                    <span style='float:right; font-size:15px; color:; padding-right:6px; margin-top:13px;'>Date: $today</span>
                </p>
                <h5 style='color:#FFC700;'>UPCOMING CLASS REMINDER</h5>
                <p style='color:;'>Hello $clean_student_name,</p>
                <p style='color:;'>This is a friendly reminder that your session for <strong>$clean_class_name</strong> is scheduled for <strong>" . $class_datetime->format('Y-m-d H:i') . "</strong>.</p>
                <br><br>
                <p style='text-align:center;'><a href='http://chbluxuryempire.com' style='color:#FFC700; text-decoration:none;'>CHBLUXURYEMPIRE</a></p>
            </div>";

            $mail_sent = sendEmail($student_email, $reminder_subject, $reminder_message);

            if ($mail_sent) {
                $current_now_string = $now->format('Y-m-d H:i:s');
                mysqli_query($con, "INSERT INTO reminder_logs (booking_id, milestone_sent, sent_at) VALUES ('$booking_id', '$milestone_string', '$current_now_string')");
                $processed_count++;
                echo "Booking ID: $booking_id | <span style='color:green;'><strong>✔️ SUCCESS: Mail sent and tracked!</strong></span><br>";
            } else {
                echo "Booking ID: $booking_id | <span style='color:red;'>❌ FAILED: Mailer accepted request but could not transmit.</span><br>";
            }
        }
    }
    echo "<br><strong>Execution Finished.</strong> Total active alerts dispatched: " . $processed_count;
} else {
    echo "SQL Query Error: " . mysqli_error($con);
}
?>