<?php include"header.php"; ?>
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
.btn-buya {
  display: inline-block;
  padding:6px;
  border:none;
  color: #fff;
  font-size: 12px;
  text-transform:uppercase;
  font-family: "Montserrat", sans-serif;
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
</style>
 <?php
 session_start();
 $orid=$_SESSION['ider'];
 if ($orid=="")
 {echo "";}  else
 {
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from cart where id='$orid' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$nam=$row["name"];
						$em=$row["email"];
						$mb=$row["phone"];
						}
}


 ?>
 <?php 
		     include "connect_to_mysqli.php";
		    if (isset($_POST['submin']))
	 {$nam =  $_POST['food']; $ord =  $_POST['idea']; $val = $_POST['va'];

$med = $_POST["dear"];
$sqk = "SELECT all* from food where id='$nam'";
		$sqlp = mysqli_query($con,$sqk);
			 while($rowe = mysqli_fetch_array($sqlp))
				  {
				  $see = $rowe['name'];
				$per = $rowe['price'];
				 $me = $rowe["nom"];
				
				}

$pre=$per*$val;

					   $submit = mysqli_query($con,"insert into enter(id,orderid,price,no,priced,status,date) values ('$see','$ord','$per','$val','$pre','','$med')") or die ('Could not connect: ' .mysqli_error($con));
   $kab=$me-$val;
   $submit = mysqli_query($con,"insert into stock(id,des,nom) values ('$nam','minus','$val')") or die ('Could not connect: ' .mysqli_error($con));	    
   $insert = mysqli_query($con,"UPDATE food SET nom= '$kab' where id='$nam'") or die ('Could not connect: ' .mysqli_error($con)); 	
					echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#myModal").modal("show");
			});
		</script>';
					}
					 
					 
					 ?></p>
					   <p> <?php 
		     include "connect_to_mysqli.php";
		     if (isset($_POST['submitt']))
{

$nam =  $_POST['drink']; $ord =  $_POST['idea']; $val = $_POST['va'];
$med = $_POST["dear"];

$sqk = "SELECT all* from food where id='$nam'";
		$sqlp = mysqli_query($con,$sqk);
			 while($rowe = mysqli_fetch_array($sqlp))
				  {
				  $see = $rowe['name'];
				$per = $rowe['price'];
				 $me = $rowe["nom"];
				
				}

$pre=$per*$val;

					   $submit = mysqli_query($con,"insert into enter(id,orderid,price,no,priced,status,date) values ('$see','$ord','$per','$val','$pre','','$med')") or die ('Could not connect: ' .mysqli_error($con));
   $kab=$me-$val;
   $submit = mysqli_query($con,"insert into stock(id,des,nom) values ('$nam','minus','$val')") or die ('Could not connect: ' .mysqli_error($con));	    
   $insert = mysqli_query($con,"UPDATE food SET nom= '$kab' where id='$nam'") or die ('Could not connect: ' .mysqli_error($con)); 	
					echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#myModal").modal("show");
			});
		</script>';
					}
					 
					 
					 ?></p>
    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto; ">
        <div class="section-title" style="color:#FFFFFF;">
          <h2>REFRESHMENTS</h2>
          <p>Get refreshed with our food options</p>
        </div>

        <div class="row">
         <div class="col-lg-4 col-md-4">
		 <p style="color:#FEBF01;">Food,Snacks,Drinks and much more..</p>
            <div class="box" data-aos="zoom-in" data-aos-delay="100">
			</div></div>
			
          <div class="col-lg-8 col-md-8">
            <div class="box" data-aos="zoom-in" data-aos-delay="100">
              <h3>FOOD</h3>
			  
             <p><form enctype="multipart/form-data" method="post"><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th></th><th></th><th></th></thead>
	<tbody>
	<div>
  
<?php 
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from food WHERE type='food' ORDER By name DESC LIMIT 100";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$imageURL='../food/'.$row["file_name"];
						$ide = $row['cart'];
								$no = $row['nom'];
						
				$data=date("Y-m-d");			
				
				if ($no > 1)
				{
				$show = 'Quantity<input class="form-control" type="number"  max="'.$row['nom'].'" min="1" name="va" value="1" /><br>
	<input type="text" value="'.$orid.'" class="form-control" name="idea" hidden />
	<input type="date" name="dear" min="'.$data.'" value="'.$data.'" class="form-control" required hidden/>
    <button type="submit" name="submin" class="btn-buya" >Add To Cart</button>';    
				}
				else
				{
				    
			$show='<p style="color: #FFC700;">Out Of Stock.</p>';
				}

echo'<form action="" method="post">
<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['id'].'" name="food" hidden />&nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"><span>'.$row['name'].'</span><br>&#8358;'.$row['price'].'.00</td>
	<td class="check" style="font-size:16px;">'.$show.'
	</td></tr></form>';
	}
	
	?>
						
						
						
						
						
							
						
						
						
						
						
						
						
						
	
	
	

	</tbody>
	</table>
	<div class="btn-wrap">
<a href="pay.php" name="submit" class="btn-buy" >Procced to Payment </a>  </div>
 <h3>DRINKS</h3>
			
