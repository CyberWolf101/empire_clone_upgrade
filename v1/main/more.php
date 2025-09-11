<?php include"header.php"; ?>
<!---...This is for Microlashing section - single.. --->
<style>


.ter{
background-color:#fff;
margin-bottom:10px;
outline:none;
border:none;
padding:10px;

}



.span{

font-size:13px;
font-weight:600;
color:black;

}
.img{
max-width:40%;
max-height:40%;
border-radius:50%;
background-color:#000000;
}


</style>
 <?php
 session_start();
 $orid=$_SESSION['ider'];

 ?>
    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#FFFFFF;">
          <h2 >ADD EXTRA SERVICE</h2>
          <p>Click to choose your desired services and get it done faster</p>
        </div>

        <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="box" data-aos="zoom-in" data-aos-delay="100">
			
    <?php
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from cater  where id!='0015' AND id!='0017' ORDER BY name ASC";
		$sql2 = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($sql2))
				  {
				  
				  $imageURL='../category/'.$row["file_name"];	
				  
				  echo'<form method="post" action="sub.php">
     <input type="text" value="'.$row["id"].'" class="form-control" name="cate" hidden />
    <input type="text" value="'.$row["id"].'" class="form-control" name="cate" hidden />
	<div class="row"  style="width:100%; margin:auto; padding:10px;">
    <button type="submit" value="Pedicure" name="submit" class="ter">
	<div class="row"  style="width:100%; margin:auto;">
	  <div class="col"><img src="'.$imageURL.'" class="img"/></div>
	   <div class="col"><span style="color:black;">'.$row["name"].'</span></div>
		   </div></button></div>
			
			
          
	
			</form>	';
				  }
			?>
     </div></div>
			

  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->

 <?php include"footer.php";  ?>