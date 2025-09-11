<?php include"header.php" ?>
<?php include "stuff.php"; ?> 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Register Staff</h2>
          <p>Register New Staff</p>
        </div>
 <div style="color:red;" >
		<?php global $statusMsg; echo "$statuMsg";?><br><?php global $suc; echo "$suc"; ?><?php global $suc; echo "$chk"; ?><?php echo "$nom"; ?></div>
         <form  method="post" enctype="multipart/form-data" >
      <p>  <input type="text" class="form-control" placeholder="*Name" name="far" required></p>
	  <p><input type="email" class="form-control"   placeholder="*Email" name="em" required></p>
	   <p><input type="number" class="form-control"   placeholder="*Phone Number" name="ph" required></p>
	  <p>  <input type="password" class="form-control"   placeholder="*Password" name="pa" required></p>
	   <p><input type="radio" value="Male" name="gend" /><label>Male  &nbsp; &nbsp; &nbsp; &nbsp;</label><input type="radio" value="Female" name="gend" /><label>Female</label></p>
       <p><select class="form-control" name="cater"  required>
	   <option selected="selected" value="">Choose Service Category</option>
	     <?php
	  include "connect_to_mysqli.php";
	 
	 $sql = "select id,name from cater ";
		  $sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
              echo'<option value="'.$row['name'].'">'.$row['name'].'</option>';
			}
			  ?>
	   </select>
	   <p><select  class="form-control" name="section" >
	   <option value="" selected>- Select Section-</option>    
	   <option>Delta Kitchen</option>   
	   <option>Orishirishi</option> 
	   <option>Saloon and Spa</option> 
	   <option>Repair Centre</option> 
	   <option>Rental Centre</option> 
	   </select></p>
	   </p>
	   <p><select  class="form-control" name="role" >
	   <option value="" selected>- Select Role -</option>    
	   <option>Manager</option>   
	   <option>Sales Person</option> 
	  
	   </select></p>
	   </p>
	   <p><input type="file" name="file"  class="form-control"  required /></p>
	   <p><input type="submit" value="Register" name="submit" class="submitn" style="text-align:center;"/></p></form>
      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>