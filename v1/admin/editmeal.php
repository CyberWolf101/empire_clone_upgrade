<?php include"header.php" ?>
<main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">
      <?php include "edit_meal.php"; ?>
        <div class="section-title">
          <h2>Edit Meal Details</h2>
          <p>Edit meal details</p>
        </div>
<?php
$ode = $_POST['ordid'];
	
$sql = "SELECT * from delta_kitchen where s= '".$ode."'  ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{
$id = $row["s"]; 
$name= $row["name"]; 
$price= $row["price"];
$discount= $row["discount"];
$category= $row["category"];
$bio= $row["bio"];
}

$sqls = "SELECT all* from delta_category where s='$category' ";
$sql2s = mysqli_query($con,$sqls);
while($rows = mysqli_fetch_array($sql2s))
{				      
$cat=$rows['name'];}
?>	  

       <form id="form" name="form" action="" method="post" enctype="multipart/form-data" > 
       <p><input type="text" class="form-control" value="<?php echo  $name; ?>"    placeholder="*Name" name="name" required/></p>
	   <p><input type="number" class="form-control" value="<?php echo  $price; ?>"  placeholder="*Price" name="price" required></p>
	   <input type="hidden" name="rack" class="form-control" value="<?php echo  $id; ?>"required readonly >
	   <p><input type="number" class="form-control" value="<?php echo  $discount; ?>"  placeholder="*Discount Price" name="discount" required></p>
	   <p><textarea  class="form-control" placeholder="Enter little details eg This contains a plate of rice and noodles" name="bio" required><?php echo  $bio; ?></textarea></p>
       <p><select class="form-control" name="category" required>
	   <option selected="selected" value="<?php echo $category ?>"><?php echo $cat ?></option>
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
	   <p><input type="file" name="file" class="form-control"/></p>
      <p><input id="submit" name="upsubmit" class="btn btn-sm btn-primary shadow-sm" type="submit" value="Update Meal"></p>
      </form>
					  























<?php include"footer.php" ?>