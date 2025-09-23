<?php include "header.php"; 


/// Delete
if(isset($_GET['categoryid'])){
    $service_delete = $_GET['categoryid'];
    $del = mysqli_query($con,"DELETE from rental_items where s='$service_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
    echo "<script>alert('Item Deleted successfully!'); window.location.href = 'rentalitems.php';</script>";
    exit(); // Make sure to exit the script after the alert
}


					
			 if (isset($_POST['name'])){
		    $service =  $_POST['name'];
			$price =  $_POST['price'];
			$id =  $_POST['id'];
			$selectedSections = $_POST['category'];
$commaSeparatedSections = implode(',', $selectedSections);
	  	 
		  			  $insert = mysqli_query($con,"UPDATE rental_items SET price= '$price' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
					  $insert = mysqli_query($con,"UPDATE rental_items SET  name= '$service' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
					  $insert = mysqli_query($con,"UPDATE rental_items SET category='$commaSeparatedSections' where s='$id'") or die ('Could not connect: ' .mysqli_error($con));
					  echo "<script>alert('Item Updated successfully!'); window.location.href = 'rentalitems.php';</script>";	 
					  
			        	}
					 ?>


               <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800">Rental Packages</h1>
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
<?php include "additem.php"; ?>
<p><button onClick="showMeal()"class="btn btn-warning" >Add New Item</button></p>

<div class="arizona" id="formMeal" style="display:none;">
<form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:left;">
<input type="text" class="form-control" name="name" placeholder="*Item Name" required /><br />
<div class="form-group"><label>Select Subcategory</label>
<select class="select2-multiple form-control" name="category[]" multiple="multiple" id="select2Multiple" style="width:100%;">
<?php 
$sql = "select id,name from rental_subcategories";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
}?></select></div>
<input type="number" class="form-control" name="price" placeholder="*Cost Price(per day)" required /><br />
<input type='submit' name='register' value='Register Item' class='btn btn-primary w-100' ></form>	
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
                        <th>Item</th>
                        <th>Price(per day)</th>
                        <th>Category</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>Item</th>
                        <th>Price (per day)</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </tfoot>
                    <tbody>
                        <?php
$sql = "SELECT * from rental_items ORDER BY s ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {


$categories = explode(',', $row['category']); 

$categoryNames = array();

foreach ($categories as $value) {
    $sq = "SELECT * FROM rental_subcategories WHERE id = '" . $value . "'";
    $sq2 = mysqli_query($con, $sq);
    
    while ($rom = mysqli_fetch_array($sq2)) {
        $categoryname = $rom['name'];
        // Add each category name to the array
        $categoryNames[] = $categoryname;
    }}


echo "
                         <tr>
                         <td>" . $row['name'] . "</td>
                         <td>" . $row['price'] . "</td>
                         <td>";
                         
                         
                          echo implode(', ', $categoryNames) ;
                         
                         echo"</td>
                         <td> <button type='button'  data-toggle='modal' data-target='#modal".$row['s']."' class='btn btn-sm btn-primary'>Edit</button></td>
                         <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this item (".$row['name'].")?\");'>
		                <input type='text' name='categoryid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete Item' class='btn btn-sm btn-danger' ></form></td>	
                        </tr>";

      
      
      echo'	<div class="modal fade" id="modal'.$row['s'].'" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
                <div class="modal-content">
				<div class="modal-header">
				<h6 style="color:black;">Edit Rental Item</h6>
				</div>
                <div class="modal-body">
                  <form id="form" name="form" action="" enctype="multipart/form-data" method="post"> 
                      <div class="row mb-3">
                      <div class="col-md-12">
                          
                          
                   <p><input type="text" name="name" class="form-control" value="'.$row['name'].'" placeholder="Name" required></p>
                   <p><input type="text" name="price" class="form-control" value="'.$row['price'].'" placeholder="Cost Price" required></p>
                    <p><select class="select2-multiple form-control" name="category[]" multiple="multiple" style="width:100%;">
                   <option value="">- Select Category -</option>';


$sqb = "SELECT id, name FROM rental_subcategories";
$sqb2 = mysqli_query($con, $sqb);
while ($rows = mysqli_fetch_array($sqb2)) {
$selected = (in_array($rows['id'], $categories)) ? 'selected' : ''; // Check if the current value is in the selectedValues array
echo '<option value="' . $rows['id'] . '" ' . $selected . '>' . $rows['name'] . '</option>';
}echo '</select>';                   
                   
                   
                   echo'</p>
                   <p><input type="hidden" name="id" class="form-control" value="'.$row['s'].'" placeholder="Advert Text" required></p>
                    
                    
                    
                      </div>
					  <div class="modal-footer">
					  <input id="submit" name="update_store" class="btn btn-sm btn-primary shadow-sm w-100" type="submit" value="Update Details"></form>
                    </div>
                  </div>
                </div></div>
               </div><!-- End Modal Dialog Scrollable-->';
				
				
				
		
}
?> 
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          
          
          
<?php include "footer.php"; ?>