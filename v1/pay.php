<?php  ob_start(); session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Chbluxuryempire - Booking</title>
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
<script type="text/javascript">
    window.onload = () => {
        $('#myModal').modal('show');
    }
</script>
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

$orid=$_SESSION['ider'];

include "connect_to_mysqli.php";


$sqk = "SELECT all* from foods where id='$orid'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp))
				  {
				$cname = $rowe['name'];
				$cmail = $rowe['email'];
				$cmob = $rowe['phone'];
				}
$_SESSION['email']= $emails;
				
 ?>




<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#000000; color:#FFFFFF;">
        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Hello there!<i class="bx bx-wink-smile" style="font-size:20px;"></i></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:#FFFFFF;"></button>
      </div>
      <div class="modal-body w-100 text-center" style="background-color:#000000; color:#FFFFFF;">
        <p style="font-weight:600;">Need to add more to cart?</p>
			<p style="color:black;" style="margin-bottom:30px;"><form action="foods.php" method="post" >
			   
			   <input type="text" value="<?php echo $orid; ?>" name="ord"  required hidden>
                <input type="submit" name="submit" value="Yes" class="submitn"></form></p>
               <p><button class="submitn" data-bs-dismiss="modal">No</button></p>
      </div>
    </div>
  </div>
</div></div>


  <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#FFFFFF;">
          <h2 >CART CHECKOUT - PAYMENT</h2>
          <p>Pay with card. we make things flexible!</p>
      
  <div class="container-fluid mt-5">
  <div class="d-flex" style="overflow: auto;">
  <div class="col-md-12">
 
					 
<?php 
		     
		     $user =  $_GET['cf'];
			 $daid =  $_GET['pf'];
			 $va =  $_GET['vf'];
		   	 $use = $_GET['sad'];
	  	  
		  $del = mysqli_query($con,"DELETE from enter where s='$use'") or die ('Could not connect: ' .mysqli_error($con));  
			
 $sql = "SELECT * from food where name='$user'";
 $sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
	{
										  $me = $row["nom"];
										  $med = $row["id"];
	}
					

	    $kab=$me+$va;
   $submit = mysqli_query($con,"insert into stock(id,des,nom) values ('$med','add','$va')") or die ('Could not connect: ' .mysqli_error($con));	    
   $insert = mysqli_query($con,"UPDATE food SET nom= '$kab' where id='$med'") or die ('Could not connect: ' .mysqli_error($con)); 	    
		
			
			
			
					 
				
					 ?>
					 
					 
					 				
						
						
						
                      
					  
					  
					  
					  
					  
				
					
						  
						  <table class="table table-bordered text-center" style="border-collapse: collapse;">
                          <thead style="background: #FFC700; color: white; border-style: 1px solid #FFC700;">
                                <tr>
                                <td style="border-right-style: hidden;"></td>
                                <td style="border-right-style: hidden;"></td>
                                <td style="border-right-style: hidden; text-align: left;">Item</td>
                                <td style="border-right-style: hidden;">Price</td>
                                <td style="border-right-style: hidden;">Quantity</td>
                                <td>Total</td>
                            </tr>
                        </thead>
                        <tbody>
						<?php 

$sql = "SELECT all* from enter where orderid='$orid' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		echo '
                                <tr style="white-space: nowrap;  color:#FFFFFF;">
                                <td width="80" style="vertical-align: middle; border-right-style: hidden;"><form action="" method="get"><input type="text" value="'.$row['id'].'" name="cf" hidden/>
<input type="text" value="'.$row['priced'].'" name="pf" hidden/>
<input type="text" value="'.$row['s'].'" name="sad" hidden/>
<input type="text" value="'.$row['no'].'" name="vf" hidden/>
<button class="btn" type="submit"><i class="bx bxs-x-circle" style="font-size: 2rem; color: #FFC700;"></i></button></form></i></td>
                                <td width="80"></td>
                                <td style="vertical-align: middle; border-left-style: hidden; text-align: left; color: white; font-size: 0.7rem; font-family: "Poppins", sans-serif;">
                                    
									<div><span style="font-weight: 500;">'.$row['id'].'</span></div>
                                    
                                </td>
                                <td style="vertical-align: middle; border-left-style: hidden;">&#8358;'.$row['price'].'</td>
                                <td width="50" style="vertical-align: middle; border-left-style: hidden;">'.$row['no'].'</td>
                                <td style="vertical-align: middle; border-left-style: hidden;">&#8358;'.$row['priced'].'</td>
                            </tr>';
							}
							?>
                        </tbody></table>
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
            </div>
            </div>
            <div class="d-flex justify-content-end flex-wrap my-5" style="overflow: auto;">
                <div class="container border p-0">
                    <h5 class="bg-light p-3" style="color: #FFC700;">Cart Total</h5>
                    <table class="table" style="color: white; font-weight: 600;">
                        <tbody>
                          <tr style="border-top-style: hidden;">
                            <th scope="row"></th>
                            <td>Subtotal</td>
                            <td>&#8358;<?php 
					
			$sql = "SELECT sum(priced) from enter where orderid='$orid' ";
            $sql2 = mysqli_query($con,$sql);
			while($row = mysqli_fetch_array($sql2))

