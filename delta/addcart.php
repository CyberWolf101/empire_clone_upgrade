<?php 
include "connect_to_mysqli.php";


 $ran=$_POST['idea'];
 
 
$dear=date("Y-m-d");


 if(isset($_POST['add'])){
 $meals=$_POST['add'];
 $qual=$_POST['quanties'];
 

$sqk = "SELECT all* from baby where id='$meals'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp))
				  {
				 $see = $rowe['name'];
			   	 $per = $rowe['price'];
				 $ter = $rowe['time'];
				 }



//Check if item exists
$extract_user = mysqli_query($con,"SELECT * FROM delta_cart WHERE order_id='$ran' && meal_id='$see'");
$count = mysqli_num_rows($extract_user);
if ($count > 0) {
    

$sqv = "SELECT * FROM delta_cart WHERE order_id='$ran' && meal_id='$see'";
$sq2v = mysqli_query($con,$sqv);
while($roe = mysqli_fetch_array($sq2v))
{
 $id=$roe['s'];
 $q=$roe['quantity'];

}

$newquantity=((int)$q + (int)$quantity);
				      
//Update Quantity
 $insert = mysqli_query($con,"UPDATE delta_cart SET quantity = quantity + $qual  where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
}



else{
 $submit = mysqli_query($con,"insert into delta_cart(order_id,meal_id,price,duration,quantity,date, name, phone, email, method, status,app) values 
 ('$ran','$see','$per','$ter','$qual','$dear','','','','Card','unpaid','yes')") or die ('Could not connect: ' .mysqli_error($con));
}
}

// protein

 if(isset($_POST['food'])){
     
    $meal=$_POST['food'];
   $price=$_POST['price'];
 $tam=$_POST['time'];
 $saw=$_POST['namesy'];

 $quant=$_POST['quant'];

 
    foreach ($meal as $index => $product) {
           
   $prices=$price[$index];
 $tams=$tam[$index];
 $saws=$saw[$index];

 $quants=$quant[$index];
       
 
//Check if item exists

$extract_user = mysqli_query($con,"SELECT * FROM delta_cart WHERE order_id='$ran' && meal_id='$saws'");
$count = mysqli_num_rows($extract_user);
if ($count > 0) {
    

$sq= "SELECT * FROM delta_cart WHERE order_id='$ran' && meal_id='$saws'";
$srv = mysqli_query($con,$sq);
while($roe = mysqli_fetch_array($srv))
{
 $id=$roe['s'];


}

 $insert = mysqli_query($con,"UPDATE delta_cart SET quantity = quantity + $quants where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 

}




    
   else {
    
 $submit = mysqli_query($con,"insert into delta_cart(order_id,meal_id,price,duration,quantity,date, name, phone, email, method, status,app) values 
 ('$ran','$saws','$prices','$tams','$quants','$dear','','','','Card','unpaid','yes')") or die ('Could not connect: ' .mysqli_error($con));
   

}
}
}


	$sql = "SELECT count(*) As 'total' FROM delta_cart where order_id='$ran'";
		 $sql2 = mysqli_query($con,$sql);
		 $dad = mysqli_fetch_assoc($sql2);
		 $kany=$dad['total'];
         echo $kany;
         
         
 ?>
