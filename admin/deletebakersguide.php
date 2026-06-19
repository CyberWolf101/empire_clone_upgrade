<?php
include "../connect.php";


if (!isset($_GET['id'])) {
    die("Invalid request");
}

$guide_id = mysqli_real_escape_string($con, $_GET['id']);

/*
-----------------------------------
STEP 1: DELETE INGREDIENTS FIRST
-----------------------------------
*/
mysqli_query($con, "
    DELETE FROM guides_needed_items
    WHERE guide_id = '$guide_id'
");

/*
-----------------------------------
STEP 2: DELETE GUIDE
-----------------------------------
*/
mysqli_query($con, "
    DELETE FROM bakers_guide
    WHERE guide_id = '$guide_id'
");

/*
-----------------------------------
STEP 3: REDIRECT
-----------------------------------
*/
header("Location: bakersguide.php");
exit;