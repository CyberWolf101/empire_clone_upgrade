<?php
include "header.php";
function getCustomer($con,$id){
    $sql = "SELECT * FROM customers WHERE unique_id = '$id'";
    $stmt = mysqli_query($con,$sql);
    if(mysqli_fetch_array($stmt)){
        return true;
    }

}
if(isset($_GET["customer_id"]) && getCustomer($con, $_GET["customer_id"])){
    ?>
    
    <?php
}else{
    ?>
    An error occured
    <?php
}