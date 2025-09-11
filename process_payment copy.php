<?php
function processPayment($saloon, $reed, $total_all, $c_email, $username, $sitemail, $siteimg, $sitename, $date, $method, $con) {
    $html = "";
    $error = "";

    if ($reed != "cancelled") {
        // Check if payment_confirmed column exists
        $checkColumnSql = "SHOW COLUMNS FROM saloon_orders LIKE 'payment_confirmed'";
        $result = mysqli_query($con, $checkColumnSql);
        if (mysqli_num_rows($result) == 0) {
            $query = "ALTER TABLE saloon_orders ADD payment_confirmed TINYINT(1) NOT NULL DEFAULT 0";
            if (!mysqli_query($con, $query)) {
                error_log("Process Payment: Failed to add payment_confirmed column: " . mysqli_error($con));
            }
            $query = "UPDATE saloon_orders SET payment_confirmed = 1 WHERE method != 'Bank Transfer'";
            if (!mysqli_query($con, $query)) {
                error_log("Process Payment: Failed to update payment_confirmed: " . mysqli_error($con));
            }
        }

        // Check if bank_account_id column exists
        $checkColumnSql = "SHOW COLUMNS FROM saloon_orders LIKE 'bank_account_id'";
        $bankAccountIdExists = mysqli_num_rows($con->query($checkColumnSql)) > 0;

        // Fetch order details
        $sql = "SELECT pay_status, preorder, preorder_date, name, email, method" . ($bankAccountIdExists ? ", bank_account_id" : "") . " FROM saloon_orders WHERE id='$saloon'";
        $sql2 = mysqli_query($con, $sql);
        if (!$sql2 || !($row = mysqli_fetch_array($sql2))) {
            error_log("Process Payment: Order not found for id=$saloon");
            return ["error" => "Order not found", "html" => ""];
        }
        $status = $row["pay_status"];
        $preorder = $row["preorder"];
        $preorder_date = $row["preorder_date"];
        $username = $row["name"] ?? $username;
        $c_email = $row["email"] ?? $c_email;
        $method = $row["method"] ?? $method;
        $bank_account_id = $bankAccountIdExists ? ($row["bank_account_id"] ?? '') : '';

        if ($status != "paid") {
            // Update order status
            $pay_status = ($method == "Bank Transfer" || $method == "Bank Transfer,Giftcard") ? "pending" : "paid";
            $order_status = ($method == "Bank Transfer" || $method == "Bank Transfer,Giftcard") ? "pending" : "processed";
            $payment_confirmed = ($method == "Bank Transfer" || $method == "Bank Transfer,Giftcard") ? 0 : 1;

            $query = "UPDATE saloon_orders SET pay_status='$pay_status', status='$order_status', payment_confirmed='$payment_confirmed', card_amount='$total_all', method='$method'";
            if ($bankAccountIdExists && $bank_account_id) {
                $query .= ", bank_account_id='$bank_account_id'";
            }
            $query .= " WHERE id='$saloon'";
            if (!mysqli_query($con, $query)) {
                error_log("Process Payment: Update saloon_orders failed: " . mysqli_error($con) . " | Query: $query");
            }

            $query = "UPDATE refreshments SET status='$order_status' WHERE orderid='$saloon'";
            if (!mysqli_query($con, $query)) {
                error_log("Process Payment: Update refreshments failed: " . mysqli_error($con) . " | Query: $query");
            }

            // Clear refreshments for completed orders
            if ($pay_status == "paid" || $pay_status == "pending") {
                $query = "DELETE FROM refreshments WHERE orderid='$saloon'";
                if (!mysqli_query($con, $query)) {
                    error_log("Process Payment: Failed to clear refreshments: " . mysqli_error($con) . " | Query: $query");
                }
            }

            // Handle gift card if present
            $sql = "SELECT s, giftcardno, amount_deducted FROM giftcard_history WHERE orderid='$saloon' AND status='processing'";
            $result = mysqli_query($con, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $giftcard = $row['giftcardno'];
                    $amount_deducted = $row['amount_deducted'];
                    $thegifttransaction = $row['s'];
                    $query = "UPDATE giftcard_history SET status='processed' WHERE s='$thegifttransaction'";
                    if (!mysqli_query($con, $query)) {
                        error_log("Process Payment: Update giftcard_history failed: " . mysqli_error($con) . " | Query: $query");
                    }

                    // Update gift card balance
                    $sql = "SELECT amount FROM giftcard WHERE giftcardno='$giftcard'";
                    $result = mysqli_query($con, $sql);
                    if ($row = mysqli_fetch_array($result)) {
                        $giftcard_real_amount = $row['amount'];
                    }
                    $sql = "SELECT SUM(amount_deducted) AS total_shop FROM giftcard_history WHERE giftcardno='$giftcard' AND status='processed'";
                    $sql2 = mysqli_query($con, $sql);
                    $ron = mysqli_fetch_array($sql2);
                    $whole_shop = $ron['total_shop'];
                    $giftcard_amountleft = $giftcard_real_amount - $whole_shop;

                    $query = "UPDATE giftcard SET amount_left='$giftcard_amountleft' WHERE giftcardno='$giftcard'";
                    if (!mysqli_query($con, $query)) {
                        error_log("Process Payment: Update giftcard failed: " . mysqli_error($con) . " | Query: $query");
                    }
                    $query = "UPDATE saloon_orders SET gift_amount='$amount_deducted' WHERE id='$saloon'";
                    if (!mysqli_query($con, $query)) {
                        error_log("Process Payment: Update saloon_orders gift_amount failed: " . mysqli_error($con) . " | Query: $query");
                    }
                    $method = ($method == "Bank Transfer") ? "Bank Transfer,Giftcard" : "Card,Giftcard";
                    $query = "UPDATE saloon_orders SET method='$method' WHERE id='$saloon'";
                    if (!mysqli_query($con, $query)) {
                        error_log("Process Payment: Update saloon_orders method failed: " . mysqli_error($con) . " | Query: $query");
                    }
                }
            }

            // Update stock (only if not Bank Transfer)
            if ($method != "Bank Transfer" && $method != "Bank Transfer,Giftcard") {
                $datetime = date('Y-m-d H:i:s');
                $sql = "SELECT itemid, quantity FROM refreshments WHERE orderid='$saloon'";
                $sql2 = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_array($sql2)) {
                    $food = $row['itemid'];
                    $value = $row['quantity'];
                    $sql = "SELECT quantity FROM food_menu WHERE s='$food'";
                    $sql3 = mysqli_query($con, $sql);
                    if ($row = mysqli_fetch_array($sql3)) {
                        $originalvalue = $row['quantity'];
                        $rem_value = $originalvalue - $value;
                        $query = "UPDATE food_menu SET quantity='$rem_value' WHERE s='$food'";
                        if (!mysqli_query($con, $query)) {
                            error_log("Process Payment: Update food_menu failed: " . mysqli_error($con) . " | Query: $query");
                        }
                        $query = "INSERT INTO stock_log(id, action, value, date) VALUES ('$food', 'minus', '$value', '$datetime')";
                        if (!mysqli_query($con, $query)) {
                            error_log("Process Payment: Insert stock_log failed: " . mysqli_error($con) . " | Query: $query");
                        }
                    }
                }
            }

            // Build order summary HTML
            $sql = "SELECT item, quantity, totalprice FROM refreshments WHERE orderid='$saloon'";
            $sql2 = mysqli_query($con, $sql);
            if (mysqli_num_rows($sql2) > 0) {
                $name = [];
                $surname = [];
                $address = [];
                while ($row = mysqli_fetch_array($sql2)) {
                    $name[] = $row['item'];
                    $surname[] = $row['quantity'];
                    $address[] = $row['totalprice'];
                }
                $html = "<p><table border='1px' bordercolor='#000000' cellpadding='10' style='color:#fff;' width='500px'>
                         <tr style='border-bottom:#FFFFFF solid;'><td>Order ID</td><td style='color:#FFC700;' colspan='2'>$saloon</td></tr>
                         <tr><td style='color:#fff; text-align:center;'>Your Items</td><td>Quantity</td><td>Price</td></tr>";
                foreach ($name as $key => $value) {
                    $html .= "<tr>
                              <td style='color:#FFC700; font-size:14px; font-weight:500;'>{$name[$key]}</td>
                              <td style='color:#FFC700; font-size:14px; font-weight:500;'>{$surname[$key]}</td>
                              <td style='color:#FFC700; font-size:14px; font-weight:500;'>&#8358;{$address[$key]}</td>
                            </tr>";
                }
                $html .= "</table></p>";
            }

            // Add bank transfer details to customer email
            $bank_details_html = "";
            if (($method == "Bank Transfer" || $method == "Bank Transfer,Giftcard") && $bankAccountIdExists && $bank_account_id) {
                $sql = "SELECT bank_name, account_name, account_number FROM bank_accounts WHERE id='$bank_account_id'";
                $result = mysqli_query($con, $sql);
                if ($row = mysqli_fetch_array($result)) {
                    $bank_details_html = "<p style='color:#fff; font-size:13px;'>Bank Transfer Details:<br>
                                          Bank Name: {$row['bank_name']}<br>
                                          Account Name: {$row['account_name']}<br>
                                          Account Number: {$row['account_number']}<br>
                                          Please complete the bank transfer and await admin verification.</p>";
                }
            }

            // Send customer email
            if (!empty($c_email)) {
                $email_from = $sitemail;
                $email_to = $c_email;
                $email_subject = "Order Initiated Successfully - $sitename";
                $email_message = "
                    <center><div style='background-color:#000000; color:#fff !important; padding:10px 20px; width:500px;'>
                    <p style='text-align:left;'>
                    <img src='$siteimg' width='100px' height='60px;' style='margin-top:13px;'>
                    <font color='#FFFFFF' style='float:right; font-size:15px; padding-right:6px; text-align:right; margin-top:13px;'>Total Cost: &#8358;$total_all <br>$date</font></p>
                    <p style='color:#fff; font-size:13px;'>Hey there, $username, You have successfully " . ($method == "Bank Transfer" ? "initiated a bank transfer for" : "purchased") . " items from orishirishi at chbluxuryempire, attached below is your receipt details</p>
                    $html
                    $bank_details_html
                    <p style='color:#fff; font-size:13px;'>Thank you for your patronage and we hope to see you again soon!</p>
                    <br><br>
                    <p style='text-align:center;'><a href='chbluxuryempire.com' style='color:#FFC700;'>$sitename</a></p>
                    </div></center>";

                $header = "From: \"$sitename\" <$sitemail>\r\n";
                $header .= "Cc: $sitemail\r\n";
                $header .= "Reply-To: $sitemail\r\n";
                $header .= "MIME-Version: 1.0\r\n";
                $header .= "Content-type: text/html\r\n";

                if (!@mail($email_to, $email_subject, $email_message, $header)) {
                    $error = '<center><font color="red">Customer email cannot be sent due to server problems, please try again.</font></center>';
                    error_log("Process Payment: Failed to send customer email to $email_to");
                }
            }

            // Send admin email
            $bank_details_html = "";
            if (($method == "Bank Transfer" || $method == "Bank Transfer,Giftcard") && $bankAccountIdExists && $bank_account_id) {
                $sql = "SELECT bank_name, account_name, account_number FROM bank_accounts WHERE id='$bank_account_id'";
                $result = mysqli_query($con, $sql);
                if ($row = mysqli_fetch_array($result)) {
                    $bank_details_html = "<p style='color:#fff; font-size:13px;'>Bank Transfer Details:<br>
                                          Bank Name: {$row['bank_name']}<br>
                                          Account Name: {$row['account_name']}<br>
                                          Account Number: {$row['account_number']}<br>
                                          Please verify the payment in the admin dashboard.</p>";
                }
            }

            $email_from = $sitemail;
            $email_to = $sitemail;
            $email_subject = "New Orishirishi Order - $sitename";
            $email_message = "
                <div style='background-color:#000000; color:#fff; height:400px; padding:50px; width:500px;'>
                <p><img src='http://chbluxuryempire.com/assets/img/luxury/logo_luxury.png' width='100px' height='60px' /></p><br><br>
                <p style='color:#fff !important;'>Hello Dear Admin, There has been a new orishirishi order (Payment Method: $method). Login to your dashboard to view</p><br><br>
                $bank_details_html
                <p style='text-align:center; color:#fff;'>
                <a href='https://chbluxuryempire.com/admin' style='color:#FFC700;text-decoration:underline;'>ADMIN DASHBOARD</a>
                </p>
                </div>";

            $header = "From: \"$sitename\" <$sitemail>\r\n";
            $header .= "Cc: $sitemail\r\n";
            $header .= "Reply-To: $sitemail\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";

            if (!@mail($email_to, $email_subject, $email_message, $header)) {
                $error .= '<center><font color="red">Admin email cannot be sent due to server problems, please try again.</font></center>';
                error_log("Process Payment: Failed to send admin email to $email_to");
            }
        }
    }

    return ["html" => $html, "error" => $error];
}
?>