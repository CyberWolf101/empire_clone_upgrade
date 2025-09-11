<?php if($reed != "cancelled"){setcookie("academyID", "", time()-3600);}  include"header.php";  ?>


<style>
.section-title h2::after {
    content: "";
    position: absolute;
    display: block;
    width: 80px;
    height: 5px;
    background: none;}
    
    

.pricing h3 {
    font-weight: 500;
    margin: -20px -20px 0 -20px;
    padding: 0px 15px;
    font-size: 20px;
    font-weight: 600;
    color: #fff;
    background: none;
}
</style>
<!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#fff; background:none; padding:none;" align="center">
        <?php 
		  global $tx_ref;
 
$ref = $_GET['tx_ref'];
$reed = $_GET['status'];
$string=trim($ref);
$card=$string;
  
if($reed == "cancelled")
{
echo"<h3>Payment Cancelled. Please try Again</h3><p><a href='../index.php' style='color:#FFC700;;'><u>Go To Home</u></a></p>";
} 
else 
{


$sql = "SELECT * from saloon_orders where id='$card' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$paid=$row["pay_status"];
 $type=$row["bookingtype"]; 
 $kit=$row["saloonkit"];
 $username=$row["name"];
 $c_phone=$row["phone"];
 $c_email=$row["email"];
 $status=$row["status"];
}

if($paid==""){
    
    
if(isset($_GET['transaction_id'])){
$method="Card";
}else{
$method="Giftcard";
}

//check giftcard
$sql = "SELECT * FROM giftcard_history WHERE orderid = '$card' AND status='processing'";
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
$insert = mysqli_query($con,"UPDATE saloon_orders SET gift_amount='$amount_deducted' where id='$card'") or die ('Could not connect: ' .mysqli_error($con));
$method="Card,Giftcard";
}



$insert = mysqli_query($con,"UPDATE saloon_orders  SET pay_status='paid' where id='$card'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE saloon_orders  SET status='processed' where id='$card'") or die ('Could not connect: ' .mysqli_error($con));  
$insert = mysqli_query($con,"UPDATE saloon_orders SET method='$method' where id='$card'") or die ('Could not connect: ' .mysqli_error($con)); 



 
 


$sql = "SELECT * from academy_cart where id='$card'";
$sql2 = mysqli_query($con, $sql);

// Check if there are rows in the result set
if (mysqli_num_rows($sql2) > 0) {
    $name = [];
    $price = [];
    $surname = [];
    $address = [];

    while ($row = mysqli_fetch_array($sql2)) {
        $name[] = $row['trainingname'];
        $price[] = $row['durationname'];
        $surname[] = $row['price'];
    }

    $html = "<p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#fff;' width='500px'>
    <tr style='border-bottom:#FFFFFF solid;'><td>Academy ID</td><td style='color:#FFC700;' colspan='2' >$card</td></tr>
     <tr><td style='color:#fff; text-align:center;'>Training</td><td>Duration</td><td>Price</td></tr>
     ";
    
    foreach ($name as $key => $value) {
        $html .= "<tr>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>" . $name[$key] . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>" . $price[$key] . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;" . $surname[$key] . "</td>
        </tr>";
    }
    
    $html .= "</table></p>";
} else {
    // No rows found, you can display a message here if needed
    $html = "";
}



 
 



    ///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from=$sitemail;					 
	$email_to = $c_email;
    $email_subject = "Training Registered (CHBLUXURY ACADEMY) - $sitename";
    $email_message ="
	<div style='background-color:#000000; color:#fff !important; padding:10px 20px; '>
	 <p style='text-align:left;'>
	 <img src='$siteimg' width='100px' height='60px;' style='margin-top:13px;'>
	 <font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>Date: $dater</font></p>

		<h5>TRAINING DETAILS</h5>
		<p style='color:white;'>$username<br>$c_email<br>$c_phone</p>
	    <p style='color:white;'>Hello Dear Customer,Here is your Academy ID which will be provided to the receptionist on arrival to process your services.</p>
         $html
	
	    <p style='color:#fff; font-size:13px;'>Thank you for your patronage and we hope to see you again soon!</p> 

 

 
 
<br><br>
<p style='text-align:center;'><a href='chbluxuryempire.com' style='color:#FFC700;'>$sitename</a></p>
</div>";
							 
	
   
       // create email headers
         $header =  'From: "'.$sitename.'" <'.$sitemail.'>'. "\r\n"; 
         $header .= "Cc: $sitemail \r\n";
         $header .=  'Reply-To: '.$sitemail.'' . "\r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";


if (!@mail($email_to, $email_subject, $email_message, $header)) {
    $error = error_get_last();
    echo 'Mail Error: ' . $error['message'];
} 
////////////////////////////////////////////End mail Function////////////////////////////////////////////////////////////// 

	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $email_message = "";		
	$email_from=$sitemail;					 
	$email_to = $sitemail;
    $email_subject ="New Training Registeration - $sitename";
    $email_message ="
      <div style='background-color:#000000; color:#fff; height:400px; padding:50px; width:500px;'>
      <p><img src='$siteimg' width='100px' height='60px' /></p><br><br>
     <p style='color:#fff !important;'>Hello Dear Admin,There has been a new chb luxury academy registeration.Login to your dashboard to view</p><br><br>
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


 
    
header('Refresh:50; url=../index.php');
echo'<h2>PAYMENT SUCCESSFUL</h2>
<p>Here is your Academy ID - <span style="font-size:large; text-transform:none; color:#FFC700;">'.$ref .'</span><br></p>
<p style="font-size:17px; font-weight:700;">Your training details have been sent to you.
kindly check your email<br><span ><a href="https://chbluxuryempire.com/" style="color:#FFC700;" >Click here to Home</a><p>';
die();     

   
    
}}?>
	

	
         
          </span><br><br>
     
 
       
          
		  
		</div>
        </div>
        </section><!-- End Pricing Section -->


























<?php include "footer.php";  ?>
