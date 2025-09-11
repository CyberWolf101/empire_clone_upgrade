<?php include"header.php" ?>
 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Edit Item Details</h2>
          <p>Edit Item details</p>
        </div>
<?php

$ode = $_POST['ordid'];
	
		    $sql = "SELECT * from food where id  = '".$ode."'  ";
			
		   $sql2 = mysqli_query($con,$sql);
			  
			   while($row = mysqli_fetch_array($sql2))
				    
					{
										  $id = $row["s"]; 
										   $drid= $row["name"];   					
					  
					  
					  $rac = $row['id'];
					  $lie= $row["type"];
					  $pas= $row['price'];
					  $na= $row['nom'];
					  
					  
					  
				
				
					  }
?>	  

<form method="post" enctype="multipart/form-data" style="text-align:left; text-transform:uppercase;">
<div class="col-lg-12" style="color:#FF339A;"><?php include"foodds.php" ?></div>
<p><label style="font-weight:800; text-align:left;">NAME</label>
<input type="text" name="oddd" class="form-control" value="<?php echo  $drid; ?>"  required  /></p>



<input type="hidden" name="rack" class="form-control" value="<?php echo  $rac; ?>"required readonly ></p>


<p> <label style="font-weight:800; text-align:left; ">PRICE</label>
<input type="text" name="add" class="form-control" value="<?php echo  $pas; ?>"required   /></p>

<p> <label style="font-weight:800; text-align:left; ">QUANTITY</label>
<input type="text" name="ad" class="form-control" value="<?php echo  $na; ?>"required   /></p>


 <p><label style="font-weight:800; ">ITEM CATEGORY</label>
			   <select class="form-control" name="vem"  required>
			   <option selected="selected" value="<?php echo $lie ?>"><?php echo $lie ?></option>
	   <option value="food">Food</option>
	    <option value="alcohol">Alcohol</option>
		 <option value="Non-alcohol">Non-Alcohol</option>
	   </select></p>
			   
			 
				<p><input type="file" name="file"  class="form-control" /></p>  
			 <center> <input  type="submit" value="UPDATE" class="submitn" name="submit" /></center>
			  </form><br>
      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>