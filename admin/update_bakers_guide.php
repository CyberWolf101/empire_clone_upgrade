<?php
include "../connect.php"; // your connection file
include "header.php";
if (!isset($_POST['guide_id'])) {
    die("Invalid request");
}

$guide_id = mysqli_real_escape_string($con, $_POST['guide_id']);

$item_ids  = $_POST['item_id'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$row_ids   = $_POST['row_id'] ?? [];

/*
-------------------------------------------------
STEP 1: GET EXISTING INGREDIENT IDS
-------------------------------------------------
*/
$existing = [];
$res = mysqli_query($con, "
    SELECT id
    FROM guides_needed_items
    WHERE guide_id = '$guide_id'
");

while ($r = mysqli_fetch_assoc($res)) {
    $existing[] = $r['id'];
}

/*
-------------------------------------------------
STEP 2: KEEP TRACK OF SUBMITTED IDS
-------------------------------------------------
*/
$submitted_ids = [];

/*
-------------------------------------------------
STEP 3: LOOP NEW/UPDATED DATA
-------------------------------------------------
*/
foreach ($item_ids as $key => $item_id) {

    $item_id = mysqli_real_escape_string($con, $item_id);
    $qty     = mysqli_real_escape_string($con, $quantities[$key]);

    $row_id = $row_ids[$key] ?? null;

    // UPDATE EXISTING ROW
    if (!empty($row_id)) {

        $row_id = mysqli_real_escape_string($con, $row_id);
        $submitted_ids[] = $row_id;

        mysqli_query($con, "
            UPDATE guides_needed_items
            SET item_id = '$item_id',
                quantity = '$qty'
            WHERE id = '$row_id'
            AND guide_id = '$guide_id'
        ");

    } else {

        // INSERT NEW ROW
        mysqli_query($con, "
            INSERT INTO guides_needed_items
            (guide_id, item_id, quantity)
            VALUES
            ('$guide_id', '$item_id', '$qty')
        ");
    }
}

/*
-------------------------------------------------
STEP 4: DELETE REMOVED ROWS
-------------------------------------------------
*/
foreach ($existing as $db_id) {

    if (!in_array($db_id, $submitted_ids)) {

        mysqli_query($con, "
            DELETE FROM guides_needed_items
            WHERE id = '$db_id'
            AND guide_id = '$guide_id'
        ");
    }
}
if (!empty($_POST['request_row'])) {
        // var_dump($_POST['request_row']);

    $code = 'BR' . date('YmdHis') . rand(100, 999);
    $user = $name ?? 'system';

    // CREATE MAIN REQUEST
    $insertRequest = mysqli_query($con, "
        INSERT INTO bakers_requests
        (request_code, guide_id, quantity, requested_by)
        VALUES
        ('$code', '$guide_id', 1, '$user')
    ");

    if (!$insertRequest) {
        die("Request insert failed: " . mysqli_error($con));
    }

    $request_id = mysqli_insert_id($con);

    // LOOP ITEMS
    foreach ($_POST['request_row'] as $rowId => $data) {

        if (empty($data['active'])) {
            continue;
        }

        $qty = !empty($data['qty']) ? $data['qty'] : 0;

        $res = mysqli_query($con, "
            SELECT item_id
            FROM guides_needed_items
            WHERE id = '$rowId'
        ");

        if (!$res || mysqli_num_rows($res) == 0) {
            continue;
        }

        $row = mysqli_fetch_assoc($res);
        $item_id = $row['item_id'];

        // FIXED TABLE NAME HERE
        $insertItem = mysqli_query($con, "
            INSERT INTO bakers_request_items
            (request_id, guide_id, item_id, quantity)
            VALUES
            ('$request_id', '$guide_id', '$item_id', '$qty')
        ");

        if (!$insertItem) {
            die("Item insert failed: " . mysqli_error($con));
        }
    }
}
/*
-------------------------------------------------
STEP 5: REDIRECT BACK
-------------------------------------------------
*/
header("Location: viewbakersguide.php?id=$guide_id");
exit;