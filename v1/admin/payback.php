<?php include "header.php";
$orid=$_SESSION['idea'];
?>


<main id="main">
<!-- ======= About Section ======= -->
<section id="about" class="about">
<div class="container">
<?php
$sqk = "SELECT all* from foods where id='$orid'";
$sqlp = mysqli_query($con,$sqk);
while($rowe = mysqli_fetch_array($sqlp))
			{
				$cname = $rowe['name'];
				$cmail = $rowe['email'];
				$cmob = $rowe['phone'];
				}
 

//Delete service		   
$user =  $_GET['sad'];
$del = mysqli_query($con,"DELETE from cart where s='$user'") or die ('Could not connect: ' .mysqli_error($con)); 


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
					 
  
  
  
  
  
  
  
<div class="section-title">
<h2 >CART CHECKOUT - PAYMENT</h2>
<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				    <tr  bgcolor="#CCCCCC">
					<th data-column-id='employee_name'  width='200px'>Services</th>
					<th data-column-id='employee_name'  width='200px'>Price</th>
					<th data-column-id='employee_name'  width='200px'></th>
				</tr>
			</thead>
		  <?php
		 

$sql = "SELECT all* from cart where id='$orid' ";
$sql2 = mysqli_query($con,$sql);
$i=1;
while ($row = mysqli_fetch_array($sql2)) {
$per=$row['price'];
$nom= $row['nom'];

				   $sev= $row['service'];
				   $tev= $row['timef'];
				   $dear= $row['date'];
				   $perd= $row['price'];
				  
				 
		 
echo"<tr bgcolor='#fff'><td width='200px' >" . $row['service'] . "</td><td width='200px'>" . $row['price'] . "</td>";
echo '<td width="200px"><form action="" method="get">
<input type="text" value="'.$row['s'].'" name="sad" hidden/>
<input type="submit" name="submin" value="Delete" class="submitn" ></form></i></td>	
</tr>'; 

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
	  </table></div>
	  </p>





<br>
<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>
<thead><tr  bgcolor="#CCCCCC">
					
					<th data-column-id='employee_name'  width='200px'>Item</th>
					<th data-column-id='employee_name'  width='200px'>Price</th>
					<th data-column-id='employee_name'  width='200px'>Quantity</th>
					<th data-column-id='employee_name'  width='200px'>Total</th>
					<th data-column-id='employee_name'  width='200px'></th>
				</tr>
			</thead>
	
	
<?php

$sql = "SELECT * from enter where orderid='$orid' ";
$sql2 = mysqli_query($con,$sql);
$i=1;
while ($row = mysqli_fetch_array($sql2)) {
$per=$row['price'];
$nome=$row['no'];

$fin=$per*$nome;




echo"
<tr bgcolor='#fff'><td width='200px' >" . $row['id'] . "</td><td width='200px'>" . $row['price'] . "</td>
<td width='200px' >" . $row['no'] . "</td><td width='200px' >" . $fin. "</td>";

echo '<td width="200px"><form action="" method="get"><input type="text" value="'.$row['id'].'" name="cf" hidden/>
<input type="text" value="'.$row['priced'].'" name="pf" hidden/>
<input type="text" value="'.$row['no'].'" name="vf" hidden/>
<input type="text" value="'.$row['s'].'" name="sad" hidden/>
<input type="submit" name="submin" value="Delete" class="submitn" ></form></i></td>	
</tr>
               ';  
				
}
		 
	
	  ?> 
	  </table></div>
	  </p>
 
<br>
<div class="w-100 text-center">
<p style="color:black;" style="margin-bottom:30px;"><form action="feeed.php" method="post" >
<input type="text" value="<?php echo $orid; ?>" name="ord"  required  hidden>
<input type="submit" name="submit" value="ADD REFRESHMENTS" class="submitn"></form></p>
</div>
 
 
<br><br>    
<p style="text-align:center; font-weight:700;">Total: &#8358;<?php echo $tot; ?>.00</p>
<br>
      

<?php
if(isset($_POST['accept'])){
//Go to cart or enter details
$extract_user = mysqli_query($con,"SELECT * FROM cart WHERE name!='' && email!='' && phone!='' && id='$orid'");
$count = mysqli_num_rows($extract_user);
		
if ($count <= 0) {
$meth = $_POST['method'];
$orid = $_POST['ordid'];

   $insert = mysqli_query($con,"UPDATE cart SET app= 'Confirmed' where id='$orid'") or die ('Could not connect: ' .mysqli_error($con)); 
   $insert = mysqli_query($con,"UPDATE cart SET status='Paid' where id='$orid'") or die ('Could not connect: ' .mysqli_error($con)); 
   $insert = mysqli_query($con,"UPDATE cart SET meth= '$meth' where id='$orid'") or die ('Could not connect: ' .mysqli_error($con));  
   $insert = mysqli_query($con,"UPDATE enter SET status= 'Paid' where orderid='$orid'") or die ('Could not connect: ' .mysqli_error($con));
session_start();
$_SESSION['id']=$orid;
header("location: customer.php");
}
else{
$_SESSION['ordid']=$orid;
header("location: saloon_receipt.php");
}
}

?>

<div class="cart_div" align="center">
<form method='post'>
<input type='text' name='ordid' value='<?php echo $orid;?>' required hidden> 

<p>	
<label class="btn-buya">
<input name="method" type="radio" value="Cash" required/>
CASH
</label>

<label class="btn-buya">
<input name="method" type="radio" value="POS" required/>
POS
</label>

<label class="btn-buya">
<input name="method" type="radio" value="Transfer" required />
TRANSFER
</label>
</p><br>			
<p><input type='submit' name='accept' value='Accept Payment' class='submitn' ></form></p><br>
<p><a <a href="walkindata.php" class='submitn'>Add More Services</a></p><br>
<p><form action='endcart.php' method='post'>
<input type='hidden' name='ordid' value='<?php echo $orid;?>' required hidden>  
<input type='submit' name='submine' value='Cancel The Order' class='submitn' ></form></p>
</div>






</div>
</section><!-- End About Section -->


   
  </main><!-- End #main -->
 
<?php include "footer.php"; ?>