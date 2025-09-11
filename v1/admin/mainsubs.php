<?php include"head.php" ?>
<main id="main">
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
font-weight:500;

}
.img{
max-width:30%;
max-height:30%;
border-radius:50%;
}
.submitn{
  
  background: #FFC700;
  color: #fff;
  border-radius: 5px;
  padding: 10px;
  font-size: 10px;
  font-weight: 600;
  outline:none;
  border:none;
 
}

.submitn:hover{
  background: #000000;
  color: #fff;
  outline:none;
  border:none;
}


</style>
 <?php
  session_start();
 $ran=$_SESSION['ider'];
 $gan=$_SESSION['more'];
 $orid=$_POST['cate'];
if ($orid == "")
{
$orid=$gan;
}
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from cater where id='$orid' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$nam=$row["name"];
						}


 ?>
    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">
        <div class="section-title" style="margin:1;">
          <h2><?php echo $nam; ?></h2>
          <p>Choose Sub Category</p>
        </div>
  <p><table id="results" width="95%" border="0"  cellspacing='0' style="border-collapse:separate;  border:none; outline:none; margin:auto; border-spacing:0px 10px;">
			 <thead>
			 <th></th><th></th><th></th></thead>
	<tbody>
	<?php 
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from sub where gen='$orid' ORDER By name DESC LIMIT 100";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$imageURL='../sub/'.$row["file_name"];
						$ide = $row['gen'];
						
						
					

echo'
<tr class="ter mx-3">
	<td class="check">&nbsp;&nbsp;&nbsp;<img src="'.$imageURL .'" class="img"/></td>
	<td class="check"style="width:30%;" ><span>'.$row['name'].'</span><br></td>
	<td class="check"><form action="details.php" method="post"><input type="hidden" value="'.$row['id'].'" name="cate" />
	<input type="hidden" value="'.$ran.'" name="ran" />
	<input type="hidden" value="'.$orid.'" name="orig" /><button type="submit" name="submit" value="submit" class="submitn" style="float:right;">READ MORE</button></form></td></tr>'
	;
	}
	
	?>
	

	</tbody>
	</table>
	
              
			  <div class="btn-wrap" style="float:right;">
               <a href="main.php" class="submitn">BACK</a></div>
            </div>
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