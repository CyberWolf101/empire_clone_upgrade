<?php include "header.php";

/// Delete
if(isset($_GET['categoryid'])){
    $service_delete = $_GET['categoryid'];
    $del = mysqli_query($con,"DELETE from delta_soups where s='$service_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
    echo "<script>alert('Item Deleted Successfully!'); window.location.href = 'delta_subcategory.php';</script>";
    exit(); // Make sure to exit the script after the alert
}

//Update Store
if (isset( $_POST['update_store'])){
$id= $_POST['id']; 
$name=$_POST['name'];
$price=$_POST['price'];
$meal=$_POST['category'];
$des= mysqli_real_escape_string($con, $_POST['details']);
$fileName = basename($_FILES["file"]["name"]);

 
 // File upload path
$targetDir = "../kitchen/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["update_store"]) && !empty($_FILES["file"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $insert = mysqli_query($con,"INSERT into images (file_name,input,uploaded_on) VALUES ('".$fileName."','staff', NOW())");
             $insert = mysqli_query($con,"UPDATE delta_soups SET picture='".$fileName."' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
            if($insert){
                $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
            }else{
                $statusMsg = "File upload failed, please try again.";
            } 
        }else{
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    }else{
        $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
    }
}else{
    $statusMsg = 'Please select a file to upload.';
}


  
$insert = mysqli_query($con,"UPDATE delta_soups SET name='$name' where s='$id'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE delta_soups SET price='$price' where s='$id'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE delta_soups SET description='$des' where s='$id'") or die ('Could not connect: ' .mysqli_error($con));
$insert = mysqli_query($con,"UPDATE delta_soups SET additional='$meal' where s='$id'") or die ('Could not connect: ' .mysqli_error($con));
echo "<script>alert('Item Updated Successfully!'); window.location.href = 'delta_subcategory.php';</script>";
}  

?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Main Menu</h1>
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
<?php include "addsoup.php"; ?>
<p><button onClick="showAri()"class="btn btn-warning w-100" >Add New Delta Soup</button></p>	
<div class="arizona" id="formAri" style="display:none;">
<form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:center;">
<input type="text" class="form-control" name="name" placeholder="*Name" required /><br />
<input type="number" class="form-control" name="price" placeholder="*Cost Price" required /><br />
<textarea name="details" class="form-control" placeholder="Enter description here"></textarea><br>
<input type="file" class="form-control" name="file" required /><br />
<p><select class="form-control" name="category" required>
<option value="" selected>- Select Additional Meal -</option>
<?php 
$sql = "select s,name from delta_additional";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
echo'<option value="'.$row['s'].'">'.$row['name'].'</option>';
}?></select></p>
<p><label><input type="checkbox" name="preorder" value="1"/> Click here to make available only on preorder</label></p>
<input type='submit' name='register' value='Register Details' class='btn btn-primary w-100' ></form>	
</div></div>
          
          
            <!-- Datatables -->
            <div class="col-lg-12" style="margin-top:2%;">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Main Menu</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush text-primary" id="dataTable">
                    <thead class="thead-light">
                      <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Additional Meal</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                      <th>Name</th>
                        <th>Price</th>
                        <th>Additional Meal</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </tfoot>
                    <tbody>
                        <?php
$sql = "SELECT * from delta_soups  ORDER BY s ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
$category_meal=$row['additional'];
$pre=$row['preorder'];

$pretext="";
if($pre > 0 ){$pretext="<span class='badge badge-primary'>preorder</span>"; }
$sq = "SELECT * from delta_additional where s='".$category_meal."'  ";
$sq2 = mysqli_query($con,$sq);
while($rom = mysqli_fetch_array($sq2)){
$categoryname = $rom['name']; }

echo "
                         <tr>
                         <td>".$row['name']."<br> $pretext</td>
                         <td>".$row['price']."</td>	
                          <td>".$categoryname."</td>
                         <td> <button type='button'  data-toggle='modal' data-target='#modal".$row['s']."' class='btn btn-sm btn-primary'>Edit</button></td>
                          <td><form action=''  method='get' onsubmit='return confirm(\"Are you sure you want to delete this item (".$row['name'].")?\");'>
		                <input type='text' name='categoryid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete' class='btn btn-sm btn-danger' ></form></td>
                        </tr>";
                        
                        
                      


echo'	<div class="modal fade" id="modal'.$row['s'].'" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
                <div class="modal-content">
				<div class="modal-header">
				<h6 style="color:black;">Edit Item</h6>
				</div>
                <div class="modal-body">
                  <form id="form" name="form" action="" method="post" enctype="multipart/form-data"> 
                      <div class="row mb-3">
                      <div class="col-md-12">
                          
                          
                   <p><input type="text" name="name" class="form-control" value="'.$row['name'].'" placeholder="Name" required></p>
                   <p><input type="number" name="price" class="form-control" value="'.$row['price'].'" placeholder="Cost Price" required></p>
                   <p><textarea name="details" class="form-control" placeholder="Enter description here">'.$row['description'].'</textarea></p>
                  <p><select class="form-control" name="category" required>
                   <option value="" selected>- Select Additional Meal -</option>';
                   
$sqb = "select s,name from delta_additional";
$sqb2 = mysqli_query($con,$sqb);
while ($rows = mysqli_fetch_array($sqb2)) {
$selected = ($category_meal == $rows['s']) ? 'selected' : ''; // Check if $location matches current s value
echo '<option value="' . $rows['s'] . '" ' . $selected . '>' . $rows['name'] . '</option>';}
echo '</select>';                   
                   
                   
                   echo'</p>
                   <p><label>Add New File</label><input type="file" class="form-control" name="file" /></p>
                   <p><input type="hidden" name="id" class="form-control" value="'.$row['s'].'" placeholder="Advert Text" required></p> </div>
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