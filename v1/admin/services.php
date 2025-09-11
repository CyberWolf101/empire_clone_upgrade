<?php include"header.php" ?>
<?php include "add_service.php"; ?> 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Add New  <?php
	      if(isset($_GET['academy'])){
	      echo "Duration"; }
	      else{ echo'Service';} ?></h2>
         </div>
         
        <div style="color:red; text-align:center;" >
		<p><?php global $statusMsg; echo "$statuMsg";?><?php global $suc; echo "$suc"; ?><?php global $chk; echo "$chk"; ?><?php global $nom; echo "$nom"; ?></p></div>
        <form  method="post" enctype="multipart/form-data" >
        <p><input type="text" class="form-control" placeholder="*<?php
	      if(isset($_GET['academy'])){
	      echo "Duration"; }
	      else{ echo'Name';} ?>" name="far" required></p>
	      
	    <p><input type="number" class="form-control"   placeholder="*Price" name="ph" required></p>
	     <?php
	      if(isset($_GET['academy'])){
	          echo"";}
	          else{
	              ?>
	          
	    <p><input type="number" class="form-control"   placeholder="*Time(in minutes) eg 40" name="th" required></p>
	    <?php } ?>
	    
        <p><select class="form-control" name="cater" required>
	   <option selected="selected" >-Sub Category -</option>
	     <?php
	      
	      if(isset($_GET['academy'])){
	       $sql = "select * from sub where gen='0012' ";
		   $sql2 = mysqli_query($con,$sql);
           while ($row = mysqli_fetch_array($sql2)) {
           echo'<option value="'.$row['id'].'" selected="selected">'.$row['name'].'</option>';   
	        }}
	    
	      else{
	      $sql = "select * from sub where gen!= '0012' AND gen!='0017' AND gen!='0016' AND gen!='0015' ";
		  $sql2 = mysqli_query($con,$sql);
          while ($row = mysqli_fetch_array($sql2)) {
          echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
			}}
			?></select></p>
	   <p><input type="submit" value="Register" name="submit" class="submitn" style="text-align:center;"/></p></form>
      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>