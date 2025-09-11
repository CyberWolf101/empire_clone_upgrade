<?php include "header2.php"; ?>
<body >

 

  <style>
  .guy{
  margin:0px;
  padding:0px;
  background-color:#FFFFFF;
  color:#000000;
  font-weight:600;
  }
  
  </style>

  <main id="main">
 
    <!-- =======rack and Book Section ======= -->
    <section id="about" class="about">
      <div class="container" data-aos="fade-up">
	  <h3> My <span style="color:#FFC700;">Dashboard</span></h3>
	  
<div class="row content">
	
	<?php
			   $date = date('Y-m-d');
		
		$sqd = "SELECT count(*) As 'total'
						FROM cart where staff='$name' && date='$date' && status='Paid'
						 ";
		 $sqd2 = mysqli_query($con,$sqd);
		 $dadd = mysqli_fetch_assoc($sqd2);
		 
 
 $kan=$dadd['total'];    
				  	
						?>

<div class="col-lg-6" style="padding:40px; width:100%;  background-color:#fff; color:#000000; " align="center" >
      
		 <h4>Total Appointment for Today<br><span style="font-size:60px; font-weight:900; font-family:Poppins;"><?php echo $kan; ?></span></h4>
		 <p><a href="rent.php" class="submitn">View All Orders</a></p>
		 </div>	
 

         <div class="col-lg-6" style="padding:20px; width:100%; margin:auto; font-size:14px; " align="center">
		 <p><img src="<?php echo $imageURL; ?>" style="max-height:20%; max-width:20%; border-radius:50%; background-color:#FFFFFF; border:#FFC700 solid 2px;"/></p>
		  <h4 >Hola, <?php echo $name; ?>!</h4>
		   <p style="color:#FFC700;">@<?php echo $owo; ?></p>
		   <p ><?php echo $email; ?><br><?php echo $mob; ?><br></p>
		 </div>
		











		
		
		
		
		
	 </div></div>
    </section><!--  Book Section  --> 
	
	<?php 
		     include "connect_to_mysqli.php";
		     if (isset($_POST['submitt']))
{

$idee =  $_POST['idn']; $teed =  $_POST['tdn'];  $teet =  $_POST['ddn'];
$fa="Done";
$insert = mysqli_query($con,"UPDATE cart SET app= '$fa' where id='$idee' && timef='$teed' && date='$teet' ") or die ('Could not connect: ' .mysqli_error($con)); 


					echo'<p style="color:green; text-align:center;">Appointmnet Successfully Done!</p>';
					}
					 
					 
					 ?>
	 <!-- =======rack and Book Section ======= -->
    <section id="today" class="about" style="margin-bottom:100px;" >
	<h2 style="text-align:center;"> TODAY APPOINTMENTS </h2>
      <div class="container" data-aos="fade-up">
<div class="row content">

<?php
 include "connect_to_mysqli.php";
 $sql = "SELECT all *from cart where staff='$name' && date='$date' && status='Paid' ORDER BY timef DESC  ";
		  $sql2 = mysqli_query($con,$sql);
		while($row = mysqli_fetch_array($sql2))
				 {
				 
			  $ap=$row['app'];
   if($ap=='Done')
   {
   $coke='';
}
else
{
$coke=' <p><form method="post" action=""><input type="text" class="form-control"  value="'.$row["id"].'" name="idn" required hidden ><input type="text" class="form-control"  value="'.$row["timef"].'" name="tdn" required hidden >
<input type="text" class="form-control"  value="'.$row["date"].'" name="ddn" required hidden ><button type="submit" class="submitn" name="submitt">
Mark as Done</button></form></p>';
}
										 echo'
<div class="col-lg-12 guy" style="padding:20px; width:100%; margin:auto;">
<p ><h4 style="color:#FFC700;"> Appointmnet No:  '.$row["id"].' </h4></p>
<p style=" font-size:12px;">Service:    '.$row["service"].'
<br>
Client:    '.$row["name"].'<br>
Client Phone Number:    '.$row["phone"].'
</p>
<p style=" font-size:12px; color:#FFC700;" ><i>Time:  '.$row["timef"].'</i></p>
'.$coke.'
</div>';
}

?>
		
		
		
		
	 </div></div>
    </section><!--  Book Section  -->  
  


  </main><!-- End #main -->
  <?php include "footer2.php"; ?>
 