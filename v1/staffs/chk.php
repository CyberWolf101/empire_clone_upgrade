<?php
session_start();

global $id;

$user="";

if (isset($_SESSION['id'])) {
	
	 $user_id = $_SESSION['id'];
	 

} else {
	
"";
}

include "connect_to_mysqli.php";
setcookie(
  "CookieName",
  "CookieValue",
  time() + (10 * 365 * 24 * 60 * 60)
);

?>