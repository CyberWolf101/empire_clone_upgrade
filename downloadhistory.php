<?php
include "connect.php";

if(isset($_POST["submitdetails"])) {
    $giftcard = $_POST['giftcard'];
   
   
//check giftcard
$sql = "SELECT * FROM giftcard WHERE giftcardno = '$giftcard'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
while ($row = mysqli_fetch_array($result)) {
$own=$row['ownername'];
}
    
$sql = "SELECT * FROM giftcard_history WHERE giftcardno = '$giftcard' AND status='processed'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
while ($row = mysqli_fetch_array($result)) {
}}
else{
    echo "<script>alert('No Transactions Found!'); window.location.href = 'giftcard_history.php';</script>";
    exit(); // Make sure to exit the script after the alert
}


}else{
    echo "<script>alert('Invalid giftcard details!Try again.'); window.location.href = 'giftcard_history.php';</script>";
    exit(); // Make sure to exit the script after the alert
}



$sql_query = "SELECT date,amount_deducted,amount_left,orderid,description FROM giftcard_history WHERE giftcardno='$giftcard' AND status='processed' ";
    


    $resultset = mysqli_query($con, $sql_query) or die("database error:". mysqli_error($con));
    $tasks = array();
    while ($rows = mysqli_fetch_assoc($resultset)) {
    $tasks[] = $rows;
    }

    $heading = array('Date','Amount Deducted','Amount Left','Transaction ID','Description');
    $filename = $owner."e-giftcard.xls";

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
