<?php include "header.php";

  $orid=$_POST['cate'];
  $ran=$_POST['ran'];
  $orig=$_POST['orig'];


 
 
  
$sql = "SELECT all* from sub where id='$orid' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
				 		$nam=$row["name"];
						$des=$row["descrip"];
						$oride=$row["gen"];
						$imageURL='../sub/'.$row["file_name"];	}
						



 ?>
 
    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto; ">
        <div class="section-title" style="color:#FFFFFF;">
          <h4 style="text-transform:uppercase; color:#FFC700;"><?php echo $nam; ?></h4>
		  <p><img src="<?php echo $imageURL ?> " style="max-height:30%; width:100%;"/></p>
        </div>
     
           <div class="row">
           <div class="col-lg-12 col-md-12">
	       <h6 style="text-transform:inherit; color:#FFFFFF;"><?php echo $des; ?></h6>
            <div class="box" data-aos="zoom-in" data-aos-delay="100" style="background:none;">
			 <div class="btn-wrap" style="text-align:center;">
               <form action="durations.php" method="post">
              <input type="hidden" value="<?php echo $orid; ?>" name="cate" />
			   <input type="hidden" value="<?php echo $ran; ?>" name="idea" />
			   <button type="submit" name="submit" value="submit" class="submitn">CLICK TO REGISTER</button></form></div>
             
			   <div class="btn-wrap" style="text-align:center;">
			   <p><a href="academy.php" class="submitn">BACK TO SECTIONS</a></p></div>
               </div>
               </div>
               
  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->

 <?php include"footer.php";  ?>