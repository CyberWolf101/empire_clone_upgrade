<?php include"header.php";  ?>


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
  
if ($reed == "cancelled")
{
 echo "<h3>Payment Cancelled. Please try Again</h3><p><a href='index.php' style='color:#FFC700;;'><u>Go To Home</u></a></p>";
} 
else 
{
			       
			       
echo'<h2>PAYMENT SUCCESSFUL</h2>
<p style="font-size:14px; font-weight:500;">Hey,there. Congratulations! Your payment was successful. You have successfully purchased a giftcard.
kindly check your email for more details<br><a href="https://chbluxuryempire.com/" style="color:#FFC700;" >Click here to Home</a></p>';



				
$sql = "SELECT * FROM giftcard WHERE giftcardno='$string'";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{  
$emails = $row['senderemail'];
$name = $row['sendername'];
$rname = $row['ownername'];
$remail = $row['owneremail'];
$notes = $row['notes'];
$amount = $row['amount'];
$status = $row['status'];
$owner = $row['owner'];
}

if($notes==""){
$note="None";
}else{
$note="<h4 style=' font-size:16px;'>($notes)</h4>";
}


$date=date("Y-m-d");					
 
if($status!="paid"){
$insert = mysqli_query($con,"UPDATE giftcard SET status='paid' where giftcardno='$string'") or die ('Could not connect: ' .mysqli_error($con)); 
if($owner==1){
     ///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from=$sitemail;					 
	$email_to = $remail;
    $email_subject = "$sitename E-GIFTCARD SERVICE";
    $email_message ="
	 <center><div style='background-color:#000000; color:#fff !important; padding:10px 20px;'>
	 <p style='text-align:left;'>
	 <img src='$siteimg' width='100px' height='60px;' style='margin-top:13px;'>
	 <font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>$date</font></p>
	
	
	
	<p><img src='http://chbluxuryempire.com/gift.png'  style='width:30%; height:auto;'/></p>
	<p style='color:#fff; font-size:13px;'>Congratulations $rname! You have recieved a $sitename giftcard worth &#8358;$amount from $name</p>
	<h4 style='color:#FFC700;'>E-GIFTCARD VOUCHER NO<br> $string</h4>
	$note
	
	<p style='color:#fff; font-size:13px;'>This e-giftcard can be used to purchase products and services from $sitename WEBSITE</p> 

 

 
 
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
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from=$sitemail;					 
	$email_to = $emails;
    $email_subject = "$sitename E-GIFTCARD SERVICE";
    $email_message="
	 <center><div style='background-color:#000000; color:#fff !important; padding:10px 20px;'>
	 <p style='text-align:left;'>
	 <img src='$siteimg' width='100px' height='60px;' style='margin-top:13px;'>
	 <font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>$date</font></p>
	
	
	
	<p><img src='http://chbluxuryempire.com/gift.png' style='width:30%; height:auto;'/></p>
	<p style='color:#FFC700; font-size:13px;'>Congratulations $name! You have successfully purchased a $sitename e-giftcard worth &#8358;$amount. Details are included below</p> 
	
<p><table bordercolor='#000000' cellpadding='10' style='color:#FFFFFF;' width='500px'>
<tr style='border-bottom:#FFFFFF solid; color:#fff; font-size:14px; font-weight:500;'>
<td>Recipient Name</td><td style=' font-size:14px; font-weight:500;' colspan='2' >$rname</td></tr>
<tr style='border-bottom:#FFFFFF solid; color:#fff; font-size:14px; font-weight:500;'>
<td>Recipient Email Address</td><td style=' font-size:14px; font-weight:500;' colspan='2' >$remail</td></tr>
<tr style='border-bottom:#FFFFFF solid; color:#fff; font-size:14px; font-weight:500;'>
<td>Giftcard Worth</td><td style=' font-size:14px; font-weight:500;' colspan='2' >&#8358;$amount</td></tr>
<tr style='border-bottom:#FFFFFF solid; color:#fff; font-size:14px; font-weight:500;'>
<td>Message</td><td style=' font-size:14px; font-weight:500;' colspan='2' >$note</td></tr>
</table></p> 


 
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
echo '<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';}}
else{
    

   ///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from = $sitemail;				 
	$email_to = $remail;
    $email_subject = "$sitename E-GIFTCARD SERVICE";
    $email_message ="
	 <center><div style='background-color:#000000; color:#fff !important; padding:10px 20px;'>
	 <p style='text-align:left;'>
	 <img src='$siteimg' width='100px' height='60px;' style='margin-top:13px;'>
	 <font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>$date</font></p>
	
	
	
	<p><img src='http://chbluxuryempire.com/gift.png'  style='width:30%; height:auto;'/></p>
	<p style='color:#fff; font-size:13px;'>Congratulations $rname! You have successfully purchased a $sitename giftcard worth &#8358;$amount</p>
	<h4 style='color:#FFC700;'>E-GIFTCARD VOUCHER NO<br> $string</h4>
	<p style='color:#fff; font-size:13px;'>This e-giftcard can be used to purchase products and services from $sitename WEBSITE</p> 

 

 
 
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
echo '<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';}}
////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////    


	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $email_message = "";		
	$email_from=$sitemail;					 
	$email_to = $sitemail;
    $email_subject ="New Giftcard Purchase - $sitename";
    $email_message ="
      <div style='background-color:#000000; color:#fff; height:400px; padding:50px; width:500px;'>
      <p><img src='$siteimg' width='100px' height='60px' /></p><br><br>
      <p style='color:#fff !important;'>Hello Dear Admin,There has been a new e-giftcard purchase. Login to your dashboard to view</p><br><br>
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


header('Refresh:60; url=index.php');
////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////









	
}}
?>
	

	
         
          </span><br><br>
     
 
       
          
		  
		</div>
        </div>
        </section><!-- End Pricing Section -->


























<?php include "footer.php";  ?>
