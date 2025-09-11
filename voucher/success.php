<?php if($reed != "cancelled"){ setcookie("voucherID", "", time()-3600); }  include"header.php";  ?>


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
  
if($reed=="cancelled")
{
echo "<h3>Payment Cancelled. Please try Again</h3><p><a href='../index.php' style='color:#FFC700;;'><u>Go To Home</u></a></p>";
} 


else 
{
$sql = "SELECT * from voucher_orders where orderid='$card' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
$username=$row["name"];
$email=$row["email"];
$phone=$row["phone"];
$dater=$row["date"];
$owner=$row["owner"];
$rname = $row['ownername'];
$remail = $row['owneremail'];
$rphone = $row['ownerphone'];
$total_all=$row["total_amount"];
$paid=$row["pay_status"];
}

			       
			       
			       


if($paid==""){
  

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
}
  
  
$insert = mysqli_query($con,"UPDATE voucher_orders SET pay_status='paid' where orderid='$card'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE voucher_orders SET status='valid' where orderid='$card'") or die ('Could not connect: ' .mysqli_error($con)); 

 

$sql = "SELECT * from voucher_cart where orderid='$card'";
$sql2 = mysqli_query($con, $sql);

// Check if there are rows in the result set
if (mysqli_num_rows($sql2) > 0) {
    $name = [];
    $price = [];
    $surname = [];
    $address = [];

    while ($row = mysqli_fetch_array($sql2)) {
        $name[] = $row['itemname'];
        $price[] = $row['price'];
        $surname[] = $row['quantity'];
        $address[] = $row['totalprice'];
    }

    $html = "<p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#fff;' width='500px'>
    <tr style='border-bottom:#FFFFFF solid;'><td>Voucher ID</td><td style='color:#FFC700;' colspan='3' >$card</td></tr>
     <tr><td style='color:#fff; text-align:center;'>Your Package(s)</td><td>Price</td><td>Quantity</td><td>Total</td></tr>
     ";
    
    foreach ($name as $key => $value) {
        $html .= "<tr>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>" . $name[$key] . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;" . $price[$key] . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>" . $surname[$key] . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;" . $address[$key] . "</td>
        </tr>";
    }
    
    $html .= "</table></p>";
} else {
    $html = "";
}


$sql = "SELECT * from voucher_cart where orderid='$card'";
$sql2 = mysqli_query($con, $sql);

// Check if there are rows in the result set
if (mysqli_num_rows($sql2) > 0) {
    $name = [];
    $price = [];
    $surname = [];
    $address = [];

    while ($row = mysqli_fetch_array($sql2)) {
        $name[] = $row['itemname'];
        $price[] = $row['price'];
        $surname[] = $row['quantity'];
        $address[] = $row['totalprice'];
    }

    $htmls = "<p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#fff;' width='500px'>
     <tr><td style='color:#fff; text-align:center;'>Package(s)</td><td>Price</td><td>Quantity</td><td>Total</td></tr>";
    
    foreach ($name as $key => $value) {
        $htmls .= "<tr>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>" . $name[$key] . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;" . $price[$key] . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>" . $surname[$key] . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;" . $address[$key] . "</td>
        </tr>";
    }
    
    $htmls .= "</table></p>";
} else {
    // No rows found, you can display a message here if needed
    $htmls = "";
}



 
 



$ownertable=$html;
$ownertext="<p style='color:white !important;'>Congratulations $username! You have succesfully purchased a e-gift spa package voucher at a total amount of &#8358;$total_all. 
<br>We look foward to serving you. Thank you for the patronage</p>";





if($owner=="1"){
$ownertable=$htmls;
$ownertext="<p style='color:white !important;'>Congratulations $username! You have succesfully purchased a e-gift spa package voucher at a total amount of &#8358;$total_all for recipient 
<br><span style='color:white;'>Name: $rname ||
Email: $remail. <br>Note: The recipient also gets an email. <br>Thank you for the patronage.</p>"; 
    

 ///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from=$sitemail;					 
	$email_to = $remail;
    $email_subject = "E-GIFT SPA  PACKAGE GIFTED! - $sitename";
    $email_message ="
	 <div style='background-color:#000000; color:#fff !important; padding:10px 20px;'>
	 <p style='text-align:left;'>
	 <img src='$siteimg' width='100px' height='60px;' style='margin-top:13px;'>
	 <font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>$dater</font></p>
	<p style='color:#fff; font-size:13px;'>Congratulations $rname! You have recieved a $sitename e-gift spa package voucher worth &#8358;$total_all from $username</p>
	<h4 style='color:#FFC700;'>E-GIFT SPA PACKAGE VOUCHER NO: $card</h4>
	$html
	<p style='color:#fff; font-size:13px;'>This e-gift spa package voucher can be used  to get free services at $sitename SPA</p> 
<br><br>
<p><a href='chbluxuryempire.com' style='color:#FFC700;'>$sitename</a></p>
</div>";
							 
	
   
       // create email headers
         $header =  'From: "'.$sitename.'" <'.$sitemail.'>'. "\r\n"; 
         $header .= "Cc: $sitemail \r\n";
         $header .=  'Reply-To: '.$sitemail.'' . "\r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";


if(!@mail($email_to, $email_subject, $email_message, $header)){
echo '<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';}}


//////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from=$sitemail;					 
	$email_to = $email;
    $email_subject = "E-GIFT SPA PACKAGE PURCHASE! - $sitename";
    $email_message ="
	<div style='background-color:#000000; color:#fff !important; padding:10px 20px;'>
	 <p style='text-align:left;'>
	 <img src='$siteimg' width='100px' height='60px;' style='margin-top:13px;'>
	 <font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>$dater</font></p>
	 $ownertext
	 $ownertable
	<p style='color:#fff; font-size:13px;'>This e-gift spa package voucher can be used  to get free services at $sitename SPA</p> 
<br><br>
<p><a href='chbluxuryempire.com' style='color:#FFC700;'>$sitename</a></p>
</div>";
							 
	
   
         // create email headers
         $header =  'From: "'.$sitename.'" <'.$sitemail.'>'. "\r\n"; 
         $header .= "Cc: $sitemail \r\n";
         $header .=  'Reply-To: '.$sitemail.'' . "\r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";


if(!@mail($email_to, $email_subject, $email_message, $header)){ echo '<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';}
    







	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $email_message = "";		
	$email_from=$sitemail;					 
	$email_to = $sitemail;
    $email_subject ="New E-GIFT SPA PACKAGE PURCHASE - $sitename";
    $email_message ="
      <div style='background-color:#000000; color:#fff; height:400px; padding:50px; width:500px;'>
      <p><img src='$siteimg' width='100px' height='60px' /></p><br><br>
     <p style='color:#fff !important;'>Hello Dear Admin,There has been a new e-gift spa package purchase.</p><br><br>
     <p style='text-align:center; color:#fff;'>
     <a href='https://chbluxuryempire.com/admin' style='color:#FFC700;text-decoration:underline;'>
    ADMIN DASHBOARD
    </a>
    </p>
   </div>";


   
 
    
    
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
<p>E-gift spa package voucher has been successfully purchased.</p>
<p style="font-size:17px; font-weight:700;">Details have to sent to submitted email address.
kindly check your email for more details.<br><span ><a href="https://chbluxuryempire.com/" style="color:#FFC700;" >Click here to Home</a><p>';  
die();   

}}?>
	

	
         
          </span><br><br>
     
 
       
          
		  
		</div>
        </div>
        </section><!-- End Pricing Section -->


























<?php include "footer.php";  ?>

