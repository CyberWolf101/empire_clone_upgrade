<?php



error_reporting(E_ALL);
ini_set(option: 'display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


?>


<?php
try {
    $result = mysqli_query($con, "YOUR QUERY HERE");

    $submit = mysqli_query($con, "insert into saloon_orders(
    id,name,email,phone,bookingtype,method,pay_status,status,date,
    saloonkit,total_amount,card_amount,cash_amount,transfer_amount,pos_amount,
    type,section,preorder,preorder_date,giftcard,gift_amount
) values (
    '$ran','','','','','','','','$date',
    '','','','','','',
    'online','refreshments','0','',
    0,0
)") or die('Could not connect: ' . mysqli_error($con));

} catch (mysqli_sql_exception $e) {
    echo "MySQL Error: " . $e->getMessage();
    exit; // Stop execution
}


// super admin
if ($_SESSION['user'] === 'superadmin') {

}
?>