<?php

// Validate order ID
$safeOrderId = !empty($saloon) ? mysqli_real_escape_string($con, $saloon) : '';

// Preload cart items from session or database
if (!isset($_SESSION['cartItems'][$safeOrderId]) && !empty($safeOrderId)) {
    $cartRes = mysqli_query($con, "SELECT itemid, quantity, preorder FROM refreshments WHERE orderid='$safeOrderId' AND status='processing'");
    while ($c = mysqli_fetch_assoc($cartRes)) {
        $_SESSION['cartItems'][$safeOrderId][(int) $c['itemid']] = [
            'quantity' => (int) $c['quantity'],
            'preorder' => (int) $c['preorder']
        ];
    }
}
$cartItems = $_SESSION['cartItems'][$safeOrderId] ?? [];

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['updateCart'])) {
        foreach ($_POST['qty'] as $id => $newQty) {
            $id = (int) $id;
            $newQty = max(1, (int) $newQty);
            $priceRes = mysqli_query($con, "SELECT price FROM food_menu WHERE s='$id'");
            if ($row = mysqli_fetch_assoc($priceRes)) {
                $itemPrice = (float) $row['price'];
                $totalValue = $newQty * $itemPrice;
                $query = "UPDATE refreshments SET quantity='$newQty', totalprice='$totalValue', unitprice='$itemPrice' WHERE orderid='$safeOrderId' AND itemid='$id' AND status='processing'";
                if (!mysqli_query($con, $query)) {
                    error_log("Update cart failed: " . mysqli_error($con) . " | Query: $query");
                }
                $_SESSION['cartItems'][$safeOrderId][$id]['quantity'] = $newQty;
            }
        }
        header("Location: foodpage.php");
        exit;
    }

    if (isset($_POST['deleteItem'])) {
        $deleteIds = array_keys($_POST['deleteItem']);
        foreach ($deleteIds as $id) {
            $id = (int) $id;
            $query = "DELETE FROM refreshments WHERE orderid='$safeOrderId' AND itemid='$id' AND status='processing'";
            if (!mysqli_query($con, $query)) {
                error_log("Delete cart failed: " . mysqli_error($con) . " | Query: $query");
            }
            unset($_SESSION['cartItems'][$safeOrderId][$id]);
        }
        header("Location: foodpage.php");
        exit;
    }

    if (isset($_POST['addtocart'])) {
        $itemid = (int) ($_POST['food'] ?? 0);
        $qty = max(1, (int) ($_POST['value'] ?? 1));
        if ($itemid === 0) {
            error_log("Add to cart error: Invalid itemid");
            exit("Invalid item ID");
        }
        $res = mysqli_query($con, "SELECT item, price FROM food_menu WHERE s='$itemid'");
        if ($row = mysqli_fetch_assoc($res)) {
            $itemName = $row['item'];
            $itemPrice = (float) $row['price'];
            $check = mysqli_query($con, "SELECT quantity FROM refreshments WHERE orderid='$safeOrderId' AND itemid='$itemid' AND status='processing'");
            if ($exist = mysqli_fetch_assoc($check)) {
                $newQty = $exist['quantity'] + $qty;
                $totalValue = $newQty * $itemPrice;
                $query = "UPDATE refreshments SET quantity='$newQty', unitprice='$itemPrice', totalprice='$totalValue' WHERE orderid='$safeOrderId' AND itemid='$itemid' AND status='processing'";
                if (!mysqli_query($con, $query)) {
                    error_log("Update cart failed: " . mysqli_error($con) . " | Query: $query");
                }
                $_SESSION['cartItems'][$safeOrderId][$itemid]['quantity'] = $newQty;
            } else {
                $totalValue = $qty * $itemPrice;
                $query = "INSERT INTO refreshments(orderid,itemid,item,unitprice,quantity,totalprice,status) VALUES ('$safeOrderId','$itemid','$itemName','$itemPrice','$qty','$totalValue','processing')";
                if (!mysqli_query($con, $query)) {
                    error_log("Insert cart failed: " . mysqli_error($con) . " | Query: $query");
                }
                $_SESSION['cartItems'][$safeOrderId][$itemid] = ['quantity' => $qty, 'preorder' => 0];
            }
        } else {
            error_log("Add to cart error: Item not found for itemid=$itemid");
        }
        header("Location: foodpage.php");
        exit;
    }

    if (isset($_POST['preorder'])) {
        $itemid = (int) ($_POST['food'] ?? 0);
        $qty = max(1, (int) ($_POST['value'] ?? 1));
        $preorder_date = date('Y-m-d');
        if (empty($safeOrderId) || $itemid === 0) {
            error_log("Preorder error: Invalid orderId=$safeOrderId or itemid=$itemid");
            exit("Invalid request");
        }
        $res = mysqli_query($con, "SELECT item, price FROM food_menu WHERE s='$itemid'");
        if ($row = mysqli_fetch_assoc($res)) {
            $itemName = $row['item'];
            $itemPrice = (float) $row['price'];
            $check = mysqli_query($con, "SELECT quantity FROM refreshments WHERE orderid='$safeOrderId' AND itemid='$itemid' AND status='processing'");
            if ($exist = mysqli_fetch_assoc($check)) {
                $newQty = $exist['quantity'] + $qty;
                $totalValue = $newQty * $itemPrice;
                $query = "UPDATE refreshments SET quantity='$newQty', unitprice='$itemPrice', totalprice='$totalValue', preorder=1, preorder_date='$preorder_date' WHERE orderid='$safeOrderId' AND itemid='$itemid' AND status='processing'";
                if (!mysqli_query($con, $query)) {
                    error_log("Update preorder failed: " . mysqli_error($con) . " | Query: $query");
                }
                $_SESSION['cartItems'][$safeOrderId][$itemid] = ['quantity' => $newQty, 'preorder' => 1];
            } else {
                $totalValue = $qty * $itemPrice;
                $query = "INSERT INTO refreshments(orderid,itemid,item,unitprice,quantity,totalprice,status,preorder,preorder_date) VALUES ('$safeOrderId','$itemid','$itemName','$itemPrice','$qty','$totalValue','processing',1,'$preorder_date')";
                if (!mysqli_query($con, $query)) {
                    error_log("Insert preorder failed: " . mysqli_error($con) . " | Query: $query");
                }
                $_SESSION['cartItems'][$safeOrderId][$itemid] = ['quantity' => $qty, 'preorder' => 1];
            }
        } else {
            error_log("Preorder error: Item not found for itemid=$itemid");
        }
        header("Location: foodpage.php");
        exit;
    }
}