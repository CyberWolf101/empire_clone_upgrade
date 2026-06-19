<?php
include "header.php";
include "../connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $item = mysqli_real_escape_string($con, $_POST["item"]);

    /*
    ------------------------------------------------
    STEP 1: GENERATE GUIDE ID
    ------------------------------------------------
    */
    function generateGuideId($con)
    {
        $result = mysqli_query($con, "SELECT id FROM bakers_guide ORDER BY id DESC LIMIT 1");
        $row = mysqli_fetch_assoc($result);

        $nextId = $row ? $row['id'] + 1 : 1;

        return 'BG' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    $guideId = generateGuideId($con);

    /*
    ------------------------------------------------
    STEP 2: INSERT GUIDE
    ------------------------------------------------
    */
    mysqli_query($con, "
        INSERT INTO bakers_guide (item, guide_id)
        VALUES ('$item', '$guideId')
    ");

    /*
    ------------------------------------------------
    STEP 3: INSERT INGREDIENTS
    ------------------------------------------------
    */
    if (!empty($_POST['item_id'])) {

        foreach ($_POST['item_id'] as $index => $itemId) {

            $itemId = mysqli_real_escape_string($con, $itemId);
            $qty = mysqli_real_escape_string($con, $_POST['quantity'][$index]);

            mysqli_query($con, "
                INSERT INTO guides_needed_items
                (guide_id, item_id, quantity)
                VALUES
                ('$guideId', '$itemId', '$qty')
            ");
        }
    }

    /*
    ------------------------------------------------
    STEP 4: CREATE REQUEST (ONLY ONCE)
    ------------------------------------------------
    */

    // if (isset($_POST['create_request'])) {

    //     $code = 'BR' . date('YmdHis'); // request code
    //     $user = $_SESSION['username'] ?? 'system';

    //     mysqli_query($con, "
    //         INSERT INTO bakers_requests
    //         (request_code, guide_id, quantity, requested_by)
    //         VALUES
    //         ('$code', '$guideId', 1, '$user')
    //     ");
    // }
    // if (isset($_POST['request_row']) && is_array($_POST['request_row'])) {

    //     foreach ($_POST['request_row'] as $rowId => $data) {

    //         if (isset($data['active'])) {

    //             $qty = !empty($data['qty'])
    //                 ? $data['qty']
    //                 : 0;

    //             // get ingredient info
    //             $res = mysqli_query($con, "
    //             SELECT item_id, guide_id
    //             FROM guides_needed_items
    //             WHERE id = '$rowId'
    //         ");

    //             $row = mysqli_fetch_assoc($res);

    //             $item_id = $row['item_id'];
    //             $guide_id = $row['guide_id'];

    //             $code = 'BR' . date('YmdHis') . rand(10, 99);
    //             $user = $_SESSION['username'] ?? 'system';

    //             mysqli_query($con, "
    //             INSERT INTO bakers_requests
    //             (request_code, guide_id, quantity, requested_by)
    //             VALUES
    //             ('$code', '$guide_id', '$qty', '$user')
    //         ");
    //         }
    //     }
    // }
    


    /*
    ------------------------------------------------
    SUCCESS
    ------------------------------------------------
    */
    $_SESSION['success_message'] = "Guide created successfully!";
    header("Location: bakersguide.php");
    exit();
}
