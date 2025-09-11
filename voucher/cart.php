<?php include "header.php";
 
 if(isset($_GET['rowitem'])){
 $item_delete =  $_GET['rowitem'];
 $del = mysqli_query($con,"DELETE from voucher_cart where s='$item_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
 header("refresh:0; url=cart.php");
 }

  if($username==""){
 header("location: user_details.php");
 }
?>



<style>
.section-title h2 {
    font-size: 32px;
    font-weight: bold;
    text-transform: capitalize;
    margin-bottom: 20px;
    padding-bottom: 20px;
    position: relative;
    letter-spacing: 0px;
}

.section-title h2::after {
background:none;
}
</style>


  <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#FFFFFF;">
          <h2>CART CHECKOUT - PAYMENT</h2>
          <p>Pay with card. we make things flexible!</p>
  <div class="container-fluid mt-5">
            <div class="d-flex" style="overflow: auto;">
                <div class="col-md-12">
						  <table class="table table-bordered text-center" style="border-collapse: collapse;">
                           <thead style="background: #FFC700; color: white; border-style: 1px solid #FFC700;">
                            <tr>
                                <td style="border-right-style: hidden;"></td>
                                <td style="border-right-style: hidden;"></td>
                                <td style="border-right-style: hidden; text-align: left;">Package</td>
                                <td style="border-right-style: hidden;">Quantity</td>
                                <td style="border-right-style: hidden; text-align: left;">Price</td>
                                <td>Total</td>
                            </tr>
                            </thead>
                            <tbody>
						 <?php 
  //All refreshment orders
$sql = "SELECT * from voucher_cart where orderid='$saloon' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{ echo '
                                <tr style="white-space: nowrap;  color:#FFFFFF;">
                                <td width="80" style="vertical-align: middle; border-right-style: hidden;"><form action="" method="get"><input type="text" value="'.$row['s'].'" name="rowitem" hidden/>
                                <button class="btn" type="submit"><i class="bx bxs-x-circle" style="font-size: 2rem; color: #FFC700;"></i></button></form></i></td>
                                <td width="80"></td>
                                <td style="vertical-align: middle; border-left-style: hidden; text-align: left; color: white; font-size: 0.7rem; font-family: "Poppins", sans-serif;">
                                <div><span style="font-weight: 500;">'.$row['itemname'].'</span></div>
                                </td>
                                <td style="vertical-align: middle; border-left-style: hidden;">'.$row['quantity'].'</td>
                                <td width="50" style="vertical-align: middle; border-left-style: hidden;">&#8358;'.$row['price'].'</td>
                                <td style="vertical-align: middle; border-left-style: hidden;">&#8358;'.$row['totalprice'].'</td>
                            </tr>';
						
							}
							?>
                        </tbody></table>
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						<table class="table table-bordered text-center" style="border-collapse: collapse;">
                        <tfoot>
                        <tr style="white-space: nowrap;">
                         <form action="" method="post">
                         <td colspan="2"><input style="font-size:12px; height:35px;" type="text" placeholder="Enter giftcard serial" id="giftcard"><input type="text" id="orderid" value='<?php echo $saloon; ?>' hidden></td>
                         <td colspan="3" class="text-left align-middle" style="border-left-style: hidden; border-right-style: hidden;">
                         <button type="submit" name="addcoupon" id="addcoupon"  style="color:#FFC700; font-size: 0.8rem; font-weight: 600;" class="btn btn-light">Apply Giftcard</button></td>
                        </form>
                        <td colspan="" class="text-right">
                        <a href="index.php" style="font-size: 0.8rem; color: rgb(209, 209, 209); font-weight: 600;" class="btn btn-secondary">Update Cart</a></td>
                        </tr>
                       </tfoot>                        
                       </table>
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
                <td>&#8358;<?php echo $total_all; ?>.00</td>
                <td></td>
                </tr>
                          
                          
                            <tr>
                            <th scope="row"></th>
                            <td>Total</td>
                            <td>&#8358;<?php echo $total_all; ?>.00</td>
                            <td></td>
                            </tr>
						  
						     <tr class="topay" style="display:none; color: #FFC700;">
                            <th scope="row"></th>
                            <td>Amount Left To Pay</td>
                            <td>&#8358;<span id="amounttopay"></span>.00</td>
                            <td></td>
                            </tr>
						  
						  
 						  
		<script>
   $(document).ready(function() {
      $('#addcoupon').click(function() {
         var giftcardValue = $('#giftcard').val();
         var orderValue = $('#orderid').val();
         $("#addcoupon").attr("disabled", "disabled");
         
         
         $.ajax({
            url: 'deductgiftcard.php',
            type: 'POST',
            data: { giftcard: giftcardValue,
            orderno: orderValue},
            success: function(response) {
               if (response === 'success') {
                  alert('Payment has been initiated and is being processed.');
                  window.location.href = 'https://chbluxuryempire.com/voucher/success?status=completed&tx_ref=<?php echo $saloon; ?>';
               }else if (response === 'half-success') {
                   alert('Giftcard applied successsfully.Please pay up the rest of your invoice with your bank card');
                  updateValues();
                  }else {
                  alert(response);
                  $("#addcoupon").removeAttr("disabled");
               }
            }
         });
      });
   });
   
   
   
   function updateValues() {
       var orderValue = $('#orderid').val();
         $.ajax({
            url: 'fetchamount.php', 
            type: 'POST',
            data: { orderno: orderValue},
            success: function(data) {
               // Update the values in your HTML
               $('.topay').show();
               $('#realamount').val(data);
               $('#amounttopay').text(data);
               
               
    var $element = $(".topay");
    if ($element.length) {
    var offsetTop = $element.offset().top;
    $("html, body").animate({
      scrollTop: offsetTop
    }, 1000);}
               
            },
            error: function() {
               alert('Failed to fetch data from the database.');
            }
         });
      }

   
   
   
   
