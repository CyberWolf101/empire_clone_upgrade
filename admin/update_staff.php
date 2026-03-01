<?php 

if (isset($_POST['register'])) {

    // Sanitize inputs
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $category = mysqli_real_escape_string($con, $_POST['role']);

    $selectedSections = $_POST['sections'] ?? [];
    $commaSeparatedSections = implode(',', $selectedSections);

    // Emergency contact fields
    $emergency_name = mysqli_real_escape_string($con, $_POST['emergency_name'] ?? '');
    $emergency_phone = mysqli_real_escape_string($con, $_POST['emergency_phone'] ?? '');
    $emergency_address = mysqli_real_escape_string($con, $_POST['emergency_address'] ?? '');

    // Single optimized update query
    $update = mysqli_query($con, "UPDATE admin SET 
        name='$name',
        email='$email',
        password='$password',
        status='$category',
        sections='$commaSeparatedSections',
        emergency_name='$emergency_name',
        emergency_phone='$emergency_phone',
        emergency_address='$emergency_address'
    WHERE s='$id'")
    or die('Could not connect: ' . mysqli_error($con));

    echo '<p style="color:blue;">Manager Details Updated Successfully!</p>';
    header('Refresh:1; url=managers.php');

}
?>