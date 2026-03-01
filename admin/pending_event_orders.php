<?php
include "header.php";
include '../mailer.php';
?>

<?php

if (!empty($_SESSION['success_message'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
    unset($_SESSION['success_message']); // Clear after showing
}

// Handle edit price
if (isset($_POST['edit_price_order_id']) && isset($_POST['new_price'])) {
    $order_id = mysqli_real_escape_string($con, $_POST['edit_price_order_id']);
    $new_price = (float) $_POST['new_price'];
    $sql = "UPDATE event_orders SET edited_price='$new_price' WHERE id='$order_id'";
    if (mysqli_query($con, $sql)) {
        $_SESSION['success_message'] = "Price updated successfully";
        header("Location: pending_event_orders.php");
    } else {
        echo "<script>alert('Error updating price: " . mysqli_error($con) . "');</script>";
    }
}

// Handle delete (admin only)
if (isset($_GET['delete']) && !empty($_GET['delete']) && $isAdmin) {
    $order_id = mysqli_real_escape_string($con, $_GET['delete']);
    $sql = "DELETE FROM event_orders WHERE id='$order_id'";
    if (mysqli_query($con, $sql)) {
        $_SESSION['success_message'] = "Order deleted successfully";
        header("Location: pending_event_orders.php");
    } else {
        echo "<script>alert('Error deleting order: " . mysqli_error($con) . "');</script>";
    }
}

// Handle complete order
if (isset($_GET['complete']) && !empty($_GET['complete'])) {
    $order_id = mysqli_real_escape_string($con, $_GET['complete']);
    $sql = "UPDATE event_orders SET status='completed' WHERE id='$order_id'";
    if (mysqli_query($con, $sql)) {
        $_SESSION['success_message'] = "Order marked as completed";
        header("Location: pending_event_orders.php");
    } else {
        echo "<script>alert('Error completing order: " . mysqli_error($con) . "');</script>";
    }
}

if (isset($_GET['order_prepared']) && !empty($_GET['order_prepared'])) {
    $order_id = mysqli_real_escape_string($con, $_GET['order_prepared']);
    $sql = "UPDATE event_orders SET isPrepared=1 WHERE id='$order_id'";

    $to = $_GET['email'] ?? '';
    $subject = "email subject";
    $message = "email message";

    if (sendEmail($to, $subject, $message)) {
     
    }
    if (mysqli_query($con, $sql)) {
        $_SESSION['success_message'] = 'Order marked as prepared.';
        header("Location: pending_event_orders.php");
    } else {
        echo "<script>alert('Error completing order: " . mysqli_error($con) . "');</script>";
    }
}

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Event Orders</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Event Orders</li>
    </ol>
</div>

<!-- Floating Add Button -->
<a href="admin_event_orders.php" class="btn btn-primary rounded-circle"
    style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; font-size: 24px; z-index: 10;">
    <i class="fas fa-plus"></i>
</a>

<!-- Add Bank Account Modal -->

<div class="col-xl-12 col-lg-12 mb-4">
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Event Orders</h6>
        </div>

        <div class="table-responsive">
            <table class="table align-items-center table-flush py-5">
                <thead class="thead-light">
                    <tr>
                        <th>SN</th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Delivery date</th>
                        <th>Estimated Price</th>
                        <th>Discounted Price</th>
                        <th>Amount paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM event_orders ORDER BY id DESC";
                    $result = mysqli_query($con, $sql);
                    $i = 1;

                    while ($row = mysqli_fetch_array($result)) {
                        $id = $row['id'];
                        $email = $row['email'];
                        $isPrepared = $row['isPrepared'] ?? 0;
                        $ref = $row['order_ref'];
                        $status = isset($row['status']) && $row['status'] !== '' ? $row['status'] : 'pending';
                        $pay_status = isset($row['pay_status']) && $row['pay_status'] === 'paid' || $row['pay_status'] === 'partly paid' ? $row['pay_status'] : 'unpaid';
                        $pay_bg = $pay_status == 'paid' ? 'badge-success' : 'badge-danger';
                        $total_amount = isset($row['edited_price']) && $row['edited_price'] > 0 ? $row['edited_price'] : $row['total_amount'];
                        $amount_paid = $row['amount_paid'];
                        $delivery_date = $row['delivery_date'] ?? '-';
                        $delivery_time = ($row['delivery_time'] && $row['delivery_time'] !== '00:00:00')
                            ? date("g:i A", strtotime($row['delivery_time']))
                            : '-';
                        $balance = $total_amount - $amount_paid;
                        if ($status == "completed") {
                            $bg = "badge-success";
                        } else if ($status == "cancelled") {
                            $bg = "badge-danger";
                        } else {
                            $bg = "badge-warning";
                        }

                        echo "<tr>
                    <td>" . $i++ . "</td>
                    <td>" . $ref . "</td>
                    <td>" . htmlspecialchars($row['customer_name']) . "</td>
                    <td>
                    <div class='nowrap'>" . $delivery_date . "</div>
                    <div class='nowrap'>" . $delivery_time . "</div>
                    </td>
                    <td>&#8358; " . number_format($row['total_amount']) . "</td>
                    <td>&#8358; " . number_format($row['edited_price']) . "</td>
                    <td>&#8358; " . number_format($amount_paid) . "</td>
                    <td>&#8358; " . number_format($balance) . "</td>
                    <td>
                    <span class='badge $bg' style='text-transform:capitalize;'>$status</span>
                    <br>
                    <span class='badge $pay_bg' style='text-transform:capitalize;'>$pay_status</span>

                    </td>
                    <td>
                      <div class='dropdown'>
                        <button class='btn btn-sm btn-primary dropdown-toggle' type='button' data-toggle='dropdown'>
                          Actions
                        </button>
                        <div class='dropdown-menu'>
                        <a class='dropdown-item' href='#' onclick='openGenerateLinkModal(\"$ref\")'>Generate Link</a>
                          <a class='dropdown-item' href='#' onclick='openEditPriceModal(\"$id\")'>Edit Price</a>
                         <a class='dropdown-item' href='view_event_order.php?order=$id' onclick='viewOrder(\"$id\")'>View Order</a>
                         
                         <a class='dropdown-item' href='event_order_receipt.php?order=" . htmlspecialchars($ref, ENT_QUOTES, 'UTF-8') . "' target='_blank'>Print Receipt</a>";


                        if ($isPrepared == 0) {
                            $url = "pending_event_orders.php?order_prepared=" . urlencode($id) . "&email=" . urlencode($email);
                            echo "<a class='dropdown-item' href='" . htmlspecialchars($url) . "' onclick='return confirm(\"Mark this order as prepared?\")'>Mark as prepared</a>";
                        }

                        if ($status !== "completed" && $pay_status === "paid" && $isPrepared == 1) {
                            echo "<a class='dropdown-item text-success' 
                             href='pending_event_orders.php?complete=$id' 
                             onclick='return confirm(\"Mark this order as completed?\")'>
                             Complete Order
                          </a>";
                        }

                        if ($isAdmin) {
                            echo "<a class='dropdown-item text-danger' href='pending_event_orders.php?delete=$id' onclick='return confirm(\"Delete this order?\")'>Delete Order</a>";
                        }
                        echo "    </div>
                      </div>
                    </td>
                  </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Price Modal -->
<div class="modal fade" id="editPriceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Price</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="edit_price_order_id" id="edit_price_order_id">
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" step="0.01" class="form-control" name="new_price" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- View Order Modal -->
<div class="modal fade" id="viewOrderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <!-- AJAX content will load here -->
            </div>
        </div>
    </div>
</div>


<!-- Generate Link Modal -->
<div class="modal fade" id="generateLinkModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Link</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Share this link with the customer so they can complete payment:</p>
                <div class="input-group">
                    <input type="text" id="paymentLinkInput" class="form-control" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" onclick="copyPaymentLink()">Copy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<script>
    function openEditPriceModal(orderId) {
        document.getElementById("edit_price_order_id").value = orderId;
        $('#editPriceModal').modal('show');
    }

    function viewOrder(orderId) {
        fetch("view_event_order.php?order=" + orderId)
            .then(res => res.text())
            .then(data => {
                document.getElementById("orderDetailsContent").innerHTML = data;
                $('#viewOrderModal').modal('show');
            });
    }

    function openGenerateLinkModal(orderRef) {
        let link = window.location.origin + "/pay_for_event.php?order=" + orderRef;
        document.getElementById("paymentLinkInput").value = link;
        $('#generateLinkModal').modal('show');
    }

    function copyPaymentLink() {
        let copyText = document.getElementById("paymentLinkInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // for mobile
        document.execCommand("copy");
        alert("Link copied: " + copyText.value);
    }

</script>

<?php include "footer.php"; ?>