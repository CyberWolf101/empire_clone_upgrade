<?php include "chk.php"; include "connect_to_mysqli.php";
	  $code = $password="" ;
	  $code = $_SESSION['user'];
	  $password =  $_SESSION['pass'];
	   $ide =  $_SESSION['ide'];
	  $check = "select * from staff where email = '".$code."' && pass = '".$password."'";
	   $query = mysqli_query($con,$check);
	     if (mysqli_affected_rows($con) == 0)
		   {
		      echo "<div class='era'><center>Authentication failed</center><br><br>";
			  echo "<center><a href = 'index.php'><font color='red'>LOGIN AGAIN</font></a></center></div>";
		   }
	     else
		   {
		    $sql = "SELECT * from staff where email = '".$code."'  ";
			
		   $sql2 = mysqli_query($con,$sql);
			  
			   while($row = mysqli_fetch_array($sql2))
				    
					{
										  $id = $row["s"];   					
					  $name = $row['name'];
					  
					  $email = $row['email'];
					  $mob = $row['phone'];
					  
					    $usid= $row['id'];
					    $imageURL='../staff/'.$row["file_name"];
					  
					  
					  
				
				
					  }
					  }
					
					  $sql = "SELECT * from cater where id='$usid'";
		  $sql2 = mysqli_query($con,$sql);
  while($row = mysqli_fetch_array($sql2))
				    
					{
					    					
					  
						$owo= $row['name'];
						
					 
					 
				   
					 }		
					 
					
				
				
	 
					  
?>