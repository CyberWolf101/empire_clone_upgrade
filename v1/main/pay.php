<?php include"header.php"; ?>
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
 session_start();
 $orid=$_SESSION['ider'];
include "connect_to_mysqli.php";


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
        <p style="font-weight:600;">Would you like to be refreshed with food and drinks during your appointment?</p>
			<p style="color:black;" style="margin-bottom:30px;"><form action="food.php" method="post" >
			   
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
                    <table class="table table-bordered text-center" style="border-collapse: collapse;">
                        <thead style="background: #FFC700; color: white; border-style: 1px solid #FFC700;">
                            <tr>
                                <td style="border-right-style: hidden;"></td>
                                <td style="border-right-style: hidden;"></td>
                                <td style="border-right-style: hidden; text-align: left;">Product</td>
                                <td style="border-right-style: hidden;">Price</td>
                                <td style="border-right-style: hidden;">Booking</td>
                                <td>Total</td>
                            </tr>
                        </thead>
						
		
		<?php 
		    //Delete service
		    $user =  $_GET['cn'];
            $del = mysqli_query($con,"DELETE from cart where s='$user'") or die ('Could not connect: ' .mysqli_error($con)); 
 ?>
					 
					 <?php 
		//Delete food item
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
 
 
 
 
 
 
 
<tbody>
 <?php 
  //Select Them all
$sql = "SELECT all* from cart where id='$orid' && timef!=''ORDER BY s ASC";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 $cname= $row['name'];
				  $cmail= $row['email'];
				   $cmob= $row['phone'];
				   $sev= $row['service'];
				   $tev= $row['timef'];
				  $nom= $row['nom'];
				  $dear= $row['date'];
				  $perd= $row['price'];
				 
				 //To check out 
                    $extrac= mysqli_query($con,"SELECT all* from cart where id='$orid' && timef!=''");
		            $cout = mysqli_num_rows($extrac);
	                 			
				  
				     //pedicure set
				  	$extract= mysqli_query($con,"SELECT all* from kit where id='$orid' && name='$sev' && date='$dear'");
		            $count = mysqli_num_rows($extract );
	                 if ($count==1) {
		             $chk = '<div><font color="green">Plus Personal Spa Kit(price : '.$kit.'naira)</font><div>';}
		             
       
       	echo '
                            <tr style="white-space: nowrap;  color:#FFFFFF;">
                                <td width="80" style="vertical-align: middle; border-right-style: hidden;"><form action="" method="get"><input type="text" value="'.$row['s'].'" name="cn" hidden/>
<button class="btn" type="submit"><i class="bx bxs-x-circle" style="font-size: 2rem; color: #FFC700;"></i></button></form></i></td>
                                <td width="80"></td>
                                <td style="vertical-align: middle; border-left-style: hidden; text-align: left; color: white; font-size: 0.7rem; font-family: "Poppins", sans-serif;">
                                    
									<div><span style="font-weight: 500;">'.$row['service'].'</span></div>
                                    <div style="color:#FFC700;">Appointement Details</div>
                                    <div>Date: '.$row['date'].'</div>
                                    <div>From: '.$row['timef'].'</div>
									<div>To: '.$row['timet'].'</div>
                                    <div>Staff: '.$row['staff'].'</div>
									<div>Client: '.$row['name'].'</div>
									'.$chk.'
                                </td>
                                <td style="vertical-align: middle; border-left-style: hidden;">&#8358;'.$row['price'].'</td>
                                <td width="50" style="vertical-align: middle; border-left-style: hidden;">1</td>
                                <td style="vertical-align: middle; border-left-style: hidden;">&#8358;'.$row['price'].'</td>
                            </tr>';
                            
							}


	//count how many pedicure kit is needed
				$checker = mysqli_query($con,"SELECT all* from kit where id='$orid'");
	        	$coun = mysqli_num_rows($checker);  
	           //get pedicure kit price
		$got = "SELECT all* from food where name='Spa'";
		$got2 = mysqli_query($con,$got);
			 while($gat = mysqli_fetch_array($got2))
				  {
				 $kit=$gat['price'];
				 }	 
			//price of all pedicure kit
			$kite=$kit*$coun;

//Bottle Water	
	     $bot = "SELECT all* from enter where orderid='$orid' && id='Comes with free coffee,Tea or Portable Water'";
		$bot2 = mysqli_query($con,$bot);
			 if (mysqli_affected_rows($con) == 0)
			  {
			   $submit = mysqli_query($con,"insert into enter(id,orderid,price,no,priced) values ('Comes with free coffee,Tea or Portable Water','$orid','0','$nom','0')") or die ('Could not connect: ' .mysqli_error($con));
} 			
							
