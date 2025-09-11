<?php include"header.php"; include"hairs.php";  ?>
<!---...This is for Microlashing section - single.. --->
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
max-width:50%;
max-height:50%;
border-radius:50%;
}


</style>
 <?php
 session_start();
 $orid=$_SESSION['ider'];
 if ($orid=="")
 {echo "";}  else
 {
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from horder where id='$orid' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$nam=$row["name"];
						$em=$row["email"];
						$mb=$row["phone"];
						}
}


 ?>
 
    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto; ">
        <div class="section-title" style="color:#FFFFFF;">
          <h2 >BOOKING TYPE - SINGLE</h2>
          <p>Choose your desired services and get it done faster</p>
        </div>

        <div class="row">
         <div class="col-lg-4 col-md-4">
		
            <div class="box" data-aos="zoom-in" data-aos-delay="100">
			</div></div>
			
          <div class="col-lg-8 col-md-8">
            <div class="box" data-aos="zoom-in" data-aos-delay="100">
              <h3>Master Hair Cut<br>
			  <p style="font-size:14px; font-weight:600;color:#FEBF01;">This service is for barbing and shaping your hair into desired styles</p></h3>
             <p><form enctype="multipart/form-data" method="post"><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th></th><th></th><th></th></thead>
	<tbody>
	<div >
  
	<tr class="ter mx-3" onclick="this.querySelector('input[type=radio]').click()" >
	<td class="check"><input type="radio" style="pointer-events:none;" value="005" name="semi" required />&nbsp;&nbsp;&nbsp;&nbsp;<img src="../services/normal.jpg" class="img"/></td>
	<td class="check"><span>Normal Haircut</span><br />
	<?php 
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from serve where id='005' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		echo $row["time"];
						}
						?>mins</td>
	<td class="check" style="font-size:16px;">&#8358;<?php 
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from serve where id='005' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		echo $row["price"];
						}
						?>.00</td></tr>
						
						
						
						
						
						
						
						
						
						
						
						
						
	
	<tr class="ter mx-3" onclick="this.querySelector('input[type=radio]').click()" >
	<td class="check"><input type="radio" style="pointer-events:none;" value="006" name="semi" required/>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../services/dreads.jpg" class="img"/></td>
	<td class="check"><span>Dreadlocks</span><br />
	<?php 
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from serve where id='006' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		echo $row["time"];
						}
						?>mins</td>
	<td class="check" style="font-size:16px;">&#8358;<?php 
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from serve where id='006' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		echo $row["price"];
						}
						?>.00</td></tr>
						
<tr class="ter mx-3" onclick="this.querySelector('input[type=radio]').click()" >
	<td class="check"><input type="radio" style="pointer-events:none;" value="007" name="semi" required/>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../services/executive.jpg" class="img"/></td>
	<td class="check"><span>Executive Barbing services</span><br />
	<?php 
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from serve where id='007' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		echo $row["time"];
						}
						?>mins</td>
	<td class="check" style="font-size:16px;">&#8358;<?php 
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from serve where id='007' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		echo $row["price"];
						}
						?>.00</td></tr>						
	
	
											
						
						
						
						
							
						
						
						
						
						
						
						
						
	
	<tr class="ter mx-3" >
	<td class="check">Date</td>
	<td class="check"><span><input type="date" name="dear" min="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>"class="form-control" class="form-control" required/></span></td>
	<td class="check"></td></tr>
	
	
	
	
	
	
	
	
	
	
	
	
	
	<tr class="ter mx-3" >
	<td class="check">Time</td>
	<?php
include "connect_to_mysqli.php"; 
$date = date('Y-m-d');
$sql = "SELECT all* from horder where date='$date'  && cart='003' ORDER BY s DESC LIMIT 1";
		$sql2 = mysqli_query($con,$sql);
			 if (mysqli_affected_rows($con) == 0)
			  {
			  
				$newtime=date('Y-m-d H:i:s');	
				$data =  date("H:i", strtotime($newtime));
				 
			  } 
			   else 
			   {
				
				 while($row = mysqli_fetch_array($sql2)){
				 
						$data =  date("H:i", strtotime($row['timet']));
						
						$newtime=date('Y-m-d H:i:s');	
						
				$dat =  date("H:i", strtotime($newtime));
				
				
				if($dat > $data)
				{
				$data =  date("H:i", strtotime($newtime));
				}
						}
						
					}
					
?>
	<td class="check"><span><input type="time" name="team" class="form-control" value="<?php echo $data; ?>" /></span></td>
	<td class="check"><i>Available Time</i></td></tr>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<tr><td></td><td style="font-weight:900;">CHOOSE STAFF</td><td></td></tr>
	<tr class="ter mx-3" onclick=\"this.querySelector('input[type=checkbox]').click()\" >
	<?php 
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from staff where id='005' ORDER By name DESC LIMIT 3";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$imageURL='../staff/'.$row["file_name"];
						$ide = $row['cart'];
						
						
					

echo'
<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['name'].'" name="staff"  />&nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"><span>'.$row['name'].'<br>'.$row['gen'].'</span></td>
	<td class="check"></td></tr>'
	;
	}
	
	?>
	

	</tbody>
	</table>
	</p>
	<div style="width:90%; margin:auto;">
	<p>PERSONAL DETAILS</p>
	<p><input type="text" class="form-control" name="name" placeholder="Your Name.." value="<?php echo $nam; ?>" required/></p>
	<p><input type="email" class="form-control" name="mail" placeholder="Your Email.." value="<?php echo $em; ?>" required/></p>
	<p><input type="number" class="form-control" name="mob" placeholder="Your Mobile Number.." value="<?php echo $mb; ?>" required />
	<input type="text" value="<?php echo $orid; ?>" class="form-control" name="idea" hidden /></p>
	</div>
              
			  <div class="btn-wrap">
               <input type="submit" name="submit" class="btn-buy" value="Add More Services"/>         
               <input type="submit" name="submit" class="btn-buy" value="Procced to Payment"/>       </div>
            </div>
          </div>
</form>
  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->

 <?php include"footer.php";  ?>