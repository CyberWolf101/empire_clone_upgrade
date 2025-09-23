<?php
if (isset($_POST['pay'])) {
    $payments = $_POST['payment'] ?? [];
    $totalEntered = 0;

    foreach ($payments as $method => $data) {
        if (!empty($data['enabled']) && is_numeric($data['amount'])) {
            $totalEntered += (float) $data['amount'];
        }
    }

    if ($totalEntered != $total_all) {
        echo "<script>alert('Error: Payment amounts must equal Grand Total (₦$total_all). You entered ₦$totalEntered');</script>";
    } else {
        $customertype = $_POST['customertype'];
        $customername = !empty($_POST['customername']) ? $_POST['customername'] : "nil";
        $customerphone = !empty($_POST['customerphone']) ? $_POST['customerphone'] : "nil";
        $customermail = !empty($_POST['customermail']) ? $_POST['customermail'] : "nil";
        $customer_id = !empty($_POST['customer']) ? $_POST['customer'] : "nil";
        $method = isset($_POST['method'])
            ? (is_array($_POST['method']) ? implode(", ", $_POST['method']) : $_POST['method'])
            : "nil";

        $datetime = date('Y-m-d H:i:s');

        if ($customertype == "old") {
            $sql = "SELECT * FROM saloon_orders WHERE name = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $customer_id);
            $stmt->execute();
            $sql2 = $stmt->get_result();
            while ($row = $sql2->fetch_assoc()) {
                $customername = $row['name'];
                $customerphone = $row['phone'];
                $customermail = $row['email'];
            }
            $stmt->close();
        }

        // Update saloon_orders and refreshments
        $stmt = $con->prepare("UPDATE saloon_orders SET pay_status = 'paid', status = 'completed', name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("ssss", $customername, $customermail, $customerphone, $saloon);
        $stmt->execute() or die('Could not connect: ' . mysqli_error($con));
        $stmt->close();

        $stmt = $con->prepare("UPDATE refreshments SET status = 'processed' WHERE orderid = ?");
        $stmt->bind_param("s", $saloon);
        $stmt->execute() or die('Could not connect: ' . mysqli_error($con));
        $stmt->close();

        // Handle payment amounts
        $payments = $_POST['payment'] ?? [];
        $posAmount = 0;
        $cashAmount = 0;
        $transferAmount = 0;

        foreach ($payments as $method => $data) {
            if (!empty($data['enabled']) && is_numeric($data['amount'])) {
                switch ($method) {
                    case "pos":
                        $posAmount = (float) $data['amount'];
                        break;
                    case "cash":
                        $cashAmount = (float) $data['amount'];
                        break;
                    case "transfer":
                        $transferAmount = (float) $data['amount'];
                        break;
                }
            }
        }

        $methodList = [];
        if ($posAmount > 0)
            $methodList[] = "POS";
        if ($cashAmount > 0)
            $methodList[] = "Cash";
        if ($transferAmount > 0)
            $methodList[] = "Bank Transfer";
        $methodString = implode(", ", $methodList);

        $stmt = $con->prepare("UPDATE saloon_orders SET pos_amount = ?, cash_amount = ?, transfer_amount = ?, method = ? WHERE id = ?");
        $stmt->bind_param("dddss", $posAmount, $cashAmount, $transferAmount, $methodString, $saloon);
        $stmt->execute() or die('Could not connect: ' . mysqli_error($con));
        $stmt->close();

        // Update stock in food_menu and refreshments
        $sqk = "SELECT itemid, quantity FROM refreshments WHERE orderid = ?";
        $stmt = $con->prepare($sqk);
        $stmt->bind_param("s", $saloon);
        $stmt->execute();
        $sqlp = $stmt->get_result();

        while ($rowe = $sqlp->fetch_assoc()) {
            $food = $rowe['itemid'];
            $value = $rowe['quantity'];

            // Fetch current stock quantity
            $sql = "SELECT quantity FROM food_menu WHERE s = ?";
            $stmt_food = $con->prepare($sql);
            $stmt_food->bind_param("s", $food);
            $stmt_food->execute();
            $sql_food = $stmt_food->get_result();

            if ($sql_food->num_rows > 0) {
                $row_food = $sql_food->fetch_assoc();
                $originalvalue = $row_food['quantity'];
                $rem_value = $originalvalue - $value;

                // Update stock quantity in food_menu
                $stmt_update = $con->prepare("UPDATE food_menu SET quantity = ? WHERE s = ?");
                $stmt_update->bind_param("is", $rem_value, $food);
                $stmt_update->execute() or die('Could not connect: ' . mysqli_error($con));
                $stmt_update->close();

                // Update total_left and date in refreshments
                $stmt_refresh = $con->prepare("UPDATE refreshments SET total_left = ?, date = ? WHERE orderid = ? AND itemid = ?");
                $stmt_refresh->bind_param("issi", $rem_value, $datetime, $saloon, $food);
                $stmt_refresh->execute() or die('Could not connect: ' . mysqli_error($con));
                $stmt_refresh->close();

                // Log stock change
                $stmt_log = $con->prepare("INSERT INTO stock_log (id, action, value, date) VALUES (?, 'minus', ?, ?)");
                $stmt_log->bind_param("sis", $food, $value, $datetime);
                $stmt_log->execute() or die('Could not connect: ' . mysqli_error($con));
                $stmt_log->close();
            } else {
                error_log("Item with s='$food' not found in food_menu for orderid='$saloon'");
            }
            $stmt_food->close();
        }
        $stmt->close();

        // Generate receipt HTML
        $sql = "SELECT * FROM refreshments WHERE orderid = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $saloon);
        $stmt->execute();
        $sql2 = $stmt->get_result();

        if ($sql2->num_rows > 0) {
            $name = [];
            $surname = [];
            $address = [];

            while ($row = $sql2->fetch_assoc()) {
                $name[] = $row['item'];
                $surname[] = $row['quantity'];
                $address[] = $row['totalprice'];
            }

            $html = "<p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#fff;' width='500px'>
     <tr style='border-bottom:#FFFFFF solid;'><td>OrderID</td><td style='color:#FFC700;' colspan='4' >" . htmlspecialchars($saloon) . "</td></tr>
     <tr><td style='color:#fff; text-align:center;'>Your Items</td><td>Quantity</td><td>Price</td></tr>";

            foreach ($name as $key => $value) {
                $html .= "<tr>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>" . htmlspecialchars($name[$key]) . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>" . htmlspecialchars($surname[$key]) . "</td>
          <td style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;" . htmlspecialchars($address[$key]) . "</td>
        </tr>";
            }

            $html .= "</table></p>";
        } else {
            $html = "";
        }
        $stmt->close();

        // Send mail if email exists
        if ($customermail != "" && $customermail != "nil") {
            $email_from = $sitemail;
            $email_to = $customermail;
            $email_subject = "Items Purchased Successfully! - $sitename";
            $email_message = "
        <center><div style='background-color:#000000; color:#fff !important; padding:10px 20px; width:500px;'>
        <p style='text-align:left;'>
        <img src='http://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='60px;' style='margin-top:13px;'>
        <font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>Total Cost: &#8358;" . htmlspecialchars($total_all) . "<br>" . htmlspecialchars($datetime) . "</font></p>
        <p style='color:#fff; font-size:13px;'>Hey there, " . htmlspecialchars($customername) . ", You have successfully paid for your items, attached below is your receipt details</p>
        $html
        <p style='color:#fff; font-size:13px;'>Thank you for your patronage and we hope to see you again soon!</p>
        <br><br>
        <p style='text-align:center;'><a href='chbluxuryempire.com' style='color:#FFC700;'>$sitename</a></p>
        </div></center>";

            $header = 'From: "' . $sitename . '" <' . $sitemail . '>' . "\r\n";
            $header .= "Cc: $sitemail \r\n";
            $header .= 'Reply-To: ' . $sitemail . '' . "\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";

            if (!@mail($email_to, $email_subject, $email_message, $header)) {
                echo '<center><font color="red">Mail cannot be submitted now due to server problems, please try again.</font></center>';
            }
        }

        // Unset session and clear cookie
        unset($_SESSION["order"]);
        if (isset($_COOKIE['orderID'])) {
            setcookie("orderID", "", time() - 3600, "/", "", true, true);
            setcookie("orderID", "", time() - 3600, "/", "", false, true);
            setcookie("orderID", "", time() - 3600, "", "", true, true);
            unset($_COOKIE['orderID']);
        }

        // Redirect to receipt page
        header("location: foodreciept.php?order=" . urlencode($saloon));
        exit();
    }
}
?>