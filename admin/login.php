<?php
	  include "../connect.php";
	  if (isset( $_POST['login'])){
	  $code= $_POST['email'];
	  $password = $_POST['password'];
	  	  

	  
	  
	 
					
	     $id="";
	     if ($code == "" || $password == "")
	     {
		   	echo 'Please enter your email and password';
		  }
	
	     else 
	    {
		  $sql = "SELECT * from admin WHERE email='".$code."' && password = '".$password."'";
		  $sql2 = mysqli_query($con,$sql);
		  
		    if (mysqli_affected_rows($con) == 0)
			  {
			 echo'Incorrect Email or Password ';
				  } 
			 else 
			  {
			     while($row = mysqli_fetch_array($sql2))
				    
					{
					  $id = $row["s"]; 
					  $adminid = $row["email"]; 
					  $user = $row['username'];
					  $pass = $row['password'];
					  $status = $row['status'];
					 
				   
					 }
					 
					
					session_start(); 
					
					
  $_SESSION['id']=$id;				 
  $_SESSION['user']=$status;
  
  //current datetime
  $date=date('d/m/y H:i:s');


    //set cookie 
    setcookie("adminID", $adminid, time() + (10 * 365 * 24 * 60 * 60));


	   

    $previousPage = $_SESSION['previous_page'] ?? 'dashboard.php';
    header("Location: $previousPage");	
	 	
  

						 }} }
	  ?>
	