<?php include "header.php"; 


/// Delete
if(isset($_GET['categoryid'])){
    $service_delete = $_GET['categoryid'];
    $del = mysqli_query($con,"DELETE from rental_gallery where s='$service_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
    echo "<script>alert('Picture Deleted successfully!'); window.location.href = 'gallery.php';</script>";
    exit(); // Make sure to exit the script after the alert
}


	 if (isset($_GET['num'])){
			$num =  $_GET['num'];
			$id =  $_GET['id'];
	  	 
		  			  $insert = mysqli_query($con,"UPDATE rental_gallery SET orderno='$num' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
					  echo "<script>alert('Order Updated successfully!'); window.location.href = 'gallery.php';</script>";	 
					  
			        	}
			        	
			        	
					 ?>


               <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800">Rental Gallery</h1>
              <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Rentals</li>
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
<?php include "addgallery.php"; ?>
<p><button onClick="showMeal()"class="btn btn-warning" >Add New Media</button></p>
<div class="arizona" id="formMeal" style="display:none;">
<form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:left;">
<p><input type="file" name="file" class="form-control" id="customFile" required></p>
<input type='submit' name='register' value='Upload Picture' class='btn btn-primary w-100' ></form>	
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
                        <th>Media</th>
                         <th>Order</th>
                        <th></th> <th></th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>Media</th>
                        <th>Order</th>
                        <th></th> <th></th>
                      </tr>
                    </tfoot>
                    <tbody>
                        <?php
$sql = "SELECT * from rental_gallery ORDER BY s ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {




echo "
                         <tr>
                         <td><img src='../gallery/" . $row['picture'] . "' style='width:10%; height:auto;' /></td>
                          <td><form action='' method='get'>
			             <input type='text' name='id' value='" . $row['s'] . "' required hidden>  
			             <input type='text' name='num' value='" . $row['orderno'] . "'  style='width:80px;' required></td>
                         <td><input type='submit' name='update' value='Update' class='btn btn-sm btn-primary'  ></form></td>
                        <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this media ?\");'>
		                <input type='text' name='categoryid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete Item' class='btn btn-sm btn-danger' ></form></td>	
                        </tr>";

      
				
				
				
		
}
?> 
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          
          
          
<?php include "footer.php"; ?>