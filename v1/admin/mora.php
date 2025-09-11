<?php include "header.php" ?>

 <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">
          <style>
           img {
   
    width:  100px;
    height: 100px;
    object-fit: cover;
}
          </style>
<?php

 include "connect_to_mysqli.php";
	  $sa =  $_POST['idea'];

			 $sql = "SELECT * from member where s ='$sa'";
		  $sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
    $nem=$row['first'];
    $nam=$row['last'];
    $nom=$row['nom'];
    $sad=$row['id'];
    $imageA='../member/'.$row["file_name"];
	$imageB='../member/'.$row["fila_name"];
	$imageC='../member/'.$row["filo_name"];
	$imageD='../member/'.$row["fili_name"];
	$imageE='../member/'.$row["filu_name"];

}
   
   
    ?>
        <div class="section-title">
          <h2>MEMBERSHIP PACKAGE</h2>
          <p>For <b><?php echo $nem; ?>  <?php echo $nam; ?></b></p>
          <p>User(s)  <b>: <?php echo $nom; ?></b></p>
          <p><img src="<?php echo $imageA; ?>" alt="" />
          <img src="<?php echo $imageB; ?>" alt="" />
          <img src="<?php echo $imageC; ?>" alt="" />
          <img src="<?php echo $imageD; ?>" alt="" />
          <img src="<?php echo $imageE; ?>" alt="" /></p>
        </div>
        
        
<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

                 <thead>
				<tr  bgcolor="#CCCCCC">
					
					<th data-column-id='employee_name'  width='200px'>No</th>
					<th data-column-id='employee_name'  width='200px'>Service</th>
				</tr>
			</thead>
		  <?php
    	$sql = "SELECT * from meme where id='$sad'  ORDER BY s DESC";
		$sql2 = mysqli_query($con,$sql);
		$i=1;
while ($row = mysqli_fetch_array($sql2)) {

echo"

<tr bgcolor='#fff'><td width='100px' >" . $i++ . "</td><td width='100px'>" . $row['name'] . "</td>
</tr>
               ";  
				
}
	 
		 
	
	  ?> 
	  </table></div>
	  </p>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->
  <?php include"footer.php" ?>