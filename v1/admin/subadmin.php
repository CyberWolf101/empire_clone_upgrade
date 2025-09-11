<?php include"header.php" ?>
<?php include "substuff.php"; ?> 

  <main id="main">
  

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Register Subadmin</h2>
          <p>Register New Subadmin</p>
        </div>
 <div style="color:red;" >
		<?php global $statusMsg; echo "$statuMsg";?><br><?php global $suc; echo "$suc"; ?><?php global $suc; echo "$chk"; ?><?php echo "$nom"; ?></div>
         <form  method="post" enctype="multipart/form-data" >
      <p>  <input type="text" class="form-control" placeholder="*Name" name="far" required></p>
	  <p><input type="email" class="form-control"   placeholder="*Email" name="em" required></p>
	   <p><input type="number" class="form-control"   placeholder="*Phone Number" name="ph" required></p>
	  <p>  <input type="password" class="form-control"   placeholder="*Password" name="pa" required></p>
	   <p><input type="radio" value="Male" name="gend" /><label>Male  &nbsp; &nbsp; &nbsp; &nbsp;</label><input type="radio" value="Female" name="gend" /><label>Female</label></p>
     
	   <p><input type="file" name="file"  class="form-control"  required /></p>
	   <p><input type="submit" value="Register" name="submit" class="submitn" style="text-align:center;"/></p></form>
      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>