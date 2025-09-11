<?php include "header.php";

$orid= $_SESSION['idea'];


// Submit user details
$extract_user = mysqli_query($con, "SELECT * FROM academy_cart WHERE id = '$orid' AND name != '' AND phone != '' AND email != '' LIMIT 1");
$count = mysqli_num_rows($extract_user);


  // Extract user details
  $sqlp = mysqli_query($con, "SELECT * FROM academy_cart WHERE id = '$orid' AND name != '' AND phone != '' AND email != '' LIMIT 1");
  while($rowe = mysqli_fetch_array($sqlp)) {
    $cname = $rowe['name'];
    $cmail = $rowe['email'];
    $cmob = $rowe['phone'];
  }

//Update
$insert = mysqli_query($con,"UPDATE academy_cart SET name ='$cname' where id='$ran' AND name=''") or die ('Could not connect: ' .mysqli_error($con)); 
$insert = mysqli_query($con,"UPDATE academy_cart SET phone ='$cmob' where id='$ran' AND phone=''") or die ('Could not connect: ' .mysqli_error($con)); 			
$insert = mysqli_query($con,"UPDATE academy_cart SET email ='$cmail' where id='$ran' AND email=''") or die ('Could not connect: ' .mysqli_error($con)); 				
				
				
?>
<script>
$(document).ready(function(){
$("#myModal").modal('show');
    });
</script>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="tr ue">
  <div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#000000; color:#FFFFFF;">
        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Hello there!<i class="bx bx-wink-smile" style="font-size:20px;"></i></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:#FFFFFF;"></button>
      </div>
      <div class="modal-body w-100 text-center" style="background-color:#000000; color:#FFFFFF;">
        <p style="font-weight:600;">Need to add more to cart?</p>
			<p style="color:black;" style="margin-bottom:30px;"><form action="academy.php" method="post" >
			   
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
$use = $_GET['sad'];
$del = mysqli_query($con,"DELETE from academy_cart where s='$use'") or die ('Could not connect: ' .mysqli_error($con)); 

?>

<div class="btn-wrap">
<p style="float:right;"><a class="btn-buya" href="academy.php">ADD MORE TO CART</a></p></div>					 
					 
					 				
<script>
    $(document).ready(function(){
$('#quantity').on('change', function() {
  $('#add_form').submit();
});

});
</script>						
						
						
                      
					  
					  
					  
					  
					  
				
					
						  
						  <table class="table table-bordered text-center" style="border-collapse: collapse;">
                          <thead style="background: #FFC700; color: white; border-style: 1px solid #FFC700;">
                                <tr>
                                <td style="border-right-style: hidden;"></td>
                                <td style="border-right-style: hidden;">Training</td>
                                <td style="border-right-style: hidden; text-align: left;">Duration</td>
                                <td style="border-right-style: hidden;">Price</td>
                               
                            </tr>
                        </thead>
                        <tbody>
						<?php 

$sql = "SELECT all* from academy_cart where id='$orid' ";
$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				      
				      
				 		        echo '
                                <tr style="white-space: nowrap;  color:#FFFFFF;">
                                <td width="80" style="vertical-align: middle; border-right-style: hidden;"><form action="" method="get">
                                <input type="text" value="'.$row['s'].'" name="sad" hidden/>
                                 <button class="btn" type="submit"><i class="bx bxs-x-circle" style="font-size: 2rem; color: #FFC700;"></i></button></form></i></td>
                                
                                <td style="vertical-align: middle; border-left-style: hidden; text-align: left; color: white; font-size: 0.7rem; font-family: "Poppins", sans-serif;">
                                    
								<div><span style="font-weight: 500;">'.$row['training'].'</span></div>
                                    
                                </td>
                                <td style="vertical-align: middle; border-left-style: hidden;">&#8358;'.$row['duration'].'</td>
                                <td style="vertical-align: middle; border-left-style: hidden;">&#8358;'.$row['price'].'</td>
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
					
			$sql = "SELECT sum(price) from academy_cart where id='$orid' ";
            $sql2 = mysqli_query($con,$sql);
			while($row = mysqli_fetch_array($sql2))
            $k=$row[0]; $tot=$k; echo $tot;
            ?>.00</td>
                  
				 <td></td>
                          </tr>
                          <tr>
                            <th scope="row"></th>
                            <td>Total</td>
                            <td>&#8358;<?php echo $tot; ?>.00</td>
                            <td></td>
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
        <input type="hidden" name="redirect_url" value="https://chbluxuryempire.com/delta/success.php" />
		<tr style="border-bottom-style: hidden;">
                            <th scope="row"></th>
                            <?php
                           if($tot > 1 && $count > 0)
                           {
                               echo'<td colspan="2" class="align-middle"><button type="submit" class="form-control" style="font-weight: 600; font-size: 0.8rem; color: #FFC700;">
                               Proceed To Payment</button></td>';
                           }
                           else
                           {
                               echo '<td colspan="2" class="align-middle"><a href="userdetails.php"><button type="button" class="form-control" style="font-weight: 600; font-size: 0.8rem; color: #FFC700;">
                               Proceed To Checkout</button></a></td>';
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


























<?php include "footer.php"; ?>