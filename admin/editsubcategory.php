<?php include "header.php"; 

if(isset($_GET['category'])){

            $category = $_GET['category'];
	        $sql = "SELECT * from sub_category where id = '$category'  ";
			$sql2 = mysqli_query($con,$sql);
		    while($row = mysqli_fetch_array($sql2))
				    
					 {
					  $id = $row["s"]; 
					  $categoryname = $row["name"];   					
					  $describe = $row['description'];
					  $main = $row['main_category'];
					  $file = $row['file_name'];
					
					  }}
					  
					  else{
					      header("location: categories.php");
					  }
					  
					  
					  
            $sql = "SELECT * from category where id = '$main'  ";
			$sql2 = mysqli_query($con,$sql);
		    while($row = mysqli_fetch_array($sql2))
				    
					 {$mainname = $row["name"]; }					  
					  
?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-primary-800"><?php echo $categoryname; ?></h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Sub Category</li>
            </ol>
          </div>
          
<div class="col-lg-12" ><?php include "updatesub.php"; ?></div> 
<form method="post" enctype="multipart/form-data">

<p><label>Name</label>
<input type="text" name="name" class="form-control" value="<?php echo  $categoryname; ?>"  required  /></p>



<p><label>Description</label>
<textarea type="text" class="form-control" name="described" placeholder="*About Subcategory" required ><?php echo  $describe; ?></textarea></p>


 <p><label style="font-weight:800; ">Main Category</label>
<select class="form-control" name="categorys" required>
<option selected="selected" value="<?php echo $main ?>"><?php echo $mainname ?></option>
<?php 
	 
$sql = "select id,name from category where id!='$main'";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
}
			  ?></select></p>
	   </p>





<?php if($file==""){ ?><p style="color:red;">No file found</p><?php } ?>
<p><label>Upload New Picture</label>
<input type="file" name="file" class="form-control"/></p>
	  
<p><input  type="submit" value="Update Category" class="btn btn-primary" name="submit" /></p>
</form>        
          
          
<?php include "footer.php"; ?>