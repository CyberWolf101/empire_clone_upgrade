<?php
// $db_host = "localhost";
// $db_username = "root";  
// $db_pass = "";         
// $db_name = "oshofree_chbluxuryempirenew"; 

if ($_SERVER['SERVER_NAME'] === 'localhost') {
    // Local development
    $db_host = "localhost";
    $db_username = "root";
    $db_pass = "";
    $db_name = "oshofree_chbluxuryempirenew";
} else {
    // Online production
    $db_host = "localhost";
    $db_username = "oshofree_luxury";
    $db_pass = "chbluxuryempire";
    $db_name = "oshofree_chbluxuryempirenew";
}


$con = mysqli_connect($db_host, $db_username, $db_pass, $db_name);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// echo "✅ Database connected successfully!";
?>