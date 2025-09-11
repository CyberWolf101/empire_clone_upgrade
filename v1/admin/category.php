<?php include"header.php" ?>
<?php include "add.php" ?> 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>All Categories</h2>
          <p>View and Delete sections</p>
          </div>
	   	 <p align="center">
	      <?php 
if(isset($_SESSION['suc'])){
$sucmessage=$_SESSION['suc'];
global $sucmessage;
echo $sucmessage;
unset ($_SESSION["suc"]);
} ?>
	<script type="text/javascript">
    function showAri() {
    if (document.getElementById('formAri').style.display == 'none') {
      // clock is visible. hide it
      document.getElementById('formAri').style.display = 'block';
     }
     
    else {
      // clock is hidden. show it
     document.getElementById('formAri').style.display = 'none';
     }}
</script><button onClick="showAri()"class="submitn" >Add New Category</button>	
<div class="arizona" id="formAri" style="display:none;">
<form enctype="multipart/form-data" method="post" style="width:60%; margin:auto; text-align:center;">
<input type="text" class="form-control" name="add" placeholder="*Category Name" required /><br />
<textarea type="text" class="form-control" name="des" placeholder="*About Category" required ></textarea><br />
<input type="file" name="file"  class="form-control" required/><br />
<input type='submit' name='submin' value='Register' class='submitn' ></form>	
		</div>
		</p>
		<?php 
		             include "connect_to_mysqli.php";
			         if (isset($_GET['submin'])){
		             $use =  $_GET['ordid'];
			        $del = mysqli_query($con,"DELETE from cater where id='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
			     	echo"<p style='color:blue;'>Category Successfully Deleted from Database!</staff>";	 
				}
					 ?>

<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>
<thead><tr  bgcolor="#CCCCCC">
					<th data-column-id='employee_name'  width='200px'>Name</th>
					<th data-column-id='employee_name'  width='200px'></th>
					<th data-column-id='employee_name'  width='200px'></th>
					<th data-column-id='employee_name'  width='200px'></th>
				</tr>
			</thead>
<?php
$sql = "SELECT * from cater where id!='0015' AND  id!='0017' AND  id!='0012' ORDER BY name ASC";
$sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {

echo "


         <tr bgcolor='#fff'><td width='200px' >" . $row['name'] . "</td>
        <td><form action='editcategory.php' method='post'>
		<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
        <input type='submit' name='submin' value='Edit' class='submitn' ></form></td>	
                
                
                <td><form action='viewsub.php' method='post'>
			    <input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
                <input type='submit' name='submin' value='Sub Categories' class='submitn' ></form></td>	

    <td><a href='#delete" . $row['id'] . "' data-toggle='modal'  ><button class='submitn'>Delete</button></a>
	<div class='modal fade' id='delete" . $row['id'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Delete Category?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to delete this category(" . $row['name'] . ")</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['id'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin'>Yes</button></form>	
        </p>
        <p><button class='submitn' data-dismiss='modal' >No</button></p>
		 
          </div>
      </div>
    </div>
  </div>
 </div> 
</div>

             
</div>
</td>
</tr>";
				
}




	 
					
	
	  
	 
		 
	
	  ?> 
	  </table></div> </p>
      </div>
     </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>