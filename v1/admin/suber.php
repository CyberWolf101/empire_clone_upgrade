<?php include"header.php";  include"edit_sub.php"; ?>
 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

          <div class="section-title">
          <h2>Edit Details</h2>
          <p>Edit details</p>
           </div>
<?php

            $ode = $_POST['ordid'];
		    $sql = "SELECT * from sub where id  = '".$ode."'  ";
		    $sql2 = mysqli_query($con,$sql);
			  
			   while($row = mysqli_fetch_array($sql2))
				    
					{
					$id = $row["s"]; 
					 $drid= $row["name"];   					
					  
					  $sub_id = $row['id'];
					  $mob = $row['phone'];
					  $rac = $row['gen'];
					  $price = $row['sub_price'];
					  $lie= $row["file_name"];
					  $des= $row['descrip'];
					
					  }
					  
$delta_show="none";
if($rac=="0015"){
   $delta_show="block";  
}
?>	  

<form method="post" enctype="multipart/form-data" style="text-align:left; text-transform:uppercase;">
<div class="col-lg-12" style="color:#FF339A;"><?php echo "$norm"; ?><?php echo "$rw"; ?></div>
<p><label style="font-weight:800; text-align:left;">NAME</label>
<input type="text" name="oddd" class="form-control" value="<?php echo  $drid; ?>"  required  /></p>

<p style="display:<?php echo $delta_show; ?>;"><label style="font-weight:800; text-align:left;">PRICE</label>
<input type="number" name="price" class="form-control" value="<?php echo  $price; ?>"  /></p>


<p><label style="font-weight:800; ">DESCRIPTION</label>
<textarea type="text" name="em" class="form-control" required ><?php echo htmlspecialchars($des); ?></textarea></p>


 <p><label style="font-weight:800; ">SERVICE CATEGORY</label>
<select class="form-control" name="vem" value="<?php echo $rac ?>"required>
<option selected="selected" value="<?php echo $rac ?>">
<?php $sql = "select name from cater where id='$rac' ";
 $sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
              echo $row['name'] ;
		    	}?></option>
		    	
		    	
	     <?php
	        include "connect_to_mysqli.php";
	 
	       $sql = "select id,name from cater where id!='$rac'";
		   $sql2 = mysqli_query($con,$sql);
            while ($row = mysqli_fetch_array($sql2)) {
              echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
			}
			  ?></select></p>
<?php  
	   
	     if($rac =='0015'){
	         
	     ?>
	   
	     <p><select class="form-control" name="add">
	    <option value="">- Update Additional Meal -</option>
	      <?php
	       include "connect_to_mysqli.php";
	          
	      $sql = "select id,name from sub where gen='0017'";
		  $sql2 = mysqli_query($con,$sql);
          while ($row = mysqli_fetch_array($sql2)) {
          echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
			}			  ?>
	   </select>
	   </p>
	   
	   <?php } ?>			   
<p><input type="text" name="id" class="form-control" value="<?php echo  $sub_id; ?>"required readonly hidden ></p>	
<p><input type="file" name="file"  class="form-control" /></p>	  
<center> <input type="submit" value="UPDATE" class="submitn" name="submit"/></center>
</form><br>
      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>