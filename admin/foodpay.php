<?php
if (isset($_POST['pay'])) {
    $payments = $_POST['payment'] ?? [];
    $totalEntered = 0;

    foreach ($payments as $method => $data) {
        if (!empty($data['enabled']) && is_numeric($data['amount'])) {
            $totalEntered += (float) $data['amount'];
        }
    }
    // If user selected Credit, normalize payment array so existing processing can handle it
    $isCreditSelected = false;
    $creditAmount = 0.0;
    if (isset($_POST['payment']['credit'])) {
        $creditData = $_POST['payment']['credit'];
        if (isset($creditData['amount']) && is_numeric($creditData['amount'])) {
            $creditAmount = (float) $creditData['amount'];
        }
        // consider either enabled flag or a non-zero amount as selection
        if (!empty($creditData['enabled']) || $creditAmount > 0) {
            $isCreditSelected = true;
        }
    }

    if ($isCreditSelected) {
        // ensure other methods are zeroed out so we don't change their handling elsewhere
        $_POST['payment']['pos'] = ['enabled' => 0, 'amount' => '0'];
        $_POST['payment']['cash'] = ['enabled' => 0, 'amount' => '0'];
        $_POST['payment']['transfer'] = ['enabled' => 0, 'amount' => '0'];

        // preserve entered credit amount, or 0 if empty/invalid
        $_POST['payment']['credit']['amount'] = (string) $creditAmount;
        $_POST['payment']['credit']['enabled'] = 1;

        // rebuild $payments and $totalEntered from normalized POST
        $payments = $_POST['payment'] ?? [];
        $totalEntered = 0;
        foreach ($payments as $method => $data) {
            if (!empty($data['enabled']) && is_numeric($data['amount'])) {
                $totalEntered += (float) $data['amount'];
            }
        }
    }

    $creditOverrideRequested = isset($_POST['credit_override']) && $_POST['credit_override'] === '1';
    $adminCreditPassword = trim($_POST['admin_credit_password'] ?? '');
    $creditValidationError = '';

    if ($isCreditSelected && isset($_POST['customertype']) && $_POST['customertype'] === 'old' && !empty($_POST['customer'])) {
        $customerName = $_POST['customer'];
        $stmt = $con->prepare("SELECT credit_sales_eligibility FROM customers WHERE name = ? LIMIT 1");
        $stmt->bind_param("s", $customerName);
        $stmt->execute();
        $result = $stmt->get_result();
        $eligible = false;
        if ($row = $result->fetch_assoc()) {
            $eligible = in_array(strtolower(trim((string)$row['credit_sales_eligibility'])), ['1', 'yes', 'true', 'eligible'], true);
        }
        $stmt->close();

        if (!$eligible) {
            if ($creditOverrideRequested) {
                $stmt = $con->prepare("SELECT COUNT(*) AS valid_admin FROM admin WHERE password = ? AND status IN ('superadmin', 'subadmin')");
                $stmt->bind_param("s", $adminCreditPassword);
                $stmt->execute();
                $result = $stmt->get_result();
                $validAdmin = 0;
                if ($row = $result->fetch_assoc()) {
                    $validAdmin = (int)$row['valid_admin'];
                }
                $stmt->close();

                if ($validAdmin === 0) {
                    $creditValidationError = 'Invalid admin password. Credit override denied.';
                }
            } else {
                $creditValidationError = 'Selected customer is not eligible for credit sales. Use admin override to enable credit.';
            }
        }
    }

    if ($creditValidationError !== '') {
        echo "<script>alert('" . addslashes($creditValidationError) . "');</script>";
    } elseif ($totalEntered != $total_all) {
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
            $sql = "SELECT name, phone, email FROM customers WHERE unique_id = ? OR name = ? LIMIT 1";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ss", $customer_id, $customer_id);
            $stmt->execute();
            $sql2 = $stmt->get_result();
            if ($row = $sql2->fetch_assoc()) {
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

        // ---------------------------------------------------------------------
        // CREDIT-ONLY ORDER HOOK
        // If this checkout is a credit-only order, insert the credit record here
        // and then remove the order from the normal saloon_orders/refreshments tables.
        // This must happen before the stock update / receipt generation below.
        // Useful variables available:
        // - $saloon             : current order id
        // - $total_all          : grand total for the order
        // - $payments['credit'] : array with ['enabled'] and ['amount']
        // - $isCreditSelected   : boolean if credit was selected
        // Example:
        $creditAmount = isset($payments['credit']['amount']) ? (float)$payments['credit']['amount'] : 0;
        if ($isCreditSelected) {
            $creditCustomer = !empty($customer_id) && $customer_id !== 'nil' ? $customer_id : $customername;

            $stmtSelect = $con->prepare("SELECT itemid, item, unitprice, quantity, totalprice, item_category FROM refreshments WHERE orderid = ?");
            $stmtSelect->bind_param('s', $saloon);
            $stmtSelect->execute();
            $result = $stmtSelect->get_result();

            if ($result && $result->num_rows > 0) {
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                $totalRefreshmentPrice = 0.0;
                foreach ($rows as $row) {
                    $totalRefreshmentPrice += is_numeric($row['totalprice']) ? (float)$row['totalprice'] : 0.0;
                }

                $stmtInsert = $con->prepare("INSERT INTO credit_sales (orderid, itemid, item, unitprice, quantity, totalprice, item_category, customer, amount_paid, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $status = 'pending';
                $allocatedPaid = 0.0;
                $rowCount = count($rows);

                foreach ($rows as $index => $row) {
                    $itemid = $row['itemid'];
                    $item = $row['item'];
                    $unitprice = is_numeric($row['unitprice']) ? (float)$row['unitprice'] : 0.0;
                    $quantity = is_numeric($row['quantity']) ? (int)$row['quantity'] : 1;
                    $totalprice = is_numeric($row['totalprice']) ? (float)$row['totalprice'] : 0.0;
                    $item_category = $row['item_category'] ?? '';

                    $amountPaid = 0.0;
                    if ($creditAmount > 0 && $totalRefreshmentPrice > 0) {
                        if ($index === $rowCount - 1) {
                            $amountPaid = round($creditAmount - $allocatedPaid, 2);
                        } else {
                            $amountPaid = round(($creditAmount * $totalprice) / $totalRefreshmentPrice, 2);
                            $allocatedPaid += $amountPaid;
                        }
                    }

                    $stmtInsert->bind_param('sssdiissds', $saloon, $itemid, $item, $unitprice, $quantity, $totalprice, $item_category, $creditCustomer, $amountPaid, $status);
                    $stmtInsert->execute();
                }

                $stmtInsert->close();
            } else {
                $stmt = $con->prepare("INSERT INTO credit_sales (orderid, totalprice, amount_paid, status, itemid, item, unitprice, quantity, customer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $status = 'pending';
                $stmt->bind_param('sddsssdss', $saloon, $total_all, $creditAmount, $status, 'CREDIT', 'Credit Sale Payment', 0.0, 1, $creditCustomer);
                $stmt->execute();
                $stmt->close();
            }

            $stmtSelect->close();

            $stmt = $con->prepare("DELETE FROM refreshments WHERE orderid = ?");
            $stmt->bind_param('s', $saloon);
            $stmt->execute();
            $stmt->close();

            $stmt = $con->prepare("DELETE FROM saloon_orders WHERE id = ?");
            $stmt->bind_param('s', $saloon);
            $stmt->execute();
            $stmt->close();

            header('Location: foodreciept.php?order=' . urlencode($saloon) . '&credit=1');
            exit();
        }
        // ---------------------------------------------------------------------

        // Update stock in food_menu and refreshments
        $sqk = "SELECT itemid, quantity FROM refreshments WHERE orderid = ?";
        $stmt = $con->prepare($sqk);
        $stmt->bind_param("s", $saloon);
        $stmt->execute();
        $sqlp = $stmt->get_result();

        // while ($rowe = $sqlp->fetch_assoc()) {
        //     $food = $rowe['itemid'];
        //     $value = $rowe['quantity'];

        //     // Fetch current stock quantity
        //     $sql = "SELECT quantity FROM food_menu WHERE s = ?";
        //     $stmt_food = $con->prepare($sql);
        //     $stmt_food->bind_param("s", $food);
        //     $stmt_food->execute();
        //     $sql_food = $stmt_food->get_result();

        //     if ($sql_food->num_rows > 0) {
        //         $row_food = $sql_food->fetch_assoc();
        //         $originalvalue = $row_food['quantity'];
        //         $rem_value = $originalvalue - $value;

        //         // Update stock quantity in food_menu
        //         $stmt_update = $con->prepare("UPDATE food_menu SET quantity = ? WHERE s = ?");
        //         $stmt_update->bind_param("is", $rem_value, $food);
        //         $stmt_update->execute() or die('Could not connect: ' . mysqli_error($con));
        //         $stmt_update->close();

        //         // Update total_left and date in refreshments
        //         $stmt_refresh = $con->prepare("UPDATE refreshments SET total_left = ?, date = ? WHERE orderid = ? AND itemid = ?");
        //         $stmt_refresh->bind_param("issi", $rem_value, $datetime, $saloon, $food);
        //         $stmt_refresh->execute() or die('Could not connect: ' . mysqli_error($con));
        //         $stmt_refresh->close();

        //         // Log stock change
        //         $stmt_log = $con->prepare("INSERT INTO stock_log (id, action, value, date) VALUES (?, 'minus', ?, ?)");
        //         $stmt_log->bind_param("sis", $food, $value, $datetime);
        //         $stmt_log->execute() or die('Could not connect: ' . mysqli_error($con));
        //         $stmt_log->close();
        //     } else {
        //         error_log("Item with s='$food' not found in food_menu for orderid='$saloon'");
        //     }
        //     $stmt_food->close();
        // }
        // $stmt->close();


        $con->begin_transaction();

        try {

            $stmt = $con->prepare("
        SELECT itemid, quantity 
        FROM refreshments 
        WHERE orderid = ?
    ");

            $stmt->bind_param("s", $saloon);
            $stmt->execute();

            $items = $stmt->get_result();

            while ($row = $items->fetch_assoc()) {

                $food = $row['itemid'];
                $qty = (int)$row['quantity'];

                // CHECK IF ITEM IS SPECIAL
                $specialStmt = $con->prepare("
            SELECT special_item 
            FROM food_menu 
            WHERE s = ?
        ");

                $specialStmt->bind_param("s", $food);
                $specialStmt->execute();

                $specialResult = $specialStmt->get_result();
                $foodData = $specialResult->fetch_assoc();

                // SPECIAL ITEM
                if (
                    isset($foodData['special_item']) &&
                    $foodData['special_item'] == 'true'
                ) {

                    $ingredientStmt = $con->prepare("
                SELECT ingredient_id, quantity
                FROM special_items
                WHERE item_id = ?
            ");

                    $ingredientStmt->bind_param("s", $food);
                    $ingredientStmt->execute();

                    $ingredients = $ingredientStmt->get_result();

                    while ($ingredient = $ingredients->fetch_assoc()) {

                        $ingredientId = $ingredient['ingredient_id'];

                        // quantity required for ONE item
                        $ingredientQty = (int)$ingredient['quantity'];

                        // total quantity to remove
                        $removeQty = $ingredientQty * $qty;

                        // lock ingredient stock
                        $stockStmt = $con->prepare("
                    SELECT quantity
                    FROM food_menu
                    WHERE s = ?
                    FOR UPDATE
                ");

                        $stockStmt->bind_param("s", $ingredientId);
                        $stockStmt->execute();

                        $stockResult = $stockStmt->get_result();

                        if ($stockResult->num_rows > 0) {

                            $stock = (int)$stockResult
                                ->fetch_assoc()['quantity'];

                            $newQty = max(0, $stock - $removeQty);

                            // UPDATE INGREDIENT STOCK
                            $updateStmt = $con->prepare("
                        UPDATE food_menu
                        SET quantity = ?
                        WHERE s = ?
                    ");

                            $updateStmt->bind_param(
                                "is",
                                $newQty,
                                $ingredientId
                            );

                            $updateStmt->execute();

                            // STOCK LOG
                            $logStmt = $con->prepare("
                        INSERT INTO stock_log
                        (id, action, value, date)
                        VALUES (?, 'minus', ?, ?)
                    ");

                            $logStmt->bind_param(
                                "sis",
                                $ingredientId,
                                $removeQty,
                                $datetime
                            );

                            $logStmt->execute();
                        }
                    }
                } else {

                    // NORMAL ITEM STOCK UPDATE

                    $stmt2 = $con->prepare("
                SELECT quantity
                FROM food_menu
                WHERE s = ?
                FOR UPDATE
            ");

                    $stmt2->bind_param("s", $food);
                    $stmt2->execute();

                    $result = $stmt2->get_result();

                    if ($result->num_rows > 0) {

                        $stock = (int)$result
                            ->fetch_assoc()['quantity'];

                        $newQty = max(0, $stock - $qty);

                        $upd = $con->prepare("
                    UPDATE food_menu
                    SET quantity = ?
                    WHERE s = ?
                ");

                        $upd->bind_param("is", $newQty, $food);
                        $upd->execute();

                        $ref = $con->prepare("
                    UPDATE refreshments
                    SET total_left=?, date=?
                    WHERE orderid=? AND itemid=?
                ");

                        $ref->bind_param(
                            "issi",
                            $newQty,
                            $datetime,
                            $saloon,
                            $food
                        );

                        $ref->execute();

                        $log = $con->prepare("
                    INSERT INTO stock_log
                    (id, action, value, date)
                    VALUES (?, 'minus', ?, ?)
                ");

                        $log->bind_param(
                            "sis",
                            $food,
                            $qty,
                            $datetime
                        );

                        $log->execute();
                    }
                }
            }

            $con->commit();
        } catch (Exception $e) {

            $con->rollback();

            error_log(
                "Stock update failed: " .
                    $e->getMessage()
            );
        }


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
