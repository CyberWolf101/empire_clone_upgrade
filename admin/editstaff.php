<?php include "header.php";

if(isset($_GET['category'])){

            $category = $_GET['category'];
	        $sql = "SELECT * from admin where s = '$category'  ";
			$sql2 = mysqli_query($con,$sql);
		    while($row = mysqli_fetch_array($sql2))
				    
					 {
					  $id = $row["s"]; 
					  $name = $row["name"];   					
					  $email = $row['email'];
					  $status = $row['status'];
					  $categories = explode(',', $row['sections']); 
					  $password = $row['password'];
					  }}
					   else{
					      header("location: staff.php");
					  }



$categoryNames = array();
foreach ($categories as $value) {}
?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Manager</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Staff</li>
            </ol>
          </div>
          
<!-- Row -->
<div class="row">          
          
<div align="center" class="col-lg-12">
<?php include "update_staff.php"; ?>
<div class="arizona">
<form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:left;">
<input type="text" class="form-control" name="name" value="<?php echo $name; ?>" placeholder="*Name" required /><br />
<input type="text" class="form-control" name="email" value="<?php echo $email; ?>"  placeholder="*Email" required /><br />
<input type="text" class="form-control" name="password"  value="<?php echo $password; ?>" placeholder="*Password" required /><br />
<p><select class="select2-multiple form-control" name="sections[]" multiple="multiple" id="select2Multiple" style="width:100%;">
<?php // Loop through the options and generate the <option> elements
$options = ['- Select Department -', 'saloon', 'orishirishi', 'kitchen', 'repair', 'academy', 'members', 'giftcard', 'vouchers', 'staff', 'rental', 'reviews'];
foreach ($options as $option) {
    // Check if the current option value is in the $categories array
    $isSelected = in_array($option, $categories) ? 'selected="selected"' : '';

    // Generate the <option> element with the selected attribute if applicable
    echo '<option value="' . htmlspecialchars($option) . '" ' . $isSelected . '>' . htmlspecialchars($option) . '</option>';
}  ?></select>
</p>

<p><select class="form-control" name="role" required>
<option value="">- Select Role -</option>
<?php 
	 
$sql = "select DISTINCT status from admin where status!='superadmin'";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
    $selected = ($row['status'] == $status) ? 'selected="selected"' : '';
    echo '<option value="' . htmlspecialchars($row['status']) . '" ' . $selected . '>' . htmlspecialchars($row['status']) . '</option>';
}
?>
</select></p>

<input type='submit' name='register' value='Update Details' class='btn btn-primary' ></form>	
</div></div>
          
 </div>
          
          
          
<?php include "footer.php"; ?>