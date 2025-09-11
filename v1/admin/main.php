<?php include "header.php";
$ran=$_SESSION['godid'];
?>
<main id="main">

    <section id="about" class="about">
      <div class="container">
        <div class="section-title" style="margin:1;">
        <h2>Walk In-Booking - Saloon</h2>
        </div>
        
<script>
function check()
{document.getElementById('results').scrollIntoView();}
// In your Javascript (external .js resource or <script> tag)
$(document).ready(function() {
    $('#productcategory').select2();
});
</script>        
        
        
<div class="row">
<div class="col-lg-12 col-md-12">
<div class="box" data-aos="zoom-in" data-aos-delay="100">
    
<?php include "dete.php"; ?>   
<p><form method="post">
    
<table id="result" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; font-size:13px; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
<th>Choose a service</th><th></th></thead>
<tbody>
<tr class="ter mx-3" >
	<td class="check">Service</td>
	<td class="check" colspan="2">
<select class="form-control" name="service" id="productcategory" >
<option value="">-Select Service-</option>
<?php 
  
$sql = "SELECT baby.id, baby.gen, baby.name, baby.price, baby.time
FROM baby
JOIN sub ON baby.gen = sub.id
JOIN cater ON sub.gen = cater.id AND cater.id != '0012' AND cater.id !='0017' ORDER by name ASC";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
				 	
				 	
				 		$imageURL='../baby/'.$row["file_name"];
						$ide = $row['gen'];
						$disable="";

echo "<option value='".$row['id']."'>".$row['name']."</option>"; 

	}	?>
</select>
</td></tr>	
</p>	
	
	
	
	
	
	
	
 <p>
<tr class="ter mx-3" >
	<td class="check">Choose Date</td>
	<td class="check"><input type="date" class="form-control" name="date" min="<?php echo date("Y-m-d"); ?>"   required/></td>
    <td class="check"></td></tr>
</p>
	

<p>
<tr class="ter mx-3" >
	<td class="check">Choose Time</td>
	<td class="check"><input type="time" class="form-control" name="time"  required /></td>
    <td class="check"></td></tr>
	</p>



<p>
<tr class="ter mx-3" >
	<td class="check">Choose Staff</td>
	<td class="check">
<select class="form-control" name="staff" >
<option value="">-Select Staff-</option>
<?php 
  
$sql = "SELECT * from staff ORDER By name DESC LIMIT 15";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
   
   echo "<option value='".$row['name']."'>".$row['name']."</option>"; 

	}	?>
</select></td>
<td class="check"></td></tr>
	
	</tbody>
	</table></p>
	

	
			
			
			
			
		
<div class="btn-wrap" align="center">
<input type="hidden" value="<?php echo $ran; ?>" name="id" />
<button type="submit" name="submit" value="next" class="submitn disable">ADD TO CART</button>
</form></div>

<br>

<div class="btn-wrap" align="center">
<form method="post">
<input type="hidden" value="<?php echo $ran; ?>" name="id" />
<button type="submit" name="proceed" value="next" class="submitn disable">PROCEED TO PAYMENT</button>
</form>
</div>
 
<?php
if(isset($_POST['proceed'])){

$ran=$_POST['id'];

session_start();
$_SESSION['idea'] =$ran;
echo header("location: payment.php");
}
?> 
 
</div>
</div>  
</section><!-- End About Section -->

   
</main><!-- End #main -->
<?php include"footer.php" ?>