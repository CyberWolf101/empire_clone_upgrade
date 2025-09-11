<?php  ob_start();
session_start(); ?><!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Chbluxuryempire</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="main/assets/img/favicon.png" rel="icon">
  <link href="main/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="main/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="main/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="main/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="main/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="main/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="main/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="main/assets/css/style.css" rel="stylesheet">



<script src="main/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="main/assets/vendor/jquery/jquery.min.js"></script>


<script>
    $(document).ready(function(){
        $("#myModal").modal('show');
    });
</script>


  <!-- =======================================================
  * Template Name: Knight - v4.3.0
  * Template URL: https://bootstrapmade.com/knight-free-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
<!---...This is for Microlashing section - single.. --->

<style>


.ter{
background-color:#fff;
margin-bottom:10px;
outline:none;
border:none;
padding:10px;

}


span{

font-size:14px;
font-weight:900;


}
.img{
max-width:30%;
max-height:30%;
border-radius:50%;
background-color:#000000;
}
/* My  */
.submitn{
  
 background:#FFC700;
  color: #fff;
  border-radius: 5px;
  padding: 10px 30px 11px 30px;
  font-size: 14px;
  font-weight: 600;
  outline:none;
  width:300px;
 
}

.submitn:hover{
  background: #000000;
  color: #fff;
}

.vertical-alignment-helper {
display:table;
height: 100%;
width: 100%;
pointer-events:none;}

.vertical-align-center {
/* To center vertically */
display: table-cell;
vertical-align: middle;
pointer-events:none;}


.close{
color:#0000FF;
text-shadow:none;
opacity:1;
font-size:30px;
}


</style>
 <?php
 session_start();
?>







  <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#FFFFFF;" align="center">
          
		   <?php 
		  global $tx_ref;
 
$ref = $_GET['tx_ref'];
$reed = $_GET['status'];
 
   
   
      
  include "connect_to_mysqli.php";
            $string=trim($ref);
  
		    if ($reed == "cancelled")
			     {
			  echo "<h3>Payment Cancelled. Please try Again</h3><p><a href='index.php' style='color:#FFC700;;'><u>Go To Home</u></a></p>";
				  } 
			 else 
			  {
			       header('Refresh:3; url=index.php');
			       echo'<h2>PAYMENT SUCCESSFUL</h2>
          <p>
		  Here is your PickUp No- <span style="font-size:large; text-transform:none; color:#FFC700;">'.$string.' </span><br></p>
<p style="font-size:14px; font-weight:500;">Your PickUp No have been sent to you which will be provided for the store upon arrival to get your items .
kindly check your email<br><span ><a href="https://chbluxuryempire.com/" style="color:#FFC700;" >Click here to Home</a><p>';


			$sql = "SELECT sum(priced) from enter where orderid='$string' ";
$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
			 {

$k=$row[0];

$price=$k;
	
			}	
			
			 $sql = "SELECT * FROM enter where orderid='$string' ";
                      $sql2 = mysqli_query($con,$sql);
		   while($row = mysqli_fetch_array($sql2))
			  {  
				
			$name[] = $row['id'];
				$surname[] = $row['no'];
					$address[] = $row['priced'];
			}
			
			
			
foreach ($name as $key => $value) {

    $html .= "<tr><td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $name[$key]. "</td><td  style='color:#FFC700; font-size:14px; font-weight:500;'>" . $surname[$key]. "</td>
    <td  style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;" . $address[$key]. "</td></tr>";
}
			
				
		 $sql = "SELECT * FROM foods WHERE id='$string'";
                      $sql2 = mysqli_query($con,$sql);
		   while($row = mysqli_fetch_array($sql2))
			  {  
				
			$emails = $row['email'];
			$name = $row['name'];
			}
				
 $insert = mysqli_query($con,"UPDATE foods SET status= 'Paid' where id='$string'") or die ('Could not connect: ' .mysqli_error($con)); 
					
$insert = mysqli_query($con,"UPDATE foods SET app= 'Confirmed' where id='$string'") or die ('Could not connect: ' .mysqli_error($con));   

$insert = mysqli_query($con,"UPDATE enter SET status= 'Paid' where orderid='$string'") or die ('Could not connect: ' .mysqli_error($con)); 

$insert = mysqli_query($con,"UPDATE foods SET meth= 'Card' where id='$string'") or die ('Could not connect: ' .mysqli_error($con));  
	
	///////////////////////////////// Mail Function started//////////////////////////////////////////////////////////////////					 
	$comments = $email_to = $email_subject = $email_from = $msg = "";	
	$email_from="admin@chbluxuryempire.com";					 
	$email_to = "$emails";
    $email_subject = "Items Purchased Successfully!";
             $comments ="
		  <center><div style='background-color:#000000; color:#FFFFFF; height:700px; width:500px;'>
	 <div style='background-color:#000000; width:500px; height:150px;'>
	 <p style='text-align:left;'>
	<img src='https://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='60px;' style='margin-top:13px;'>
	<font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>Total Cost: &#8358;$price <br>$dear</font></p>
	
	<p style='color:#FFC700; font-size:30px;'>Purchase Successful</p> 
	 
	 </div>
 <p style='color:white;'>Hello Dear Customer,Here is your PickUp No which will be provided to the store upon arrival to get your  items.Thank you.</p>
 <p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#FFFFFF;' width='500px'>
 <tr style='border-bottom:#FFFFFF solid; font-size:14px; font-weight:500;'><td>PickUp No</td><td style='color:#FFC700; font-size:14px; font-weight:500;' colspan='2' >$string</td></tr>
 <tr><td  style='color:#fff; text-align:center;'>Your Items</td><td>Quantity</td><td>Price</td></tr>
 $html 
 </table>
 
</p> 
 
 
<br><br> <p style='text-align:center;'><a href='chbluxuryempire.com' style='color:#FFC700;'>CHBLuxuryempire</a></p>
 </div></center>
	
	


";
							 
		function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }
				 
							 
    $email_message ="";					 
    $email_message .= "  ". clean_string($user)."\n";
    $email_message .= clean_string($comments)."\n";
 
   
// create email headers
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: admin@chbluxuryempire.com' . "\r\n";


if(!@mail($email_to, $email_subject, $email_message, $headers)){

echo '<center><font color="red">mail cannot be submitted now due to server problems, Please try again.</font></center>';
	}
	////////////////////////////////////////////End mail Function//////////////////////////////////////////////////////////////
	
				  }
	  
			session_destroy();

	?>
	

	
         
          </span><br><br>
     
 
       
          
		  
		  </div>
        </div>
        </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->
  <!-- Vendor JS Files -->
  <script src="main/assets/vendor/aos/aos.js"></script>
  <script src="main/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="main/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="main/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="main/assets/vendor/php-email-form/validate.js"></script>
  <script src="main/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="main/assets/vendor/bootstrap/js/bootstrap.js"></script>

  <!-- Template Main JS File -->
  <script src="main/assets/js/main.js"></script>

</body>

</html>