<?php include "header.php";?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add New Subcategory</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Subcategory</li>
            </ol>
          </div>
          
           <!-- Row -->
          <div class="row">          
          
<div align="center" class="col-lg-12">
<?php include "addsub.php"; ?>
<div class="arizona">
<form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:center;">
<input type="text" class="form-control" name="name" placeholder="*Subategory Name" required /><br />
<textarea type="text" class="form-control" name="described" placeholder="*About Subategory" required ></textarea><br>

 <p>
<select class="form-control" name="main" required>
<option selected="selected" value="">- Select Main Category -</option>
<?php 
	 
$sql = "select id,name from category where id!='$main'";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
}
			  ?></select></p>
<p><input type="file" name="file" class="form-control" id="customFile" required></p>
<input type='submit' name='register' value='Register Subcategory' class='btn btn-primary' ></form>	
</div></div>
          
 </div>
          
          
          
<?php include "footer.php"; ?>