<script>
	 function toggleClocs() {
    // get the cloch
		var myClocs = document.getElementById('clocs');
    var myCloch = document.getElementById('cloch');
	


    // get the current value of the cloch's display property
    var displaySetting = myCloch.style.display;
	  var displaySettin = myClocs.style.display;

    // also get the clock button, so we can change what it says
    var clocsButton = document.getElementById('clocsButton');

    // now toggle the clock and the button text, depending on current state
    if (displaySettin == 'block') {
      // clock is visible. hide it
      myClocs.style.display = 'none';
   
    }
    else {
      // clock is hidden. show it
      myClocs.style.display = 'block';
	  myCloch.style.display = 'none';
    
    }
  }
  </script>						 
 <script>
	 function toggleCloch() {
    // get the cloch
    var myCloch = document.getElementById('cloch');
	var myClocs = document.getElementById('clocs');

    // get the current value of the cloch's display property
    var displaySettin = myCloch.style.display;
	  var displaySetting = myClocs.style.display;

    // also get the cloch button, so we can change what it says
    var clochButton = document.getElementById('clochButton');

    // now toggle the cloch and the button text, depending on current state
    if (displaySettin == 'block') {
      // cloch is visible. hide it
      myCloch.style.display = 'none';
   
    }
    else {
      // cloch is hidden. show it
      myCloch.style.display = 'block';
	  myClocs.style.display = 'none';
    
    }
  }
  </script>	
							 
	
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 
					 <div style="text-align:center;"><p><button  onclick="toggleClocs()" id="clocsButton" type="button" class="btn-buya">Alcohol</button>
					 <button  onclick="toggleCloch()" id="clochButton" type="button" class="btn-buya">Non-Alcohol</button></p>	
             <p><div id="clocs"><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th></th><th></th><th></th></thead>
	<tbody>
	
  
<?php 
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from food WHERE type='Alcohol'  ORDER By name DESC LIMIT 100";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$imageURL='../food/'.$row["file_name"];
						$ide = $row['cart'];
						
					$no = $row['nom'];
						
				$data=date("Y-m-d");			
				
				if ($no > 1)
				{
				$show = 'Quantity<input class="form-control" type="number"  max="'.$row['nom'].'" min="1" name="va" value="1" /><br>
	<input type="text" value="'.$orid.'" class="form-control" name="idea" hidden />
	<input type="date" name="dear" min="'.$data.'" value="'.$data.'" class="form-control" required hidden/>
    <button type="submit" name="submitt" class="btn-buya" >Add To Cart</button>';    
				}
				else
				{
				    
			$show='<p style="color: #FFC700;">Out Of Stock.</p>';
				}

echo'<form action="" method="post">
<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['id'].'" name="food" hidden />&nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"><span>'.$row['name'].'</span><br>&#8358;'.$row['price'].'.00</td>
	<td class="check" style="font-size:16px;">'.$show.'
	</td></tr></form>';
	}
	
	?>
						
						
						
						
						
							
						
						
						
						
						
						
						
						
	
	
	

	</tbody>
	</table>

</div></p>


             <p><div id="cloch"><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th></th><th></th><th></th></thead>
	<tbody>
	
  
<?php 
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from food WHERE type='Non-Alcohol' ORDER By name DESC LIMIT 100";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$imageURL='../food/'.$row["file_name"];
						$ide = $row['cart'];
						
				$no = $row['nom'];
						
				$data=date("Y-m-d");			
				
				if ($no > 1)
				{
				$show = 'Quantity<input class="form-control" type="number"  max="'.$row['nom'].'" min="1" name="va" value="1" /><br>
	<input type="text" value="'.$orid.'" class="form-control" name="idea" hidden />
	<input type="date" name="dear" min="'.$data.'" value="'.$data.'" class="form-control" required hidden/>
    <button type="submit" name="submitt" class="btn-buya" >Add To Cart</button>';    
				}
				else
				{
				    
			$show='<p style="color: #FFC700;">Out Of Stock.</p>';
				}

echo'<form action="" method="post">
<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click()\' >
	<td class="check"><input type="radio" style="pointer-events:none;" value="'.$row['id'].'" name="food" hidden />&nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"><span>'.$row['name'].'</span><br>&#8358;'.$row['price'].'.00</td>
	<td class="check" style="font-size:16px;">'.$show.'
	</td></tr></form>';
	}
	
	?>
						
						
						
						
						
							
						
						
						
						
						
						
						
						
	
	
	

	</tbody>
	</table>
<div class="btn-wrap">
<a href="pay.php" name="submit" class="btn-buy" >Procced to Payment </a>  </div>
</div></p></p>
              
			 
          </div>
</form>
  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#000000; color:#FFFFFF;">
        <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Added to Cart!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="color:#FFFFFF;"></button>
      </div>
      <div class="modal-body w-100 text-center" style="background-color:#000000; color:#FFFFFF;">
        <p style="font-weight:600; font-size:13px;">Your item has been successfully added to cart.<br>Press ok to continue or Proceed to payment directly</p>
			<p><button class="submitn"  data-bs-dismiss="modal">Ok</button></p>
         	<p><a href="pay.php" ><button class="submitn" >Proceed to Payment</button></a></p> 
          
      </div>
    </div>
  </div>
</div></div>		
 <?php include"footer.php";  ?>
 