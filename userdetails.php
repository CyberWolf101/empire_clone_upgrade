<?php
include "header.php";

// Redirect if user details are already set and order is pending
if (isset($_SESSION['username']) && $_SESSION['username'] != "") {
    if (isset($saloon) && !empty($saloon)) {
        $sql = "SELECT status, pay_status FROM saloon_orders WHERE id='$saloon'";
        $result = mysqli_query($con, $sql);
        if ($row = mysqli_fetch_array($result)) {
            if ($row['status'] == 'pending' && $row['pay_status'] == 'pending') {
                header("Location: cart.php");
                exit;
            }
        }
    }
}

// Initialize $saloon for new order
// Check if we already have an order ID in cookie
if (isset($_COOKIE['foodID']) && !empty($_COOKIE['foodID'])) {
    $saloon = $_COOKIE['foodID'];
} else {
    // Create a new order ID and cookie
    $saloon = uniqid('ORDER_');
    setcookie("foodID", $saloon, time() + 3600, "/", "", true, true);

    // Insert new pending order
    $query = "INSERT INTO saloon_orders (id, status, pay_status, payment_confirmed, name, email, phone, bookingtype, method, date, saloonkit, total_amount, card_amount, cash_amount, transfer_amount, pos_amount, giftcard, gift_amount, type, section, preorder, preorder_date) 
    VALUES ('$saloon', 'pending', 'pending', 0,'','','', 0,'', '',0,0,0,0,0,0,0,0,'','',0,'')";
    if (!mysqli_query($con, $query)) {
        error_log('Userdetails.php: Failed to create order: ' . mysqli_error($con) . " | Query: $query");
    }
}
if (isset($_POST['submitdetails'])) {
    $username = mysqli_real_escape_string($con, $_POST['name']);
    $mail = mysqli_real_escape_string($con, $_POST['email']);
    $mob = mysqli_real_escape_string($con, $_POST['mobile']);

    $query = "UPDATE saloon_orders SET name='$username', email='$mail', phone='$mob' WHERE id='$saloon'";
    if (!mysqli_query($con, $query)) {
        error_log("Userdetails.php: Failed to update saloon_orders: " . mysqli_error($con) . " | Query: $query");
        die('Could not update order: ' . mysqli_error($con));
    }

    $_SESSION['username'] = $username;
    $_SESSION['customer_email'] = $mail;
    $_SESSION['customer_phone'] = $mob;

?>
    <script>
        alert('Personal details uploaded successfully!');
        window.location.href = "cart.php"
    </script>

<?php
    exit;
}
?>

<style>
    .btn-buya {
        display: inline-block;
        padding: 6px !important;
        border: none;
        color: #fff;
        font-size: 12px !important;
        text-transform: uppercase;
        font-family: "Montserrat", sans-serif;
        font-weight: 700;
        transition: 0.3s;
        background: #FEBF01;
        width: 100px;
    }

    .btn-buya:hover {
        font-weight: 800;
        background: #FEBF01;
    }
</style>

<div style="margin-top:100px; color:#FFFFFF;">
    <div class="justify-content-center" align="center">
        <form action="" method="post">
            <p><b>PERSONAL DETAILS</b></p>
            <p>Submit your details to proceed</p>
            <div class="col-lg-4">
                <p><input type="text" class="form-control" name="name" placeholder="Your Name.." required /></p>
                <p><input type="email" class="form-control" name="email" placeholder="Your Email.." required /></p>
                <p><input type="number" class="form-control" name="mobile" placeholder="Your Mobile Number.." required /></p>
            </div>
            <div class="col-lg-12">
                <p><button type="submit" name="submitdetails" value="1" class="btn-buya">SUBMIT</button></p>
            </div>
        </form>
    </div>
</div>

<?php


include "footer.php";

?>