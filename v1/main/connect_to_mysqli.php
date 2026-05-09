<?php


if ($_SERVER['SERVER_NAME'] === 'localhost') {
    // Local development
    $db_host = "localhost";
    $db_username = "root";
    $db_pass = "";
    $db_name = "oshofree_chbluxuryempirenew";
} else {
    // Online production
    $db_host = "localhost";
    $db_username = "chbluxu1_empire";
    $db_pass = "4Ew5939xu8QzwbICPC";
    $db_name = "chbluxu1_chbluxuryempirenew";
}


$con = mysqli_connect($db_host, $db_username, $db_pass, $db_name);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// echo "✅ Database connected successfully!";
?>