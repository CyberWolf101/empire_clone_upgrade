<?php
	  include "connect_to_mysqli.php";
	  if (isset( $_POST['email'])){
	  $code= $_POST['email'];
	  $password = $_POST['pass'];
	  	  

	  
	  
	 }
					
	  $id="";
	  if ($code == "" || $password == "")
	     {
		     
			$as= 'Please enter your email and password';
			
		 }
	
	  else 
	    {
		  $sql = "SELECT * from staff where email= '".$code."' && pass = '".$password."'";
		  $sql2 = mysqli_query($con,$sql);
		  
		    if (mysqli_affected_rows($con) == 0)
			  {
			  $we ='Incorrect Email or Password ';
				  } 
			 else 
			  {
			     while($row = mysqli_fetch_array($sql2))
				    
					{
					  $id = $row["s"];   					
					  $name = $row['first'];
					  $user = $row['user'];
					  $pass = $row['pass'];
					  $status = $row['status'];
					 
				
				
					session_start(); 
					
					
	$_SESSION['id']=$id;				 
  $_SESSION['user']=$code;
  $_SESSION['pass']=$password;
  
	   
  
					 echo header("location:dash.php");	
						 }
						 
					
			}
				 
			
			 } 
		
	  ?>
	