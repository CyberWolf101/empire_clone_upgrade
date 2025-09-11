<?php include "header.php" ?>
<link href="style.css" rel="stylesheet" type="text/css">
  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">
        <div class="section-title">
          <h2>Add New Protein</h2>
          <p>Add protein to menu</p>
        </div>
       <div style="color:red; text-align:center;" ><?php include "delta_add.php"; ?> </div><br>
    
      <form  method="post" enctype="multipart/form-data" >
      <p><input type="text" class="form-control" placeholder="*Name" name="far" required></p>
	   <p><input type="number" class="form-control"   placeholder="*Price" name="ph" required></p>
	   <!----
       <p><select class="form-control" name="cater" required>
	    <option selected="selected" >-Sub Category-</option>
	     <?php
	     /*
	       if(isset($_GET['kayd'])){
	      $sql = "select id,name from sub where gen='0017'";
		  $sql2 = mysqli_query($con,$sql);
          while ($row = mysqli_fetch_array($sql2)) {
          echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
			}}
			
		 else{
		  $sql = "select id,name from sub where gen='0015'";
		  $sql2 = mysqli_query($con,$sql);
          while ($row = mysqli_fetch_array($sql2)) {
              echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';}
			}
			*/
			?></select></p> ----->
			<p><select class="form-control" name="cater[]"  id="multiple-checkboxes" multiple="multiple" style=" z-index:100;"required>
				      
			    <option selected="selected" disabled >-Sub Category-</option>
			       <?php 

  
$sql =  "select id,name from sub where gen='0015' ";
$result = mysqli_query($con,$sql);
 if(mysqli_num_rows($result) > 0){
      $options= mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    foreach ($options as $option) {
  ?>
    <option value="<?php echo $option['id']; ?>"><?php echo $option['name']; ?> </option>
    <?php 
    }


        ?>
				    </select></p>
			
		  <p><input type="file"  name="product"  class="form-control" required /></p>
	   <p><input type="submit" value="Register" name="submit" class="submitn" style="text-align:center;"/></p></form>
      </div>
     </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>