<?php include "header.php";

/// Delete
if(isset($_GET['categoryid'])){
    $service_delete = $_GET['categoryid'];
    $del = mysqli_query($con,"DELETE from delta_additional where s='$service_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
    echo "<script>alert('Category Deleted Successfully!'); window.location.href = 'delta_meals.php';</script>";
    exit(); // Make sure to exit the script after the alert
}

//register
if (isset($_POST['register'])){
$name=$_POST['name'];
$submit = mysqli_query($con,"insert into delta_additional(name) values ('$name')") or die ('Could not connect: ' .mysqli_error($con));
echo "<script>alert('Category added Successfully!'); window.location.href = 'delta_meals.php';</script>";
}




//Update Store
if (isset( $_POST['update_store'])){
$id= $_POST['id']; 
$name=$_POST['name'];
$insert = mysqli_query($con,"UPDATE delta_additional SET name='$name' where s='$id'") or die ('Could not connect: ' .mysqli_error($con));
echo "<script>alert('Category Updated Successfully!'); window.location.href = 'delta_meals.php';</script>";
}  

?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Additional Categories</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Delta Kitchen</li>
            </ol>
          </div>
          
           <!-- Row -->
          <div class="row">          
          
<div align="center" class="col-lg-12">
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
</script>
<p><button onClick="showAri()"class="btn btn-warning" >Add New Category</button></p>

<div class="arizona" id="formAri" style="display:none;">
<form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:center;">
<input type="text" class="form-control" name="name" placeholder="*Name" required /><br />
<input type='submit' name='register' value='Register Category' class='btn btn-primary w-100' ></form>	
</div></div>
          
          
            <!-- Datatables -->
            <div class="col-lg-12" style="margin-top:2%;">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Additional Categories</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush text-primary" id="dataTable">
                    <thead class="thead-light">
                      <tr>
                        <th>Name</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                      <th>Name</th>
                      <th></th>
                      <th></th>
                      </tr>
                    </tfoot>
                    <tbody>
                        <?php
$sql = "SELECT * from delta_additional  ORDER BY s ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {

echo "
                         <tr>
                         <td>".$row['name']."</td>
                         <td> <button type='button'  data-toggle='modal' data-target='#modal".$row['s']."' class='btn btn-sm btn-primary'>Edit</button></td>
                          <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this item (".$row['name'].")?\");'>
		                <input type='text' name='categoryid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete' class='btn btn-sm btn-danger' ></form></td>
                        </tr>";
                        
                        
                      


echo'	<div class="modal fade" id="modal'.$row['s'].'" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
                <div class="modal-content">
				<div class="modal-header">
				<h6 style="color:black;">Edit Category</h6>
				</div>
                <div class="modal-body">
                  <form id="form" name="form" action="" method="post"> 
                      <div class="row mb-3">
                      <div class="col-md-12">
                          
                          
                   <p><input type="text" name="name" class="form-control" value="'.$row['name'].'" placeholder="Name" required></p>
                  <p><input type="hidden" name="id" class="form-control" value="'.$row['s'].'" placeholder="Advert Text" required></p>
                    
                    
                    
                      </div>
					  <div class="modal-footer">
					  <input id="submit" name="update_store" class="btn btn-sm btn-primary shadow-sm w-100" type="submit" value="Update Details"></form>
                    </div>
                  </div>
                </div></div>
               </div><!-- End Modal Dialog Scrollable-->';

       $i++;
}
?> 
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          
          
          
          
<?php include "footer.php"; ?>