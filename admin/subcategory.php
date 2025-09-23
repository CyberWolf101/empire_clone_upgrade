<?php include "header.php";

if(isset($_GET['category'])){

            $category = $_GET['category'];
	        $sql = "SELECT * from category where id = '$category'  ";
			$sql2 = mysqli_query($con,$sql);
		    while($row = mysqli_fetch_array($sql2))
				    
					 {
					  $id = $row["s"]; 
					  $categoryname = $row["name"];   					
					  $describe = $row['description'];
					  }}
					  
					  else{
					      header("location: categories.php");
					  }


/// Delete
if(isset($_GET['categoryid'])){
    $service_delete = $_GET['categoryid'];
    $del = mysqli_query($con,"DELETE from sub_category where id='$service_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
    echo "<script>alert('Subcategory Deleted successfully!'); window.location.href = window.location.href;</script>";
    exit(); // Make sure to exit the script after the alert
}

?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Sub Categories (<?php echo $categoryname; ?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Sub Categories</li>
            </ol>
          </div>
          
           <!-- Row -->
          <div class="row">
          
          <!-- Datatables -->
            <div class="col-lg-12" style="margin-top:2%;">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary"></h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush text-primary" id="dataTable">
                    <thead class="thead-light">
                      <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </tfoot>
                    <tbody>
                        <?php
$sql = "SELECT * from sub_category where main_category='$category' ORDER BY name ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {

echo "
                         <tr>
                         <td>".$row['name']."</td>
                         <td>$categoryname</td>
                        <td><form action='editsubcategory.php' method='get'>
		                <input type='text' name='category' value='" . $row['id'] . "' required hidden>  
                        <input type='submit' name='edit' value='Edit Subcategory' class='btn btn-sm btn-primary' ></form></td>	
                        <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this subcategory (".$row['name'].")?\");'>
		                <input type='text' name='categoryid' value='" . $row['id'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete Subategory' class='btn btn-sm btn-danger' ></form></td>	
                        </tr>";

        
}
?> 
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          
          
          
          
<?php include "footer.php"; ?>