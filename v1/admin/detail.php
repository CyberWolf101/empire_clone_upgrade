<?php include"header.php" ?>
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
  font-size: 14px;
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
  $orid=$_POST['cate'];
  $ran=$_POST['ran'];
  $orig=$_POST['orig'];

 include "connect_to_mysqli.php";
 
 
  
$sql = "SELECT all* from sub where id='$orid' ";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				 		$nam=$row["name"];
						$des=$row["descrip"];
						$oride=$row["gen"];
						$imageURL='../sub/'.$row["file_name"];
	
	
						}
						
						
						
						if($ran==0)
						{
$ran=substr(md5(mt_rand()), 0, 4);
}
else
{
$ran=$ran;
}


 ?>
 
    <!-- ======= Pricing Section ======= -->
    <section id="pricing" style=" background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto; ">
        <div class="section-title" style="color:#FFFFFF;">
          <h4 style="text-transform:uppercase; color:#FFC700;"><?php echo $nam; ?></h4>
		  <p><img src="<?php echo $imageURL ?> " style="max-height:30%; width:100%;"/></p>
        </div>
     
           <div class="row">
          <div class="col-lg-12 col-md-12">
	       <h6 style="text-transform:inherit;"><?php echo $des; ?></h6>
            <div class="box" data-aos="zoom-in" data-aos-delay="100" style="background:none;">
			 <div class="btn-wrap" style="text-align:center;">
               <form action="babe.php" method="post"><input type="hidden" value="<?php echo $orid; ?>" name="cate" />
			   <input type="hidden" value="<?php echo $ran; ?>" name="idea" />
			   <button type="submit" name="submit" value="submit" class="submitn">CLICK TO BOOK</button></form></div><br>
             
			  	 <div class="btn-wrap" style="text-align:center;">
               <form action="mainsub.php" method="post"><input type="hidden" value="<?php echo $oride; ?>" name="cate" />
			   <button type="submit" name="submit" value="submit" class="submitn">BACK TO MAIN</button></form></div>
            </div>
          </div>
               
  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->
  <?php include"footer.php" ?>