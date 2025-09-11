<?php include"header.php" ?>
<?php include "add_sub.php"; 

$show="block";
if(isset($_GET['add'])){
   $show="none";  
}

$delta_show="none";
if(isset($_GET['kayd'])){
   $delta_show="block";  
}

?> 

  <main id="main">

    <!-- ======= About Section ======= -->
      <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Register Sub Category</h2>
          <p>Choose Category carefully</p>
        </div>
         <div style="color:red; text-align:center;" >
		<p><?php global $suc; echo "$suc"; ?><?php global $chk; echo "$chk"; ?><?php global $nom; echo "$nom"; ?></p></div>
        <form  method="post" enctype="multipart/form-data" >
        <p><input type="text" class="form-control" placeholder="*Name" name="far" required></p>
        <p style="display:<?php echo $delta_show; ?>;"><input type="text" class="form-control" placeholder="*Price" name="price"></p>
	    <p style="display:<?php echo $show; ?>;"><textarea type="email" class="form-control"   placeholder="*Description" name="em" required></textarea></p>
        <p><select class="form-control" name="cater" required>
	    <option  value="">Choose Main Category</option>
	     <?php
	       include "connect_to_mysqli.php";
	          
	       if(isset($_GET['kayd'])){
	       $sql = "select id,name from cater where id='0015' ";
		   $sql2 = mysqli_query($con,$sql);
           while ($row = mysqli_fetch_array($sql2)) {
           echo'<option value="'.$row['id'].'" selected="selected">'.$row['name'].'</option>';   
	        }}
	          
	          else if(isset($_GET['add'])){
	       $sql = "select id,name from cater where id='0017' ";
		   $sql2 = mysqli_query($con,$sql);
           while ($row = mysqli_fetch_array($sql2)) {
           echo'<option value="'.$row['id'].'" selected="selected">'.$row['name'].'</option>';   
	        }}
	        
	        else if(isset($_GET['academy'])){
	       $sql = "select id,name from cater where id='0012' ";
		   $sql2 = mysqli_query($con,$sql);
           while ($row = mysqli_fetch_array($sql2)) {
           echo'<option value="'.$row['id'].'" selected="selected">'.$row['name'].'</option>';   
	        }}
	          
	          
	       else{
	       $sql = "select id,name from cater where id!='0015' AND id!='0017' ";
		   $sql2 = mysqli_query($con,$sql);
           while ($row = mysqli_fetch_array($sql2)) {
           echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
			}}
			  ?>
	   </select>
	   </p>
	     <?php  
	   
	     if(isset($_GET['kayd'])){
	         
	     ?>
	   
	     <p><select class="form-control" name="add">
	     <option value="">- Choose Additional Meal -</option>
	      <?php
	       include "connect_to_mysqli.php";
	          
	      $sql = "select id,name from sub where gen='0017'";
		  $sql2 = mysqli_query($con,$sql);
          while ($row = mysqli_fetch_array($sql2)) {
          echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
			}			 
			
			?>
	   </select>
	   </p>
	   
	   <?php } ?>
	   
	   <p style="display:<?php echo $show; ?>;"><input type="file" name="file"  class="form-control" required /></p>
	   <p><input type="submit" value="Register" name="submit" class="submitn" style="text-align:center;"/></p></form>
      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>