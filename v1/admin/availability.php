
<?php
 include 'header.php';
// Assume you have established a database connection

$selectedStaff = $_POST['staffName'];
$bookingTime = $_POST['bookingTime'];
$bookingDate = $_POST['bookingDate'];

// Perform a query to check staff availability in the database
$query = "SELECT COUNT(*) as count FROM cart WHERE staff = '$selectedStaff' AND time = '$bookingTime' AND date = '$bookingDate'";
$result = mysqli_query($connection, $query);

$response = array('isBooked' => false);

if ($result) {
  $row = mysqli_fetch_assoc($result);
  $count = $row['count'];
  if ($count > 0) {
    $response['isBooked'] = true;
  }
} else {
  // Handle query error
  $response['error'] = 'Failed to check staff availability.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
