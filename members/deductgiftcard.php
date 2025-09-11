<?php

include "../connect.php"; 
$giftcard=$_POST['giftcard'];
$order=$_POST['orderno'];

$date=date("Y-m-d H:i:s");

//select order
$sql = "SELECT * from members where cardno='$order'";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$order_amount=$row["total_amount"];}





//check giftcard
$sql = "SELECT * FROM giftcard WHERE giftcardno = '$giftcard'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
while ($row = mysqli_fetch_array($result)) {
$giftcard_amount=$row['amount_left'];
}



//check for any pending transaction
$sqb = "SELECT * FROM giftcard_history WHERE giftcardno='$giftcard' AND status='processing'";
$results = mysqli_query($con, $sqb);
if (mysqli_num_rows($results) > 0) {
while ($rows = mysqli_fetch_array($results)) {
$giftcardpending_amount=$rows['amount_left'];
} $giftcard_amount=$giftcardpending_amount; }





//insufficient funds = 0
if($giftcard_amount <=0){
echo "Insufficient Funds! Please pay with bank card instead";    
}





//all was substracted /success
else if($giftcard_amount >= $order_amount){
$amount_left=$giftcard_amount - $order_amount;
$insert = mysqli_query($con,"UPDATE giftcard SET amount_left='$amount_left' where  giftcardno='$giftcard'") or die ('Could not connect: ' .mysqli_error($con));
$submit = mysqli_query($con,"insert into giftcard_history(giftcardno,orderid,amount_deducted,amount_left,date,status)
values ('$giftcard','$order','$order_amount','$amount_left','$date','processed')") or die ('Could not connect: ' .mysqli_error($con));
echo "success";
}







//half succcess
else if($giftcard_amount < $order_amount){
$submit = mysqli_query($con,"insert into giftcard_history(giftcardno,orderid,amount_deducted,amount_left,date,status)
values ('$giftcard','$order','$giftcard_amount','0','$date','processing')") or die ('Could not connect: ' .mysqli_error($con));
echo "half-success";
}




}else{
// Gift card number does not exist in the table
echo "Invalid Giftcard Details";
}







































?>