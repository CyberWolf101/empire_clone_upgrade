<?php include "../connect.php"; 


// Get the selected date from the AJAX request
$selectedDate = $_POST['selectedDate'];

// Create the SQL query
$sql = "SELECT * FROM rental_dates WHERE date = '$selectedDate'";

// Execute the query
$result = mysqli_query($con, $sql);

// Check if any rows were returned
if (mysqli_num_rows($result) > 0) {
    echo 'booked'; // Date is booked
} else {
    echo 'not_booked'; // Date is not booked
}


?>