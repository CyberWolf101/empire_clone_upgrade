<?php include"header.php" ?>
<?php include "fadd.php"; ?> 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Add New Item</h2>
          <p>Food and drinks</p>
        </div>
 <div style="color:red;" >
		<?php global $statusMsg; echo "$statuMsg";?><br><?php global $suc; echo "$suc"; ?><?php global $chk; echo "$chk"; ?><?php global $nom; echo "$nom"; ?></div>
         <form  method="post" enctype="multipart/form-data" >
      <p>  <input type="text" class="form-control" placeholder="*Name" name="far" required/></p>
	   <p><input type="number" class="form-control"   placeholder="*Price" name="ph" required></p>
	   <p><input type="number" class="form-control"   placeholder="*Quantity In Stock" name="qa" required></p>
       <p><select class="form-control" name="cater"required>
	   <option selected="selected" value="">Choose Item Category</option>
	     <option value="food">Food</option>
	    <option value="alcohol">Alcohol</option>
		 <option value="Non-alcohol">Non-Alcohol</option>
	   </select>
	   </p>
	   <p><input type="file" name="file"  class="form-control" required/></p>
	   <p><input type="submit" value="Register" name="submit" class="submitn" style="text-align:center;"/></p></form>
      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>