//Total services price                           
$som = "SELECT sum(price) from cart where id='$orid' && timef !='' ";
$som2 = mysqli_query($con,$som);
while($ros = mysqli_fetch_array($som2))

$pa=$ros[0];
$p=$pa+ $kite;	

// Total item price
$sam = "SELECT sum(priced) from enter where orderid='$orid' ";
$sam2 = mysqli_query($con,$sam);
while($row = mysqli_fetch_array($sam2))
$k=$row[0];


//Whole Total
$tot=$k+$p;
							?>
                        </tbody></table>
						  <h3>REFRESHMENTS</h3>
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
  //All refreshment orders
$sql = "SELECT all* from enter where orderid='$orid' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		echo '
                            <tr style="white-space: nowrap;  color:#FFFFFF;">
                                <td width="80" style="vertical-align: middle; border-right-style: hidden;"><form action="" method="get"><input type="text" value="'.$row['id'].'" name="cf" hidden/>
<input type="text" value="'.$row['priced'].'" name="pf" hidden/>
<input type="text" value="'.$row['s'].'" name="sad" hidden/>
<input type="text" value="'.$row['no'].'" name="vf" hidden/><button class="btn" type="submit"><i class="bx bxs-x-circle" style="font-size: 2rem; color: #FFC700;"></i></button></form></i></td>
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
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						<table class="table table-bordered text-center" style="border-collapse: collapse;">
                        <tfoot>
                            <tr style="white-space: nowrap;">
                                <form action="" method="post">
                                    <td colspan="2"><input type="text" class="" name="dio"></td>
                                    <td colspan="3" class="text-left align-middle" style="border-left-style: hidden; border-right-style: hidden;"><button type="submit" name="submin"value="as"style="color:#FFC700; font-size: 0.8rem; font-weight: 600;" class="btn btn-light">Apply Coupon</button></td>
                                </form>
                                <td colspan="2" class="text-right"><a href="more.php" style="font-size: 0.8rem; color: rgb(209, 209, 209); font-weight: 600;" class="btn btn-secondary">Update Cart</a></td>
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
                            <td>&#8358;<?php echo $tot; ?>.00</td>
                  <td></td>
                          </tr>
                          
                          
                            <tr>
                            <th scope="row"></th>
                            <td>Total</td>
                            <td>&#8358;<?php echo $tot; ?>.00</td>
                            <td></td>
                          </tr>
						  
						  
<div class="modal fade" id="myMod" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#000000; color:#FFFFFF;">
        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Add More Services!al</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:#FFFFFF;"></button>
      </div>
      <div class="modal-body w-100 text-center" style="background-color:#000000; color:#FFFFFF;">
        <p style="font-weight:600; font-size:13px;">Your booking type requires that you add more services else you can't checkout<br>i.e 2 or more services for couple booking or
        3 or more services for family booking</p>
         	<p><a href="more.php" ><button class="submitn" >Okay,Choose more</button></a></p> 
          
      </div>
    </div>
  </div>
</div></div>							  
						  
						  
						  
						  
						  
						  
						  
						  
						  
						  
						  
						  
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
        <input type="hidden" name="redirect_url" value="https://chbluxuryempire.com/main/success.php" />
						  
                          <tr style="border-bottom-style: hidden;">
                            <th scope="row"></th>
                           <?php
                           //Checkout Condition
                           if($tot > 1)
                           {
                               if ($nom=="1" && $cout < 1)
                               {
                                    	echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#myMod").modal("show");
			
			});
		</script>';
		
		
	echo"<script type='text/javascript'>
    window.onload = () => {
        $('#myModal').modal('hide');
    }
</script>";   
            
                               }
                               
                             else if ($nom=="2" && $cout < 2)
                               {
                                  	echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#myMod").modal("show");
				
			});
		</script>';
		
		
	
		
		
		
		
		
		
		
		
		
		
		
		
	echo"<script type='text/javascript'>
    window.onload = () => {
        $('#myModal').modal('hide');
    }
</script>";   
            
                               }
                               
                         else if ($nom=="3" && $cout < 3)
                               {
                                  	echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#myMod").modal("show");
		 
			});
		</script>';
		
		
	echo"<script type='text/javascript'>
    window.onload = () => {
        $('#myModal').modal('hide');
    }
</script>";   
            
                               }    
                               
                            else
                               {  
                                                             echo'<td colspan="2" class="align-middle"><button type="submit" class="form-control" style="font-weight: 600; font-size: 0.8rem; color: #FFC700;">
                               Proceed To Checkout</button></td>';               
                               }
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

 
                            <?php include"footer.php"; ?>