<?php include "header.php";

if (isset($_GET['category'])){
$category=$_GET['category'];
}
else{
header("Location: " . $_SERVER['HTTP_REFERER']);
}
  
$sql = "SELECT * from delta_soups where id='$category' ";
$sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
{$name=$row["name"];
$describe=$row["description"];
$imageURL='../kitchen/'.$row["picture"]; 
}

?>

    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
    <div class="container" style="width:100%; margin:auto; ">
    <div class="section-title" style="color:#FFFFFF;">
    <h4 style="text-transform:uppercase; color:#FFC700;"><?php echo $name; ?></h4>
	<p><img src="<?php echo $imageURL ?> " style="width:100%; height:auto;"/></p>
    </div>
     
               <div class="row">
               <div class="col-lg-12 col-md-12">
	           <h6 style="text-transform:inherit; color:#FFFFFF;"><?php echo $describe; ?></h6>
			   <p><div class="btn-wrap" style="text-align:center;">
               <a href="menu.php?category=<?php echo $category;?>" class="submitn">CLICK TO ORDER</a>
			   <a href="index.php" class="submitn">BACK TO MENU</a></div>
               </div>
               </div></p>
               
  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->

 <?php include"footer.php";  ?>