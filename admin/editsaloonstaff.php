<?php include "header.php";

if(isset($_GET['category'])){

            $category = $_GET['category'];
	        $sql = "SELECT * from staff where id = '$category'  ";
			$sql2 = mysqli_query($con,$sql);
		    while($row = mysqli_fetch_array($sql2))
				    
					 {
					  $id = $row["s"]; 
					  $name = $row["name"];   					
					  $email = $row['email'];
					  $phone = $row['phone'];
					  $gender = $row['gender'];
					  $password = $row['password'];
					  $section = $row['section'];
					  }}
					   else{
					      header("location: staff.php");
					  }
					  
					  
					  
					  
					  ?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Update Staff Details</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Staff</li>
            </ol>
          </div>
          
<!-- Row -->
<div class="row">          
          
<div align="center" class="col-lg-12">
<?php include "update_saloonstaff.php"; ?>
<div class="arizona">
<form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:left;">
<input type="text" class="form-control" name="name" value="<?php echo $name; ?>" placeholder="*Name" required /><br />
<input type="text" class="form-control" name="email" value="<?php echo $email; ?>"  placeholder="*Email" required /><br />
<input type="text" class="form-control" name="phone"  value="<?php echo $phone; ?>" placeholder="*Phone Number" required /><br />
<input type="text" class="form-control" name="password"  value="<?php echo $password; ?>" placeholder="*Password" required /><br />

<p><input type="radio" value="male" name="type" <?php if($gender=="male"){ echo "checked"; } ?>/> <label>Male</label> <input type="radio" value="female"  <?php if($gender=="female"){ echo "checked"; } ?> name="type"/> <label>Female</label></p>
<p>
    <select class="form-control" name="service" required>
        <option value="">- Select Service Category -</option>
        <?php 
        $sql = "SELECT id, name FROM category WHERE id != '$main'";
        $sql2 = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_array($sql2)) {
            $selected = ($row['id'] == $section) ? 'selected="selected"' : '';
            echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>';
        }
        ?>
    </select>
</p>
<p><input type="file" name="file" class="form-control" id="customFile" ></p>
<input    type='submit' name='register' value='Update Details' class='btn btn-primary' ></form>	
</div></div>
          
 </div>
          
          
          
<?php include "footer.php"; ?>