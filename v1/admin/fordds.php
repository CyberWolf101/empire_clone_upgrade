<?php include "connect_to_mysqli.php";
		 
		  

$ema=$_POST['rack'];

$bah=$_POST['ad'];



			  if (strlen($bah) == 0)
						{
						$rw = '<p style="color:red;">Upload Details Correctly</p>';
						}
						else{
						
		
					
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						


 $sql = "SELECT * from food where id  = '".$ema."'  ";
 $sql2 = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($sql2))
	{
										  $me = $row["nom"];
	}
					
	if ($bah > $me)	
	{
   $kab=$bah-$me;
   $submit = mysqli_query($con,"insert into stock(id,des,nom) values ('$ema','add','$kab')") or die ('Could not connect: ' .mysqli_error($con));	    
	    
	}
	else
	{
	$kab=$me-$bah;
   $submit = mysqli_query($con,"insert into stock(id,des,nom) values ('$ema','minus','$kab')") or die ('Could not connect: ' .mysqli_error($con));	    
	}									

						
						
						
						
						
						
						
		 $insert = mysqli_query($con,"UPDATE food SET nom= '$bah' where id='$ema'") or die ('Could not connect: ' .mysqli_error($con)); 				
						
						
						
				
			
					
					$norm='<p style="color:blue;">Update Successful!</p>';
					
					header('Refresh:1; url=ford.php');
                    echo $norm;
	  
	  
	  }
	  
	  ?>