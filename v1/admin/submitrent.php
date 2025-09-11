<?php include"header.php" ?>

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
        <h2>Submit Rent Request</h2>
        </div>
        
        <div style="color:red; text-align:center; padding:20px;" >
		<p><?php include "submit_rent.php"; ?> </p></div>
		
		
       <form  method="post" enctype="multipart/form-data" >
       <p><input type="text" class="form-control" placeholder="Your Fullname*" name="name" required></p>
       <p><input type="email" class="form-control" placeholder="Your email* <?php echo  $err1; ?>" name="email" required></p>
        <p><input type="date"  name="date"  class="form-control" required /></p>
        <?php echo $er; ?>
	   <p><select  class="form-control" name="reason" >
	   <option value="" selected>- Select Reason -</option>    
	   <option>For beauty rental</option>   
	   <option>Skills Training rental</option> 
	   </select></p>
	   <p><input type="tel" class="form-control" placeholder="Phone number*" name="phone" required></p>
	     <p><textarea  class="form-control" placeholder="Give Reason(if your reason is not stated above)" name="reasons" ></textarea></p>
	    <p><input type="number" class="form-control" placeholder="How many hours*" name="hour" required></p>
	    <p><input type="number" class="form-control" placeholder="How many people*" name="people" required></p>
	    <p><input type="submit" value="Submit" name="submit" class="submitn" style="text-align:center;"/></p></form>
       </form>
      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>