$k=$row[0];

$tot=$k;
echo $tot;


 $_SESSION['tot']=$tot;
 ?>.00</td>
                  
				 <td></td>
                          </tr>
                          <tr>
                            <th scope="row"></th>
                            <td>Total</td>
                            <td>&#8358;<?php echo $tot; ?>.00</td>
                            <td></td>
                          </tr>
						  <tr>
                            <th scope="row"></th>
                            <?php
                           if($tot > 1)
                           {
                            
                                echo'<td colspan="2" class="align-middle"><form action="hi.php" method="post"><input type="hidden" value=" '.$orid.'" name="idea" />
                                <input type="hidden" value="'.$tot.'" name="tot" />
                                <input type="hidden" value="'.$cmail.'" name="emails" />
                                <input type="hidden" value="'.$cname.'" name="first" />
                                <input type="submit" value="Buy with Giftcard" class="form-control" style="font-weight: 600; name="pd" font-size: 0.8rem; color: #FFC700;">
                              </form></td>';
                           }
                           ?>
                           
                           </td>
                            
                          </tr>
						  
						  
						  
						  
						  
						  
	
						  
						  
						  
						  
						  
						  
						  
    <form  method="post" action="https://checkout.flutterwave.com/v3/hosted/pay">
	 <input type="hidden" name="public_key" value="FLWPUBK-d2aabb7a85c33740c16347fa2d0aeac7-X" />

        <input type="email" name="customer[email]" style="border:0; color:#fff; width:300px;  outline:0; background:transparent; border-bottom:2px solid #fff;"  value=" <?php echo  $cmail; ?>" placeholder="Enter Email" hidden />


        <input type="hidden" name="customer[phone_number]" style="border:0; color:#fff; width:300px;  outline:0; background:transparent; border-bottom:2px solid #fff;" value=" <?php echo  $cmob; ?>" placeholder="Enter Phone Number" />


       
        <input type="hidden" name="customer[name]" style="border:0; color:#fff; width:300px;  outline:0; background:transparent; border-bottom:2px solid #fff;" value=" <?php echo  $cname; ?> " /></div><br>
<br>

        <input type="hidden" name="tx_ref" value=" <?php echo  $orid; ?>" />
        <input type="hidden" name="amount" value=" <?php echo  $tot; ?> " />
        <input type="hidden" name="currency" value="NGN" />
        <input type="hidden" name="meta[token]" value="54" />
       <input type="hidden" name="redirect_url" value="https://chbluxuryempire.com/success.php" />
						  
                          <tr style="border-bottom-style: hidden;">
                            <th scope="row"></th>
                            <?php
                           if($tot > 1)
                           {
                               echo'<td colspan="2" class="align-middle"><button type="submit" class="form-control" style="font-weight: 600; font-size: 0.8rem; color: #FFC700;">
                               Proceed To Checkout</button></td>';
                               
                           }
                           else
                           {
                               echo '';
                           }
                           ?>
                          </tr>
                        </tbody>
                      </table></form>
                </div>
            </div>
        </div>
      
       
          
		  
		  </div>
        </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->
 
  <!-- Vendor JS Files -->
  <script src="main/main/assets/vendor/aos/aos.js"></script>
  <script src="main/main/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="main/main/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="main/main/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="main/main/assets/vendor/php-email-form/validate.js"></script>
  <script src="main/main/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="main/main/assets/vendor/bootstrap/js/bootstrap.js"></script>

  <!-- Template Main JS File -->
  <script src="main/main/assets/js/main.js"></script>

</body>

</html>