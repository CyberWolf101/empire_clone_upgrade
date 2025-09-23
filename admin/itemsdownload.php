<?php
include "../connect.php";

if(isset($_POST["ExportType"])) {
    $from = $_POST['from'];
    $to = $_POST['to'];



$sql_query = "SELECT r.item, SUM(r.quantity) as quantity, SUM(r.totalprice) as total FROM refreshments r INNER JOIN saloon_orders s ON s.id=r.orderid WHERE s.pay_status='paid' AND s.date BETWEEN '$from' AND '$to' GROUP BY r.item ORDER BY r.item ASC";
    


    $resultset = mysqli_query($con, $sql_query) or die("database error:". mysqli_error($con));
    $tasks = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
    $tasks[] = $rows;
    }

    $heading = array('Stock','Quantity Sold','Total Amount');
    $filename = "orishirishistocks" . $from . "-" . $to . ".xls";

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