</script>				  
									  
						  
						  
						  
 						  
						  
						  
						  
						  
						  
						  
						  
						  
						  
						  
						  
						  
	       <form  method="post" action="https://checkout.flutterwave.com/v3/hosted/pay">
	    <input type="hidden" name="public_key" value="<?php echo $apikey; ?>" />
        <input type="email" name="customer[email]" style="border:0; color:#fff; width:300px;  outline:0; background:transparent; border-bottom:2px solid #fff;"  value=" <?php echo  $c_email; ?>" placeholder="Enter Email" hidden />
        <input type="hidden" name="customer[phone_number]" style="border:0; color:#fff; width:300px;  outline:0; background:transparent; border-bottom:2px solid #fff;" value=" <?php echo  $c_phone; ?>" placeholder="Enter Phone Number" />
        <input type="hidden" name="customer[name]" style="border:0; color:#fff; width:300px;  outline:0; background:transparent; border-bottom:2px solid #fff;" value=" <?php echo  $username; ?> " /></div><br>



        <input type="hidden" name="tx_ref" value=" <?php echo  $saloon; ?>" />
        <input type="hidden" id="realamount" name="amount" value=" <?php echo  $total_all; ?> " />
        <input type="hidden" name="currency" value="NGN" />
        <input type="hidden" name="meta[token]" value="54" />
        <input type="hidden" name="redirect_url" value="https://chbluxuryempire.com/voucher/success.php" />
		<tr style="border-bottom-style: hidden;">
        <th scope="row"></th>
        <?php
        //Checkout Condition
                           
                           
                           
                               if($total_all <= 0){
                                echo'<td colspan="2" class="align-middle"><a href="items.php"  class="form-control" style="font-weight: 600; font-size: 0.8rem;  text-align:center; color: #FFC700;">
                               Add Items To Cart</a></td>';
                               }
               	             
                            else
                               {  
                               echo'<td colspan="2" class="align-middle"><button type="submit" class="form-control" style="font-weight: 600; font-size: 0.8rem; color: #FFC700;">
                               Proceed To Checkout</button></td>';               
                               }
                         
                           ?>
                          </tr>
                        </tbody>
                        </table></form>
                        </div></div></div>
      
       
          
		  
		  </div>
          </div></section><!-- End Pricing Section -->


<?php include "footer.php"; ?>