<?php include "header.php"; 


/// Delete
if(isset($_GET['categoryid'])){
    $service_delete = $_GET['categoryid'];
    $del = mysqli_query($con,"DELETE from services where id='$service_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
    echo "<script>alert('Service Deleted successfully!'); window.location.href = 'services.php';</script>";
    exit(); // Make sure to exit the script after the alert
}


					
			 if (isset($_GET['service'])){
		    $service =  $_GET['service'];
			$price =  $_GET['price'];
			$duration =  $_GET['duration'];
			$id =  $_GET['serviceid'];
	  	 
		  			  $insert = mysqli_query($con,"UPDATE services SET price= '$price' where id='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
					  $insert = mysqli_query($con,"UPDATE services SET  duration= '$duration' where id='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
					  $insert = mysqli_query($con,"UPDATE services SET  name= '$service' where id='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
					  echo "<script>alert('Service Updated successfully!'); window.location.href = 'services.php';</script>";	 
					  
			        	}
					 ?>


             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">All Saloon and Spa Services</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Services</li>
            </ol>
          </div>
          
          
            <!-- Row -->
          <div class="row">
 
 <div class="col-lg-12">
<script type="text/javascript">
   function showMeal() {
    if (document.getElementById('formMeal').style.display == 'none') {
      // clock is visible. hide it
      document.getElementById('formMeal').style.display = 'block';
     }
     
    else {
      // clock is hidden. show it
     document.getElementById('formMeal').style.display = 'none';
     }}
</script>
<?php include "addservice.php"; ?>
<p><button onClick="showMeal()"class="btn btn-warning" >Add New Service</button></p>

<div class="arizona" id="formMeal" style="display:none;">
<form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:left;">
<input type="text" class="form-control" name="name" placeholder="*Service Name" required /><br />
<div class="form-group"><label>Select Subcategory</label>
<select class="form-control" name="category" style="width:100%;">
<?php 
$sql = "select id,name from sub_category";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
}?></select></div>
<input type="number" class="form-control" name="price" placeholder="*Cost Price" required /><br />
<input type="number" class="form-control" name="duration" placeholder="*Duration" required /><br />
<input type='submit' name='register' value='Register Service' class='btn btn-primary w-100' ></form>	
</div></div>





          <!-- Datatables -->
            <div class="col-lg-12" style="margin-top:2%;">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary"></h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush text-primary" id="dataTable" style="font-size:15px;">
                    <thead class="thead-light">
                      <tr>
                        <th>Service Name</th>
                        <th>Service</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Subcategory</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                         <th>Service Name</th>
                        <th>Service</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Subcategory</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </tfoot>
                    <tbody>
                        <?php
$sql = "SELECT * from services ORDER BY name ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
$sub= $row["sub_category"]; 

$sq = "SELECT * from sub_category where id='$sub' ORDER BY name ASC";
$sq2 = mysqli_query($con,$sq);
while ($rows = mysqli_fetch_array($sq2)) {
$categoryname= $rows["name"];  }

echo "
                         <tr>
                         <td>" . $row['name'] . "</td>
                         <td><form action='' method='get'><input type='text' name='service' value='" . $row['name'] . "' style='width:200px; font-size:12px;'  required></td>
                        <td>
			             <input type='text' name='serviceid' value='" . $row['id'] . "' required hidden>  
			             <input type='text' name='price' value='" . $row['price'] . "'  style='width:80px;' required></td>
			             <td><input type='text' name='duration' value='" . $row['duration'] . "' style='width:50px;' required></td>
			             <td>$categoryname</td>
                         <td><input type='submit' name='update' value='Update Service' class='btn btn-sm btn-primary'  ></form></td>
                        <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this service (".$row['name'].")?\");'>
		                <input type='text' name='categoryid' value='" . $row['id'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete Service' class='btn btn-sm btn-danger' ></form></td>	
                        </tr>";

      
				
				
				
		
}
?> 
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          
          
          
<?php include "footer.php"; ?>