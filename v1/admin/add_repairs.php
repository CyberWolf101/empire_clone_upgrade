<?php include"header.php" ?>

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
        <h2>Submit Repair Request</h2>
        </div>
        
        <div style="color:red; text-align:center;" >
		<p><?php include "addrepair.php"; ?> </p></div>
		
		
       <form  method="post" enctype="multipart/form-data" >
       <p><input type="text" class="form-control" placeholder="*Equipment Name" name="item" required></p>
       <p><select class="form-control" name="purchase">
       <option value="" selected> - Was this item purchased from us? -</option>    
       <option value="yes" > Yes </option> 
       <option value="no"> No </option> 
        </select></p>
       <p><input type="text" class="form-control" placeholder="*How long has it been used before it became faulty?" name="duration" required></p>
	   <p><textarea class="form-control" placeholder="*Full Description (Extent of damage)" name="describe" required></textarea></p>
	   <p><input type="file"  name="product"  class="form-control" required /></p>
	   
	   <p><b>CUSTOMER DETAILS</b></p>
	   <p><input type="text" class="form-control" placeholder="*Customer Name" name="name" required></p>
	   <p><input type="text" class="form-control" placeholder="*Customer Email" name="email" required></p>
       <p><input type="tel" class="form-control" placeholder="*Customer Phone Number" name="phone" required></p>
	   <p><input type="submit" value="Submit" name="repair" class="submitn" style="text-align:center;"/></p></form>
      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>