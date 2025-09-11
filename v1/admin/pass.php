<?php include"header.php" ; ?>

  <main id="main">

  <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Change Password</h2>
          <p>Admin,Cashier</p>
        </div>
  	
<p><?php 
		     include "connect_to_mysqli.php";
			 if (isset($_POST['pass']))
 {
		    $use =  $_POST['name'];
			$used =  $_POST['pass'];
	  	  
		  	$insert = mysqli_query($con,"UPDATE admob SET password= '$used' where s='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
					 
				echo"<p style='color:blue;'>Password Successfully Updated (".$used.")!</staff>";	 
				}
					 ?></p>	 

<p><form action="" method="post">
<select name="name" class="form-control" required>
<option selected="selected" value="">-Change Password For-</option>
     <?php
	  include "connect_to_mysqli.php";
	 
	 $sql = "select s,status from admob";
     $sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
    
              echo'<option value="'.$row['s'].'">'.$row['status'].'</option>';
			}
			  ?>
			  
</select></p>
<p><input type="password" name="pass" placeholder="Enter New Password" class="form-control" required/></p>
<p><input type="submit" value="Update" name="submit" class="submitn" style="text-align:center;"/></p>
</form>
	  
      

      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->
<?php include "footer.php" ?>