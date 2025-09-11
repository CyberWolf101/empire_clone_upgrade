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
		  $sql = "SELECT * from admob where email= '".$code."' && password = '".$password."'";
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
					 
				   
					 }
					 
					 if ($status=="superadmin")				    
					
					    {
						   
				
					session_start(); 
					
					
	$_SESSION['id']=$id;				 
  $_SESSION['user']=$code;
  $_SESSION['pass']=$password;
  
	   
  
					 echo header("location:dashboard.php");	
						 }
						 	
					
						 else if ($status=="cashier")	
						 {
						 session_start(); 
					
					
	$_SESSION['id']=$id;				 
  $_SESSION['user']=$code;
  $_SESSION['pass']=$password;
  
	   
  
					 echo header("location:dasher.php");	
						 }
						 
						 
						  else if ($status=="store")	
						 {
						 session_start(); 
					
					
	$_SESSION['id']=$id;				 
  $_SESSION['user']=$code;
  $_SESSION['pass']=$password;
  
	   
  
					 echo header("location:dashed.php");	
						 }
						 
						 	  else if ($status=="stock")	
						 {
						 session_start(); 
					
					
	$_SESSION['id']=$id;				 
  $_SESSION['user']=$code;
  $_SESSION['pass']=$password;
  
	   
  
					 echo header("location:ford.php");	
						 }
						 
					
			}
				 
			
			 } 
		
	  ?>
	