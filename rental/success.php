<?php if($reed != "cancelled"){setcookie("rentalID", "", time()-3600);}  include"header.php";  ?>


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
  
if ($reed == "cancelled")
{
echo"<h3>Payment Cancelled. Please try Again</h3><p><a href='../index.php' style='color:#FFC700;;'><u>Go To Home</u></a></p>";
} 
else 
{


$sql = "SELECT * from rentals where orderid='$card' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$paid=$row["paystatus"];}

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
    
    
    
    
$insert = mysqli_query($con,"UPDATE rentals  SET paystatus='paid' where orderid='$card'") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE rentals  SET status='processed' where orderid='$card'") or die ('Could not connect: ' .mysqli_error($con)); 

 
    // Split the string into an array of dates
    $datesArray = explode(',', $alldates);

    // Iterate through the array and insert each date into a separate column
    foreach ($datesArray as $date) {
        // Insert $date into your desired column in the database
        $insertSql = "INSERT INTO rental_dates (orderid,date) VALUES ('$card','$date')"; // Replace with your column name
        mysqli_query($con, $insertSql);
    }
   
 
 
 


$sql = "SELECT * from rental_cart where id='$card'";
$sql2 = mysqli_query($con, $sql);

// Check if there are rows in the result set
if (mysqli_num_rows($sql2) > 0) {
    $name = [];
    $price = [];
    $surname = [];
    $address = [];

    while ($row = mysqli_fetch_array($sql2)) {
        $name[] = $row['itemname'];
        $price[] = $row['itemprice'];
        $surname[] = $row['days'];
        $address[] = $row['total'];
    }

    $html = "<p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#fff;' width='500px'>
    <tr style='border-bottom:#FFFFFF solid;'><td>Rental ID</td><td style='color:#FFC700;' colspan='3' >$card</td></tr>
    <tr style='border-bottom:#FFFFFF solid;'><td>Approved Date(s)</td><td style='color:#FFC700;' colspan='3' >$alldates</td></tr>
     <tr><td style='color:#fff; text-align:center;'>Your Items</td><td>Price</td><td>Duration(days)</td><td>Total</td></tr>
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
    // No rows found, you can display a message here if needed
    $html = "";
}



 
 

$sql = "SELECT * from rentals  where orderid='$card' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
$name=$row["name"];
$email=$row["email"];
$phone=$row["phone"];
$dater=$row["date"];
$total_all=$row["total_amount"];


    ///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from=$sitemail;					 
	$email_to = $email;
    $email_subject = "Rental Package! - $sitename";
    $email_message ="
	<div style='background-color:#000000; color:#fff !important; padding:10px 20px; '>
	 <p style='text-align:left;'>
	 <img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='60px;' style='margin-top:13px;'>
	 <font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>Date: $dater</font></p>

		<h5>RENTAL DETAILS</h5>
		<p style='color:white;'>$name<br>$email<br>$phone</p>
	    <p style='color:white;'>Hello Dear Customer,Here is your Rental ID which will be provided to the receptionist on arrival.Here are your purchased items</p>
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



if(!@mail($email_to, $email_subject, $email_message, $header)){
echo '<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';}}

////////////////////////////////////////////End mail Function////////////////////////////////////////////////////////////// 

	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $email_message = "";		
	$email_from=$sitemail;					 
	$email_to = $sitemail;
    $email_subject ="New Rental Order - $sitename";
    $email_message ="
      <div style='background-color:#000000; color:#fff; height:400px; padding:50px; width:500px;'>
      <p><img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='60px' /></p><br><br>
     <p style='color:#fff !important;'>Hello Dear Admin,There has been a new rental order.Login to your dashboard to view</p><br><br>
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
<p>Here is your Rental ID - <span style="font-size:large; text-transform:none; color:#FFC700;">'.$ref .'</span><br></p>
<p style="font-size:17px; font-weight:700;">Your rental details have been sent to you.
kindly check your email<br><span ><a href="https://chbluxuryempire.com/" style="color:#FFC700;" >Click here to Home</a><p>';
die();     

   
    
}}?>
	

	
         
          </span><br><br>
     
 
       
          
		  
		</div>
        </div>
        </section><!-- End Pricing Section -->


























<?php include "footer.php";  ?>
