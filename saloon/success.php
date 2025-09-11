<?php  if($reed != "cancelled"){setcookie("saloonID", "", time()-3600);}  include"header.php";  ?>


<style>
.section-title h2::after {
    content: "";
    position: absolute;
    display: block;
    width: 80px;
    height: 5px;
    background: none;}
</style>
<!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#FFFFFF;" align="center">
        <?php 
		  global $tx_ref;
 
$ref = $_GET['tx_ref'];
$reed = $_GET['status'];
$string=trim($ref);
$saloon=$string;
  
if ($reed == "cancelled")
{
echo "<h3>Payment Cancelled. Please try Again</h3><p><a href='../index.php' style='color:#FFC700;;'><u>Go To Home</u></a></p>";
} 
else 
{

if(isset($_GET['transaction_id'])){
$method="Card";
}else{
$method="Giftcard";
}

//check giftcard
$sql = "SELECT * FROM giftcard_history WHERE orderid = '$saloon' AND status='processing'";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
while ($row = mysqli_fetch_array($result)) {
$giftcard=$row['giftcardno'];
$amount_deducted=$row['amount_deducted'];
$thegifttransaction=$row['s'];
}
$insert = mysqli_query($con,"UPDATE giftcard_history SET status='processed' where s='$thegifttransaction'") or die ('Could not connect: ' .mysqli_error($con));

//amount_left
$sql = "SELECT * FROM giftcard WHERE giftcardno = '$giftcard'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
while ($row = mysqli_fetch_array($result)) {
$giftcard_real_amount=$row['amount'];
}}



    $sqlw = "SELECT sum(amount_deducted) as total_shop FROM giftcard_history WHERE giftcardno ='$giftcard' AND status='processed'";
    $sql2w = mysqli_query($con, $sqlw);
    while ($ron = mysqli_fetch_array($sql2w)) {
        $whole_shop = $ron['total_shop'];
        $giftcard_amountleft = $gifcard_real_amount - $whole_shop;
    }
$insert = mysqli_query($con,"UPDATE giftcard SET amount_left='$giftcard_amountleft' where giftcardno='$giftcard'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE saloon_orders SET gift_amount='$amount_deducted' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con));
$method="Card,Giftcard";
}



//saloon orders
$sql = "SELECT * from saloon_orders where id='$saloon'";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
 $saloon=$row["id"];
 $status=$row["pay_status"];
 $type=$row["bookingtype"]; 
 $kit=$row["saloonkit"];
 $username=$row["name"];
 $c_phone=$row["phone"];
 $c_email=$row["email"];
 $status=$row["status"];
}






