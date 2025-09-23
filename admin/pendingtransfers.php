<?php include "header.php";
// --- Ensure event_orders has amount_paid column ---
$checkColumn = mysqli_query($con, "SHOW COLUMNS FROM event_orders LIKE 'amount_paid'");
if (mysqli_num_rows($checkColumn) == 0) {
    mysqli_query($con, "ALTER TABLE event_orders ADD COLUMN amount_paid DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER total_amount");
}
?>

<?php
// Handle payment status update
if (isset($_GET['order']) && !empty($_GET['order']) && isset($_GET['type'])) {
    $order_id = mysqli_real_escape_string($con, $_GET['order']);
    $type = mysqli_real_escape_string($con, $_GET['type']);
    $transfer_id = mysqli_real_escape_string($con, $_GET['transfer']); // transfer row id

    // if ($type === "cart_items") {
    //     $sql = "UPDATE saloon_orders SET pay_status = 'paid' WHERE id = '$order_id'";
    // }

    if ($type === "cart_items") {
        $sql = "UPDATE saloon_orders SET pay_status = 'paid' WHERE id = '$order_id'";
        $datetime = date('Y-m-d H:i:s');
        $result = mysqli_query($con, "SELECT itemid, quantity FROM refreshments WHERE orderid = '$order_id'");
        while ($row = mysqli_fetch_assoc($result)) {
            $food = $row['itemid'];
            $value = $row['quantity'];
            $sql_food = mysqli_query($con, "SELECT quantity FROM food_menu WHERE s = '$food'");
            if (mysqli_num_rows($sql_food) > 0) {
                $row_food = mysqli_fetch_assoc($sql_food);
                $originalvalue = $row_food['quantity'];
                $rem_value = $originalvalue - $value;
                mysqli_query($con, "UPDATE food_menu SET quantity = '$rem_value' WHERE s = '$food'");
                mysqli_query($con, "UPDATE refreshments SET total_left = '$rem_value', date = '$datetime' WHERE orderid = '$order_id' AND itemid = '$food'");
                mysqli_query($con, "INSERT INTO stock_log (id, action, value, date) VALUES ('$food', 'minus', '$value', '$datetime')");
            } else {
                error_log("Item with s='$food' not found in food_menu for orderid='$order_id'");
            }
        }
    } elseif ($type === "event_order") {
        $transferRes = mysqli_query($con, "SELECT amount_paid FROM bank_transfers WHERE id='$transfer_id'");
        if ($transferRes && mysqli_num_rows($transferRes) > 0) {
            $transferRow = mysqli_fetch_assoc($transferRes);
            $paidNow = (float) $transferRow['amount_paid'];

            // Get order details
            $orderRes = mysqli_query($con, "SELECT total_amount, edited_price, amount_paid FROM event_orders WHERE id='$order_id'");
            if ($orderRes && mysqli_num_rows($orderRes) > 0) {
                $orderRow = mysqli_fetch_assoc($orderRes);

                $expected = ($orderRow['edited_price'] > 0) ? (float) $orderRow['edited_price'] : (float) $orderRow['total_amount'];
                $alreadyPaid = (float) $orderRow['amount_paid'];

                $newTotalPaid = $alreadyPaid + $paidNow;

                if ($newTotalPaid >= $expected) {
                    // Full payment reached
                    $sql = "UPDATE event_orders 
                        SET pay_status = 'paid', 
                            amount_paid = $newTotalPaid 
                        WHERE id = '$order_id'";
                } else {
                    // Still partial
                    $sql = "UPDATE event_orders 
                        SET pay_status = 'partly paid', 
                            amount_paid = $newTotalPaid 
                        WHERE id = '$order_id'";
                }
            }
        }
    } else {
        $sql = "";
    }

    if ($sql !== "" && mysqli_query($con, $sql)) {
        if (mysqli_affected_rows($con) > 0) {
            // Delete transfer record after successful update
            mysqli_query($con, "DELETE FROM bank_transfers WHERE id='$transfer_id'");
            echo "<script>alert('Payment status updated successfully!');</script>";
            header("Location: pendingtransfers.php");
        } else {
            echo "<script>alert('No rows updated. Order may not exist or already marked as paid.');</script>";
            header("Location: pendingtransfers.php");
        }
    } else {
        $error = mysqli_error($con);
        echo "<script>alert('Error updating payment status: " . addslashes($error) . "');</script>";
        header("Location: pendingtransfers.php");
    }
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Pending Transfers</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pending Transfers</li>
    </ol>
</div>

<!-- Pending Transfers Table -->
<div class="col-xl-12 col-lg-12 mb-4">
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Pending Transfers</h6>
        </div>

        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>SN</th>
                        <th>Transfer</th>
                        <th>Payment for</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>View</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM bank_transfers ORDER BY id DESC";
                    $sql2 = mysqli_query($con, $sql);
                    if (!$sql2) {
                        echo "<tr><td colspan='7'>Error fetching orders: " . htmlspecialchars(mysqli_error($con)) . "</td></tr>";
                    } else if (mysqli_num_rows($sql2) == 0) {
                        echo "<tr><td colspan='7'>No pending transfers found.</td></tr>";
                    } else {
                        $i = 1;
                        while ($row = mysqli_fetch_array($sql2)) {
                            $status = isset($row['status']) && $row['status'] !== '' ? $row['status'] : 'pending';

                            // Color coding
                            if ($status == "no") {
                                $bg = "badge-warning";
                                $status = "booking";
                            } else if ($status == "processing") {
                                $bg = "badge-primary";
                            } else if ($status == "cancelled") {
                                $bg = "badge-danger";
                            } else if ($status == "processed" || $status == "completed") {
                                $bg = "badge-success";
                            } else {
                                $bg = "badge-danger";
                            }

                            // Escape outputs
                            $transfer_id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
                            $id = htmlspecialchars($row['item_id'], ENT_QUOTES, 'UTF-8');
                            $amount_paid = isset($row['amount_paid']) ? htmlspecialchars($row['amount_paid'], ENT_QUOTES, 'UTF-8') : 0;
                            $total_amount = htmlspecialchars($row['amount'], ENT_QUOTES, 'UTF-8');
                            if ($amount_paid > 0) {
                                $total_amount = $amount_paid;
                            }
                            $status = htmlspecialchars($status, ENT_QUOTES, 'UTF-8');
                            $bg = htmlspecialchars($bg, ENT_QUOTES, 'UTF-8');
                            $fileUrl = htmlspecialchars($row['fileUrl'], ENT_QUOTES, 'UTF-8');
                            $paymentFor = htmlspecialchars($row['payment_for'], ENT_QUOTES, 'UTF-8');
                            $disabled = $isAdmin ? '' : 'disabled';
                            if ($paymentFor === "event_order") {
                                $viewUrl = "view_event_order.php?order=" . $id;
                            } else {
                                $viewUrl = "viewbooking.php?order=" . $id;
                            }

                            echo "
                <tr>
                  <td>" . $i++ . "</td>
                  <td><img src='" . $fileUrl . "' class='file_url' style='cursor:pointer; width:60px;' 
                        onclick=\"openModal('$fileUrl','$total_amount','$id','$paymentFor','$transfer_id','$amount_paid')\"></td>
                  <td>" . $paymentFor . "</td>
                  <td>&#8358; " . $total_amount . "</td>
                  <td><span class='badge " . $bg . "' style='text-transform:capitalize;'>" . $status . "</span></td>
                 <td><a href='" . $viewUrl . "' class='btn btn-sm btn-primary'>View Order Details</a></td>

                  <td><button class='btn btn-sm btn-success' $disabled onclick='confirmPayment(\"$id\",\"$paymentFor\",\"$transfer_id\",\"$amount_paid\")'>Mark as Paid</button></td>
                  
                </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer"></div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid mb-3" style="max-height:400px;">
                <h5>Amount: ₦<span id="modalAmount"></span></h5>
                <button id="modalConfirmBtn" class="btn btn-success mt-3" <?php echo !$isAdmin ? 'disabled' : ''; ?>>
                    Mark as Paid
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmPayment(orderId, type, transferId, amountPaid) {
        if (confirm("Are you sure you want to mark this order as paid?")) {
            window.location.href = 'pendingtransfers.php?order=' + encodeURIComponent(orderId) + '&type=' + encodeURIComponent(type) + '&transfer=' + encodeURIComponent(transferId) + '&amount_paid=' + encodeURIComponent(amountPaid);
        }
    }

    function openModal(imageUrl, amount, orderId, type, transferId, amountPaid) {
        document.getElementById("modalImage").src = imageUrl;
        document.getElementById("modalAmount").textContent = amount;
        document.getElementById("modalConfirmBtn").onclick = function () {
            confirmPayment(orderId, type, transferId, amountPaid);
        };
        $('#transferModal').modal('show');
    }
</script>

<?php include "footer.php"; ?>