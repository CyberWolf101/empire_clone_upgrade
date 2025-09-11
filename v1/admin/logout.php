<?php session_start();

$_SESSION = array();

if (isset($_COOKIE['idCookie'])) {
    setcookie("idCookie", '', time()-42000, '/');
	setcookie("passCookie", '', time()-42000, '/');
}

unset($_SESSION['id']);
unset($_SESSION['email']);
unset($_SESSION['password']);
unset($_SESSION['name']);




session_destroy();

if(!$_SESSION['id']){
header("location:index.php"); 
} else {
print "<h2>Could not log you out, sorry the system encountered an error.</h2>";
exit();
} 
?> 

