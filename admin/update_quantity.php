<?php
// Start output buffering to catch stray output
ob_start();
// Disable display errors to prevent JSON corruption
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log'); // Log errors to a file

include "header.php";

// Ensure no output before this point
$response = ['success' => false, 'error' => ''];

if (!isset($con) || !$con) {
    $response['error'] = 'Database connection failed: ' . mysqli_connect_error();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
    ob_end_flush();
    exit;
}

if (isset($_POST['item_id'], $_POST['quantity'], $_POST['order_id'])) {
    $item_id = $_POST['item_id'];
    $quantity = (int)$_POST['quantity'];
    $order_id = $_POST['order_id'];

    // Get item details
    $sql = "SELECT unitprice, itemid FROM refreshments WHERE s = ? AND orderid = ?";
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        $response['error'] = 'Prepare failed: ' . $con->error;
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        ob_end_flush();
        exit;
    }
    $stmt->bind_param("ss", $item_id, $order_id);
    if (!$stmt->execute()) {
        $response['error'] = 'Execute failed: ' . $stmt->error;
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        ob_end_flush();
        exit;
    }
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $unit_price = (float)$row['unitprice'];
        
        // Check available quantity
        $sql_check = "SELECT quantity FROM food_menu WHERE s = ?";
        $stmt_check = $con->prepare($sql_check);
        if (!$stmt_check) {
            $response['error'] = 'Prepare check failed: ' . $con->error;
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($response);
            ob_end_flush();
            exit;
        }
        $stmt_check->bind_param("s", $row['itemid']);
        if (!$stmt_check->execute()) {
            $response['error'] = 'Execute check failed: ' . $stmt_check->error;
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($response);
            ob_end_flush();
            exit;
        }
        $result_check = $stmt_check->get_result();
        $available = (int)$result_check->fetch_assoc()['quantity'];
        $stmt_check->close();

        if ($quantity <= $available && $quantity > 0) {
            $total_price = $quantity * $unit_price;
            
            // Update quantity
            $sql_update = "UPDATE refreshments SET quantity = ?, totalprice = ? WHERE s = ? AND orderid = ?";
            $stmt_update = $con->prepare($sql_update);
            if (!$stmt_update) {
                $response['error'] = 'Prepare update failed: ' . $con->error;
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($response);
                ob_end_flush();
                exit;
            }
            $stmt_update->bind_param("idss", $quantity, $total_price, $item_id, $order_id);
            if ($stmt_update->execute()) {
                $response['success'] = true;
                $response['unit_price'] = $unit_price;
            } else {
                $response['error'] = 'Update failed: ' . $stmt_update->error;
            }
            $stmt_update->close();
        } else {
            $response['error'] = "Requested quantity exceeds available stock ($available) or is invalid";
        }
    } else {
        $response['error'] = "Item not found in cart";
    }
    $stmt->close();
} else {
    $response['error'] = "Missing required POST parameters";
}

// Clear output buffer and send JSON response
ob_end_clean();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
exit;
?>