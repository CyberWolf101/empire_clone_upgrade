<?php
include "../connect.php";
$order = $_GET['order'];
if ($order == '') {
    $toDelete = [
        ["table"=>"saloon_orders","column"=>"id"],
        ["table"=>"academy_cart","column"=>"id"],
        ["table"=>"academy_cart_training_items","column"=>"item_for"]
    ];
    foreach($toDelete as $toDel){
        $sql = "DELETE FROM {$toDel['table']} WHERE {$toDel['table']} = '$order'";
        mysqli_query($con, $sql);
    }
}
header("Location: academybooking.php");
exit();
?>