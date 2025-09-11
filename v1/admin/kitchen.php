<?php include "header.php" ?>
 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">
<p style="color:blue;">
<?php 
include "addkitchen.php";

//Update Status
 if (isset( $_POST['submitstat'])){
$status=$_POST['submitstat'];
$id=  $_POST['orderid'];
$insert = mysqli_query($con,"UPDATE delta_kitchen SET status='$status' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
  }
?>
</p>
          <div class="section-title">
          <h2>Delta Kitchen</h2>
          <p>View and Add Meals</p>
        </div>



<div style="overflow-x:scroll;">
<table class='table table-condensed table-hover' border="0" style="color:black; font-size:14px;" data-toggle='bootgrid'>
<thead><tr>
<th data-column-id='employee_name'>Name</th>
<th data-column-id='employee_salary'>Price</th>
<th data-column-id='employee_salary'>Discount Price</th>
<th data-column-id='employee_name' >Category</th>
<th data-column-id='employee_name' >Status</th>
<th data-column-id='employee_salary'>Actions</th>
<th data-column-id='employee_name' >Category</th>
</tr>
<?php
$sql = "SELECT all* from delta_kitchen ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
$status=$row['status'];
$category=$row['category'];
    
//color
if ($status=="pending"){
  $bg="bg-warning";
}
else if ($status=="unavailable"){
  $bg="bg-danger";
}
else if ($status=="available"){
  $bg="bg-success";
}				      
				      
$sqls = "SELECT all* from delta_category where s='$category' ";
$sql2s = mysqli_query($con,$sqls);
while($rows = mysqli_fetch_array($sql2s))
{				      
$cat=$rows['name'];}


               echo'<tr><td>'.$row['name'].'</td>
               <td>'.$row['price'].'</td>
               <td>'.$row['discount'].'</td>
               <td>'.$cat.'</td>
               <td><div class="badge '.$bg.'">'. $row['status'] .'</div></td>
                   <td><div class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions </button>
  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1">
    <h6 class="dropdown-header">Status</h6>
    <form method="post">
     <input type="text" name="orderid" value="' . $row['s'] . '" required hidden>  
    <button type="submit" name="submitstat" class="dropdown-item" value="available">Mark as Available</button>
    <button  type="submit" name="submitstat" class="dropdown-item" value="unavailable">Mark as Unavailable</button>
   </form>
</div></td>';
   echo " <td><form action='editmeal.php' method='post'>
			     <input type='text' name='ordid' value='" . $row['s'] . "' required hidden/>  
                 <input type='submit' name='submit' value='Edit' class='btn btn-primary btn-sm'/  ></form></td></tr>";
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
				<h6 style="color:black;">Add New Meal</h6>
				</div>
                <div class="modal-body">
                  <form id="form" name="form" action="" method="post" enctype="multipart/form-data" > 
       <p><input type="text" class="form-control" placeholder="*Name" name="name" required/></p>
	   <p><input type="number" class="form-control"   placeholder="*Price" name="price" required></p>
	   <p><input type="number" class="form-control"   placeholder="*Discount Price" name="discount" required></p>
	   <p><textarea  class="form-control" placeholder="Enter little details eg This contains a plate of rice and noodles" name="bio" required></textarea></p>
       <p><select class="form-control" name="category" required>
	   <option selected="selected" value="">Choose Item Category</option>
	    <?php
   $sql = "SELECT * from delta_category where status!=''";
   $sql2 = mysqli_query($con,$sql);
   while ($row = mysqli_fetch_array($sql2)) {
   echo "
   <option value='".$row['s']."'> ".$row['name']." </option>
   ";
}
?>
	   </select>
	   </p>
	   <p><input type="file" name="file" class="form-control" required/></p>

					  <div class="modal-footer">
					  <input id="submit" name="submit" class="btn btn-sm btn-primary shadow-sm" type="submit" value="Add Meal"></form>
                    </div>
                  </div>
                </div></div>
             </div><!-- End Modal Dialog Scrollable-->
	
      </div>
    </section><!-- End About Section -->
  </main><!-- End #main -->

  <?php include"footer.php" ?>