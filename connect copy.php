<?php

// $db_host = "localhost"; 

// $db_username = "oshofree_luxury"; 

// $db_pass = "chbluxuryempire"; 

// $db_name = "oshofree_chbluxuryempirenew";

// $con = mysqli_connect ("$db_host","$db_username","$db_pass","$db_name");


$db_host = "localhost";
$db_username = "root";   // default XAMPP MySQL username
$db_pass = "";           // default XAMPP MySQL password is empty
$db_name = "oshofree_chbluxuryempirenew"; // must match the DB you just created

$con = mysqli_connect($db_host, $db_username, $db_pass, $db_name);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

echo "✅ Database connected successfully!";
?>
?>

