<?php

// Include your database connection here
include "../connect.php"; 


if (isset($_POST['shop'])) {
  $selectedShop = $_POST['shop'];
  $thedate = $_POST['date'];

  
  //fetch quantity
$sql = "SELECT * from chb_inventory where product='$selectedShop'";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
$maxquantity=$row['inventory'];
} 

echo '<label id="packsLabel">Quantity Deducted</label><input type="number"  max="'.$maxquantity.'" class="form-control" name="quantity" required /><br />';
}

?>
