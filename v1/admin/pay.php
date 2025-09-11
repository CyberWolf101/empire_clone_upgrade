<?php include"head.php" ;
$orid=$_SESSION['ider'];?>
  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">
<style>


.ter{
background-color:#fff;
padding:0 10px;
}
.check{
padding:2%;
font-size:12px;
width:25%;
}
.check span{

font-size:13px;
font-weight:700;

}
.img{
max-width:30%;
max-height:30%;
border-radius:50%;
}
.btn-buya {
  display: inline-block;
  padding:6px;
  border:none;
  color: #fff;
  font-size: 13px;
  text-transform:uppercase;
  font-family: "Poppins", sans-serif;
  font-weight: 800;
  transition: 0.3s;
  background:#FEBF01;
  
}

#clocs
{
display:none;}

#cloch{
display:none;
}


.big {
    display:block;
    width:100px;
    height:100px;
    background-color:red;
    cursor:pointer;
}

.hli {
    border:2px solid blue;   
}
</style>

  <?php
 session_start();
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
 
				
 ?>


  
  
  
  	 <?php 
  	 //Delete food item
		     include "connect_to_mysqli.php";
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
          <p>Pay with card. we make things flexible!</p>
	<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					
					<th data-column-id='employee_name'  width='200px'>Item</th>
					<th data-column-id='employee_name'  width='200px'>Price</th>
					<th data-column-id='employee_name'  width='200px'>Quantity</th>
					<th data-column-id='employee_name'  width='200px'>Total</th>
					<th data-column-id='employee_name'  width='200px'></th>
					
				
					
				</tr>
			</thead>
		  <?php
		 
     	include "connect_to_mysqli.php";
         $sql = "SELECT all* from enter where orderid='$orid' ";
		  $sql2 = mysqli_query($con,$sql);
		$i=1;
while ($row = mysqli_fetch_array($sql2)) {
$per=$row['price'];
$nome=$row['no'];
$fin=$per*$nome;
echo"

<tr bgcolor='#fff'><td width='200px' >" . $row['id'] . "</td><td width='200px'>" . $row['price'] . "</td>
<td width='200px' >" . $row['no'] . "</td><td width='200px' >" . $fin. "</td>";

echo '    <td width="200px"><form action="" method="get"><input type="text" value="'.$row['id'].'" name="cf" hidden/>
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
      
<p style="text-align:center; font-weight:700;">Total: &#8358;<?php 
					
			$sql = "SELECT sum(priced) from enter where orderid='$orid' ";
$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))

$k=$row[0];

$tot=$k;
echo $tot;
 ?>.00</p>
      
<p><center><form action='recep.php' method='post'>
			<input type='text' name='ordid' value='<?php echo $orid;?>' required hidden> 
			
			
			
			
<br>			
<p>	

<label class="btn-buya">
<input name="met" type="radio" value="Cash" required/>
CASH
</label>

<label class="btn-buya">
<input name="met" type="radio" value="POS" required/>
POS
</label>

<label class="btn-buya">
<input name="met" type="radio" value="Transfer" required />
TRANSFER
</label>




</p>			
				
		
			
<br>			
			
			
			
			
			
			
			
<input type='submit' name='submin' value='Accept Payment' class='submitn' ></form></p><br>

<p><a href="order.php"  class='submitn'>Update Cart</a></p></center>
<br>
<p><center><form action='end.php' method='post'>
			<input type='text' name='ordid' value='<?php echo $orid;?>' required hidden>  
			<input type='submit' name='submine' value='Cancel Order' class='submitn' ></form></center></p>			
      </div>
    </section><!-- End About Section -->


   
  </main><!-- End #main -->
 
 <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/typed.js/typed.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  

</body>
</html>