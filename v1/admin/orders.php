<?php include"head.php" ;
$orid=$_SESSION['ider'];
$re=$_SESSION['shan'];

include "skip.php"
?>
 

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
  font-size: 14px;
  text-transform:uppercase;
  font-family: "Poppins", sans-serif;
  font-weight: 600;
  transition: 0.3s;
  background:#FEBF01;
  
}


</style>
        <div class="section-title">
        <h2>EXISTING CUSTOMER</h2>
  
  
  
  
  
  
  <p><center><form method="post" action="">
      <input type="text"  name="q" class="form-control"/><br><input type="submit" name="submit" value="SEARCH" class="btn-buya"/></center></p></form><br>

   <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>


	 <?php  
	   if (isset($_POST['submit']))
	 {
	 $q=$_POST["q"];
	 
	 echo "
	 <thead>
		     <tr  bgcolor='#CCCCCC'>
			 <th data-column-id='employee_name'  width='200px'>Name</th>
			 <th data-column-id='employee_name'  width='200px'>Mobile</th>
			 <th data-column-id='employee_name'  width='200px'></th>
			</tr>	
					</thead>
	<tbody id='livesearch'>";
 
 	$sql = "SELECT DISTINCT name,phone from foods WHERE name LIKE '%".$q."%' ORDER By name DESC LIMIT 1000";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		
	
    $data=date("Y-m-d");			
					
     echo '<form method="post" action="other.php">
    <tr><td width="200px" ><input type="text" value="'.$row['name'].'" name="name" readonly style="border:0; background:none; outline:0;" /></td>
	<td width="200px" >'.$row['phone'].'</td>
	<input type="text" class="form-control" name="ran" value="'.$re.'" hidden  />
	<input type="date" name="dear" min="'.$data.'" value="'.$data.'"    hidden/ >
    <td><input type="submit"  name="submit" class="btn-buya " value="SELECT" /></td></tr>
    </form>
     ';
				  }}
     ?>
	</tbody>
	</table></div>
 
            
            
            
 <br>  <br> <br>          
            
     <h2>NEW CUSTOMER</h2>

     <form method="post"  action="other.php">
	<p><input type="text" class="form-control" name="name" placeholder="Your Name.." required/></p><br>
	<p><input type="text" class="form-control" name="email" placeholder="Your Email.." ></p><br>
	<p><input type="text" class="form-control" name="phone" placeholder="Your Phone Number.." required/></p><br>
	<input type="hidden" class="form-control" name="ran" value="<?php echo $re; ?>" required/>
	<input type="date" name="dear" min="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>"class="form-control" required hidden/>
	<p><center><button type="submit" value="new" name="submit" class="btn-buya"/>SUBMIT</button></center></p>
    </form>
	
      

      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->
 
 <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
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