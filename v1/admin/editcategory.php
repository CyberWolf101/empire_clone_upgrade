<?php include"header.php";
 include"edit_category.php" ?>
 

  <main id="main">

    <!-- ======= About Section ======= -->
        <section id="about" class="about">
         <div class="container">

        <div class="section-title">
          <h2>Edit Category</h2>
          <p>Edit Category Details</p>
        </div>
     <?php

            $ode = $_POST['ordid'];
	        $sql = "SELECT * from cater where id  = '".$ode."'  ";
			$sql2 = mysqli_query($con,$sql);
		    while($row = mysqli_fetch_array($sql2))
				    
					 {
					  $id = $row["s"]; 
					  $drid= $row["name"];   					
					  $rac = $row['id'];
					  $pas= $row['des'];
					  }
					  
					  $show="none";
					  if($id!="27"){
					      $show="block";
					  }
?>	  

<form method="post" enctype="multipart/form-data" style="text-align:left; text-transform:uppercase;">
<div class="col-lg-12" style="color:#FF339A;"><?php echo "$norm"; ?><?php echo "$rw"; ?></div>
<p><label style="font-weight:800; text-align:left;">NAME</label>
<input type="text" name="oddd" class="form-control" value="<?php echo  $drid; ?>"  required  /></p>



<input type="hidden" name="rack" class="form-control" value="<?php echo  $rac; ?>"required readonly ></p>


<p style="display:<?php echo $show; ?>;"><label style="font-weight:800; text-align:left; ">DESCRIPTION</label>
<textarea type="text" class="form-control" name="des" placeholder="*About Category" required ><?php echo  $pas; ?></textarea></p>


<p><input type="file" name="file"  class="form-control" /></p>	  
<center> <input  type="submit" value="UPDATE" class="submitn" name="submit" /></center>
</form><br>
      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>