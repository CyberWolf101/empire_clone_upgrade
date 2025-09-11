mAIN.PHP

<?php include"header.php" ?>
<main id="main">
<style>
.box{
    background:black;
    color:white;
    padding:30px;
    margin:10px;
   
}    
    
.img{
 max-width:40%;
max-height:40%;
border-radius:50%; 
}    

.kay{
    font-size:16px;
}    
</style>
    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">
        <div class="section-title" style="margin:1;">
          <h2>Walk In-Booking - Category</h2>
          <p>Choose Category</p>
        </div>
<div class="row">
<?php
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from cater  where id!='0015' AND id!='0017' ORDER BY name ASC LIMIT 100";
		$sql2 = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($sql2))
	{
	$imageURL='../categ/'.$row["file_name"];
	echo'
	<div class="col-lg-4"> 
    <div class="box">
   <p><img src="'. $imageURL.'"  alt="" class="img" />  </p> 
   <h4 class="mb-3 kay" style="text-transform:uppercase;" >'.$row["name"].'</h4>
   <div class="button_container">
                            <form action="mainsub.php" method="post"><input type="hidden" value="'.$row['id'].'" name="cate" />
                            <button type="submit" name="submit" value="submit"  class="submitn"><span>CLICK TO BOOK</span></button> 
                            </form></div> 
</div></div>  
';	}
						?>




</div>  
</section><!-- End About Section -->

   
  </main><!-- End #main -->
  <?php include"footer.php" ?>
  
  
  
  
  
  
  
  
  
  // ped kit //
if($raff=="Yes")
{
$sql = "SELECT all* from kit where id='$ran' && name='$see' && date='$dear'";
		$sql2 = mysqli_query($con,$sql);
			 if (mysqli_affected_rows($con) == 0)
			 {
   $submit = mysqli_query($con,"insert into kit(id, name,date) values ('$ran','$see','$dear')") or die ('Could not connect: ' .mysqli_error($con));
}
else
{
echo "";
}
}