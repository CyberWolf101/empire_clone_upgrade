<?php include"header.php" ?><?php include"strafs.php" ?>
 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Edit Staff Details</h2>
          <p>Edit staff details</p>
        </div>
<?php

$ode = $_POST['ordid'];
	
		    $sql = "SELECT * from staff where email  = '".$ode."'  ";
			
		   $sql2 = mysqli_query($con,$sql);
			  
			   while($row = mysqli_fetch_array($sql2))
				    
					{
					     $id = $row[""]; 
										  $id = $row["s"]; 
										   $drid= $row["name"];   					
					  
					  $emails = $row['email'];
					  $mob = $row['phone'];
					  $rac = $row['services'];
					  $lie= $row["file_name"];
					  $pas= $row['pass'];
					  $role= $row['status'];
					   $sect= $row['section'];
					  
					  
				
				
					  }
?>	  

<form method="post" enctype="multipart/form-data" style="text-align:left; text-transform:uppercase;">
<div class="col-lg-12" style="color:#FF339A;"><?php echo "$su"; ?><?php echo "$rw"; ?><?php echo "$nom"; ?></div>
<p><label style="font-weight:800; text-align:left;">NAME</label>
<input type="text" name="oddd" class="form-control" value="<?php echo  $drid; ?>"  required  /></p>


<p><label style="font-weight:800; ">EMAIL</label>
<input type="text" name="em" class="form-control" value="<?php echo  $emails; ?>"required readonly ></p>


<p> <label style="font-weight:800; text-align:left; ">PHONE NUMBER</label>
<input type="text" name="add" class="form-control" value="<?php echo  $mob; ?>"required   /></p>


 <p><label style="font-weight:800; ">SERVICE CATEGORY</label>
			   <select class="form-control" name="vem" value="<?php echo $rac; ?>"required>
			   <option selected="selected" value="<?php echo $rac; ?>"><?php echo $rac; ?></option>
	     <?php
	  include "connect_to_mysqli.php";
	 
	 $sql = "select id,name from cater ";
		  $sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
              echo'<option value="'.$row['name'].'">'.$row['name'].'</option>';
			}
			  ?></select></p>
			   <p><label style="font-weight:800; ">ROLE CATEGORY</label>
			   <select class="form-control" name="role" value=""required>
			   <option selected="selected" value="<?php echo $role ?>"><?php echo $role ?></option>
			   
			    <option>Manager</option>   
	   <option>Sales Person</option> 
	    </select></p>
	    <p><label style="font-weight:800; ">SECTION CATEGORY</label>
			   <select class="form-control" name="cart" value=""required>
			   <option selected="selected" value="<?php echo $sect ?>"><?php echo $sect ?></option>
			    
			    <option>Delta Kitchen</option>   
	   <option>Orishirishi</option> 
	   <option>Saloon and Spa</option> 
	   <option>Repair Centre</option> 
	   <option>Rental Centre</option> 
	    </select></p>
			   
<p>
			   <label style="font-weight:800; text-align:left; ">PASSWORD</label>
			  <input type="text" name="pasd" class="form-control" value="<?php echo  $pas; ?>"required   /></p>
		<p><input type="file" name="file" value="<img src="../staff/<?php echo $lie; ?>" class="form-control" /></p>	  

			 <center> <input  type="submit" value="UPDATE" class="submitn" name="submit" /></center>
			  </form><br>
      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>