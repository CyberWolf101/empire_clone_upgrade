<?php
include "../connect.php";

if(isset($_POST["ExportType"])) {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $section = $_POST['section'];


if($section=="saloon"){
$sql_query = "SELECT DATE(date) AS order_date,COUNT(*) AS order_count,SUM(total_amount) AS order_amount_count FROM saloon_orders WHERE date BETWEEN '$from' AND '$to' && pay_status = 'paid' && section='spa' GROUP BY order_date ORDER BY order_date ASC";
}

if($section=="orishirishi"){
$sql_query = "SELECT DATE(date) AS order_date,COUNT(*) AS order_count,SUM(total_amount) AS order_amount_count FROM saloon_orders WHERE date BETWEEN '$from' AND '$to' && pay_status = 'paid' && section='refreshments' GROUP BY order_date ORDER BY order_date ASC";
}


if($section=="kitchen"){
$sql_query = "SELECT DATE(date) AS order_date,COUNT(*) AS order_count,SUM(total_amount) AS order_amount_count FROM saloon_orders WHERE date BETWEEN '$from' AND '$to' && pay_status = 'paid' && section='kitchen' GROUP BY order_date ORDER BY order_date ASC";
}

if($section=="academy"){
$sql_query = "SELECT DATE(date) AS order_date,COUNT(*) AS order_count,SUM(total_amount) AS order_amount_count FROM saloon_orders WHERE date BETWEEN '$from' AND '$to' && pay_status = 'paid' && section='academy' GROUP BY order_date ORDER BY order_date ASC";
}

if($section=="members"){
$sql_query = "SELECT DATE(start_date) AS order_date,COUNT(*) AS order_count,SUM(total_amount) AS order_amount_count FROM members WHERE date BETWEEN '$from' AND '$to' && pay_status = 'paid' GROUP BY order_date ORDER BY order_date ASC";
}

if($section=="giftcard"){
$sql_query = "SELECT DATE(date) AS order_date,COUNT(*) AS order_count,SUM(amount) AS order_amount_count FROM giftcard WHERE date BETWEEN '$from' AND '$to' && status = 'paid' GROUP BY order_date ORDER BY order_date ASC";
}

if($section=="giftcard"){
$sql_query = "SELECT DATE(date) AS order_date,COUNT(*) AS order_count,SUM(amount) AS order_amount_count FROM giftcard WHERE date BETWEEN '$from' AND '$to' && status = 'paid' GROUP BY order_date ORDER BY order_date ASC";
}

if($section=="voucher"){
$sql_query = "SELECT DATE(date) AS order_date,COUNT(*) AS order_count,SUM(total_amount) AS order_amount_count FROM voucher_orders WHERE date BETWEEN '$from' AND '$to' && pay_status = 'paid' GROUP BY order_date ORDER BY order_date ASC";
}

if($section=="rental"){
$sql_query = "SELECT DATE(date) AS order_date,COUNT(*) AS order_count,SUM(total_amount) AS order_amount_count FROM rentals WHERE date BETWEEN '$from' AND '$to' && paystatus = 'paid' GROUP BY order_date ORDER BY order_date ASC";
}



if ($section == "all") {
    $sql_query = "
        (
            SELECT DATE(date) AS order_date, COUNT(*) AS order_count, SUM(total_amount) AS order_amount_count
            FROM saloon_orders
            WHERE date BETWEEN '$from' AND '$to' AND pay_status = 'paid'
            AND section IN ('spa', 'refreshments', 'kitchen', 'academy')
            GROUP BY order_date
        )
        UNION ALL
        (
            SELECT DATE(start_date) AS order_date, COUNT(*) AS order_count, SUM(total_amount) AS order_amount_count
            FROM members
            WHERE date BETWEEN '$from' AND '$to' AND pay_status = 'paid'
            GROUP BY order_date
        )
        UNION ALL
        (
            SELECT DATE(date) AS order_date, COUNT(*) AS order_count, SUM(amount) AS order_amount_count
            FROM giftcard
            WHERE date BETWEEN '$from' AND '$to' AND status = 'paid'
            GROUP BY order_date
        )
        UNION ALL
        (
            SELECT DATE(date) AS order_date, COUNT(*) AS order_count, SUM(total_amount) AS order_amount_count
            FROM voucher_orders
            WHERE date BETWEEN '$from' AND '$to' AND pay_status = 'paid'
            GROUP BY order_date
        )
        UNION ALL
        (
            SELECT DATE(date) AS order_date, COUNT(*) AS order_count, SUM(total_amount) AS order_amount_count
            FROM rentals
            WHERE date BETWEEN '$from' AND '$to' AND paystatus = 'paid'
            GROUP BY order_date
        )
        ORDER BY order_date ASC";
}



    $resultset = mysqli_query($con, $sql_query) or die("database error:". mysqli_error($con));
    $tasks = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
        $tasks[] = $rows;
    }

    $heading = array('Date','Total Orders','Total Amount of Orders');
    $filename = "chbluxuryreportfor$section" . $from . "-" . $to . ".xls";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    ExportFile($tasks, $heading);
}

function ExportFile($records, $heading) {
    $headingWritten = false;
    if (!empty($records)) {
        foreach ($records as $row) {
            if (!$headingWritten) {
                // display field/column names as a first row
                echo implode("\t", $heading) . "\n";
                $headingWritten = true;
            }
            echo implode("\t", array_values($row)) . "\n";
        }
    }
    exit;
}
?>