if($status==""){
$insert = mysqli_query($con,"UPDATE saloon_orders SET pay_status='paid' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE saloon_orders SET status='processed' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE appointments SET status='processed' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE refreshments SET status='processed' where orderid='$saloon'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE saloon_orders SET method='$method' where id='$saloon'") or die ('Could not connect: ' .mysqli_error($con)); 


//update stocks
$sqk = "SELECT * from refreshments where orderid='$saloon'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp))
{
$food = $rowe['itemid'];
$value = $rowe['quantity'];


$sql = "SELECT * from food_menu where s='$food'";
$sql = mysqli_query($con,$sql);
while($rowe = mysqli_fetch_array($sql2))
{ $origianlvalue = $rowe['quantity']; }

$rem_value=$originalvalue-$value;
$insert = mysqli_query($con,"UPDATE food_menu SET quantity='$rem_value' where s='$food'") or die ('Could not connect: ' .mysqli_error($con)); 
$submit = mysqli_query($con,"insert into stock_log(id,action,value,date) values ('$food','minus','$value','$datetime')") or die ('Could not connect: ' .mysqli_error($con));	    
}    
 
 
 

$sql = "SELECT * FROM appointments where id='$saloon'";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{  
$service[] = $row['servicename'];
$unitprice[] = $row['price'];
$starttime[] = $row['start_time'];
$endtime[] = $row['end_time'];
$servicedate[] = $row['date'];
$staff[] = $row['staffname'];
}
			
			
			
foreach ($service as $keys => $value) {
   $booked_services .= "<tr><td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $service[$keys]. "</td>
    <td  style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;" . $unitprice[$keys]. "</td>
    <td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $servicedate[$keys]. " </td>
    <td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $starttime[$keys]. " - " . $endtime[$keys]. " </td>
    <td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $staff[$keys]. "</td></tr>";
}

 
 
 
 


$sql = "SELECT * from refreshments where orderid='$saloon'";
$sql2 = mysqli_query($con, $sql);

// Check if there are rows in the result set
if (mysqli_num_rows($sql2) > 0) {
    $name = [];
    $surname = [];
    $address = [];

    while ($row = mysqli_fetch_array($sql2)) {
        $name[] = $row['item'];
        $surname[] = $row['quantity'];
        $address[] = $row['totalprice'];
    }

    $html = "<p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#fff;' width='500px'>
     <tr><td style='color:#fff; text-align:center;'>Your Items</td><td>Quantity</td><td>Price</td></tr>";
    
    foreach ($name as $key => $value) {
        $html .= "<tr>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>" . $name[$key] . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>" . $surname[$key] . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;" . $address[$key] . "</td>
        </tr>";
    }
    
    $html .= "</table></p>";
} else {
    // No rows found, you can display a message here if needed
    $html = "";
}


if($kit > 0){
$showkit="<tr><td style='color:#FFC700; font-size:14px; font-weight:500;>Pedicure Spa Kit</td>
<td colspan='4' style='color:#FFC700; font-size:14px; font-weight:500;>&#8358; $kit </td><tr>";
}

 
 

//send mail if email exists  
if($c_email!=""){

    ///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from=$sitemail;					 
	$email_to = $c_email;
    $email_subject = "Booking Successful - $sitename";
    $email_message ="
	 <center><div style='background-color:#000000; color:#fff !important; padding:10px 20px; width:500px;'>
	 <p style='text-align:left;'>
	 <img src='$siteimg' width='100px' height='60px;' style='margin-top:13px;'>
	 <font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>Total Cost: &#8358;$total_all <br>$date</font></p>
	
	
	

	<p style='color:#fff; font-size:13px;'>Hey there, $username,You have successfully paid for your booking service. ,attached below is your booking details</p>
	<p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#FFFFFF;' width='500px'>
    <tr style='border-bottom:#FFFFFF solid;'><td>Booking ID</td><td style='color:#FFC700;' colspan='4' >$saloon</td></tr>
    <tr><td  style='color:#fff; text-align:center;'>Service</td><td>Price</td><td>Date</td><td>Duration</td><td>Staff</td></tr>
    $booked_services
    $showkit
    </table>
    </p> 
    
    $html
	
	<p style='color:#fff; font-size:13px;'>Thank you for your patronage and we hope to see you again soon!</p> 

 

 
 
<br><br>
<p style='text-align:center;'><a href='chbluxuryempire.com' style='color:#FFC700;'>$sitename</a></p>
</div></center>";
							 
	
   
      // create email headers
         $header =  'From: "'.$sitename.'" <'.$sitemail.'>'. "\r\n"; 
         $header .= "Cc: $sitemail \r\n";
         $header .=  'Reply-To: '.$sitemail.'' . "\r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";



if(!@mail($email_to, $email_subject, $email_message, $header)){
echo '<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';}
////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////  

	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $email_message = "";		
	$email_from=$sitemail;					 
	$email_to = $sitemail;
    $email_subject ="New Saloon Booking - $sitename";
    $email_message ="
      <div style='background-color:#000000; color:#fff; height:400px; padding:50px; width:500px;'>
      <p><img src='$siteimg' width='100px' height='60px' /></p><br><br>
     <p style='color:#fff !important;'>Hello Dear Admin,There has been new online saloon booking.Login to your dashboard to view</p><br><br>
     <p style='text-align:center; color:#fff;'>
     <a href='https://chbluxuryempire.com/admin' style='color:#FFC700;text-decoration:underline;'>
    ADMIN DASHBOARD
    </a>
    </p>
 </div>
	
	


";


   
 
    
    
    // create email headers
         $header =  'From: "'.$sitename.'" <'.$sitemail.'>'. "\r\n"; 
         $header .= "Cc: $sitemail \r\n";
         $header .=  'Reply-To: '.$sitemail.'' . "\r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";


 
if(!@mail($email_to, $email_subject, $email_message, $header)){
echo '<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';}
}} 
    
    

header('Refresh:60; url=../index.php');
echo'<h2>PAYMENT SUCCESSFUL</h2>
<p>Here is your booking ID - <span style="font-size:large; text-transform:none; color:#FFC700;">'.$ref .'</span><br></p>
<p style="font-size:17px; font-weight:700;">Your appointment No have been sent to you which will be provided for the receptionist upon arrival.
kindly check your email<br><span ><a href="https://chbluxuryempire.com/" style="color:#FFC700;" >Click here to Home</a><p>';
die();   
    
}?>
	

	
         
          </span><br><br>
     
 
       
          
		  
		</div>
        </div>
        </section><!-- End Pricing Section -->


























<?php include "footer.php";  ?>
