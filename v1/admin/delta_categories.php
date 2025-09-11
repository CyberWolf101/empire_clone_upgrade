<?php include "header.php" ?>
 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">
<p style="color:blue;">
<?php 
//Submit Category
if (isset( $_POST['submit'])){
$name= $_POST['category'];
$submit = mysqli_query($con,"insert into delta_category(name,status) values ('$name','active')") or die ('Could not connect: ' .mysqli_error($con));
echo "Category Added Successfully!";
header("refresh:1;url=delta_category.php");

}

//Update Status
 if (isset( $_POST['submitstat'])){
$status=$_POST['submitstat'];
$id=  $_POST['orderid'];
$insert = mysqli_query($con,"UPDATE delta_category SET status='$status' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
  }
?>
</p>
          <div class="section-title">
          <h2>Delta Kitchen Categories</h2>
          <p>Add Resturant Meal Categories</p>
        </div>



<div style="overflow-x:scroll;">
<table class='table table-condensed table-hover' border="0" style="color:black; font-size:14px;" data-toggle='bootgrid'>
<thead><tr>
<th data-column-id='employee_name' >Category</th>
<th data-column-id='employee_name' >Status</th>
<th data-column-id='employee_salary'>Actions</th>
</tr>
<?php
$sql = "SELECT all* from delta_category ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
$status=$row['status'];   
    
//color
if ($status=="pending"){
  $bg="bg-warning";
}
else if ($status=="closed"){
  $bg="bg-danger";
}
else if ($status=="active"){
  $bg="bg-success";
}				      
				      
				      
				      
               echo'<tr><td>'.$row['name'].'</td>
               <td><div class="badge '.$bg.'">'. $row['status'] .'</div></td>
                   <td><div class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions </button>
  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1">
    <h6 class="dropdown-header">Status</h6>
    <form method="post">
     <input type="text" name="orderid" value="' . $row['s'] . '" required hidden>  
    <button type="submit" name="submitstat" class="dropdown-item" value="active">Mark as Active</button>
    <button  type="submit" name="submitstat" class="dropdown-item" value="closed">Mark as Closed</button>
   </form>
</div></td></tr>';    
				  }
 ?>
</thead>
</table>
</div>
      
      
      
      
      
      
      
      
<a href="#" class="float" data-toggle="modal" data-target="#modalDialogScrollable">
<i class="bi bi-plus my-float"></i>
</a>
<style>
.float{
	position:fixed;
	width:60px;
	height:60px;
	bottom:40px;
	right:40px;
	background-color:red;
	color:#FFF;
	border-radius:50px;
	text-align:center;
	box-shadow: 2px 2px 3px #999;
}

.my-float{
	margin-top:22px;
	font-size:42px;
}

.my-float:hover{
	color:white;
}
</style>
                <div class="modal fade" id="modalDialogScrollable" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
                 <div class="modal-content">
				<div class="modal-header">
				<h6 style="color:black;">Register New Category</h6>
				</div>
                <div class="modal-body">
                  <form id="form" name="form" action="" method="post"> 
                      <div class="row mb-3">
                      <div class="col-md-12">
					  <input type="text" name="category" class="form-control" placeholder="Category Name" required><br>
                      </div>

					  <div class="modal-footer">
					  <input id="submit" name="submit" class="btn btn-sm btn-primary shadow-sm" type="submit" value="Register"></form>
                    </div>
                  </div>
                </div></div>
               </div><!-- End Modal Dialog Scrollable-->	
	
      </div>
    </section><!-- End About Section -->
  </main><!-- End #main -->

  <?php include"footer.php" ?>