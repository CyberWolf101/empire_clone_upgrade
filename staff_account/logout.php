<?php session_start();

$_SESSION = array();

if (isset($_COOKIE['adminID'])) {
    setcookie("adminID", '', time()-42000, '/');
	setcookie("passCookie", '', time()-42000, '/');
}

unset($_SESSION['id']);
unset($_SESSION['email']);
unset($_SESSION['password']);
unset($_SESSION['name']);




session_destroy();

if(!$_SESSION['adminid']){
header("location:index.php"); 
} else {
echo "<script>alert('Could not log you out, sorry the system encountered an error.');</script>";
header("location:index.php"); 
} 
?> 

