<?php 




	         
$checkEmail = mysqli_query($con, "SELECT * FROM appointments WHERE staff='$selectedStaff' AND date='$selectedDate' ");
$currentTime = strtotime('now'); // Get the current time in seconds 
$sixHoursFromNow = $currentTime + 6 * 3600; // Add 6 hours in seconds

if (strtotime($selectedDate) == strtotime('today')) {
    // Selected date is today
    $startOfDay = max(strtotime('7:00 AM'), $sixHoursFromNow); // Start from the current time + 6 hours or 12:00 AM, whichever is later
    $endOfDay = strtotime('8:59 PM');
    $currentSlot = max($currentTime, $startOfDay); // Start from the current time or 12:00 AM, whichever is later

    // Loop to display time slots
    while ($currentSlot + $serviceDuration * 60 <= $endOfDay) {
        echo "<option value='" . date('h:i A', $currentSlot) . "'>" . date('h:i A', $currentSlot) . " - " . date('h:i A', $currentSlot + $serviceDuration * 60) . "</option>";
        $currentSlot += $serviceDuration * 60;
    }
} elseif (mysqli_num_rows($checkEmail) == 1 ) {

$startOfDay = strtotime('7:00 AM');
$endOfDay = strtotime('8:59 PM');
$currentSlot = $startOfDay;
while ($currentSlot + $serviceDuration * 60 <= $endOfDay) {
    echo "<option value='" . date('h:i A', $currentSlot) . "'>" . date('h:i A', $currentSlot) . " - " . date('h:i A', $currentSlot + $serviceDuration * 60) . "</option>";
    $currentSlot += $serviceDuration * 60;
}}

elseif(mysqli_num_rows($checkEmail) > 1 ){


// Retrieve existing appointments for the selected staff on the given day
$sql = "SELECT start_time, end_time FROM appointments WHERE staff_id = $selectedStaff AND DATE(date) = '$selectedDate'";
$result = mysqli_query($con, $sql);

$bookedTimeSlots = [];

while ($row = mysqli_fetch_assoc($result)) {
    $bookedTimeSlots[] = [
        'start' => strtotime($row['start_time']),
        'end' => strtotime($row['end_time'])
    ];
}

// Calculate available time slots
$availableTimeSlots = [];
$startOfDay = strtotime('12:00 AM');
$endOfDay = strtotime('11:59 PM');

// Dynamic duration
$dynamicDuration = $serviceDuration; // Change this to your dynamic duration in minutes

// Find available slots based on booked appointments
$prevEndTime = $startOfDay;

foreach ($bookedTimeSlots as $bookedSlot) {
    if ($bookedSlot['start'] - $prevEndTime >= $serviceDuration * 60) {
        $time = $prevEndTime;
        while ($time + $dynamicDuration * 60 <= $bookedSlot['start']) {
            $availableTimeSlots[] = ['start' => $time, 'end' => $time + $dynamicDuration * 60];
            $time += $dynamicDuration * 60;
        }
    }
    $prevEndTime = $bookedSlot['end'];
}

// Check for remaining available slots after booked appointments
if ($endOfDay - $prevEndTime >= $serviceDuration * 60) {
    $time = $prevEndTime;
    while ($time + $dynamicDuration * 60 <= $endOfDay) {
        $availableTimeSlots[] = ['start' => $time, 'end' => $time + $dynamicDuration * 60];
        $time += $dynamicDuration * 60;
    }
}

// Display available time slots or fully booked message
if (empty($availableTimeSlots)) {
   echo "<script>alert('Sorry, $selectedStaffName has been booked for the entire day');</script>";
   header("Refresh: 0; url=" . $_SERVER['HTTP_REFERER']);
} else {
    foreach ($availableTimeSlots as $slot) {
        echo "<option value='" . date('h:i A', $slot['start']) . "'>" . date('h:i A', $slot['start']) . " - " . date('h:i A', $slot['end']) . "</option>";
}}






















}


?>