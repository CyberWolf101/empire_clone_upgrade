<?php include 'header.php';

$_SESSION = array();

if (isset($_COOKIE['adminID'])) {
   // setcookie("adminID", "", time()-3600, '/');
        setcookie("adminID", "", time() - 3600, "", "", true, true); // No path
   
}

unset($_SESSION['id']);
unset($_SESSION['email']);
unset($_SESSION['password']);
unset($_SESSION['name']);




session_destroy();

if (isset($_COOKIE['adminID'])) {
header("location:index.php"); 
} else {
echo "<script>alert('Could not log you out, sorry the system encountered an error.');</script>";
header("location:index.php"); 
} 
?> 

