<?php include "header.php"; ?>

<?php
if (!isset($_GET['order'])) {
  echo "<div class='alert alert-danger'>No order specified.</div>";
  include "footer.php";
  exit;
}

$order_id = mysqli_real_escape_string($con, $_GET['order']);

// Handle price updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_price'])) {
  $item_id = (int) $_POST['item_id'];
  $new_unitprice = floatval($_POST['unitprice']);

  // Validate input
  if ($new_unitprice <= 0) {
    echo "<div class='alert alert-danger'>Unit price must be greater than 0.</div>";
  } else {
    // Start transaction
    mysqli_begin_transaction($con);
    try {
      // Update event_order_items
      $update_sql = "UPDATE event_order_items SET unitprice = ?, totalprice = (quantity * ?) WHERE id = ? AND itemid = 0";
      $update_stmt = mysqli_prepare($con, $update_sql);
      mysqli_stmt_bind_param($update_stmt, "ddi", $new_unitprice, $new_unitprice, $item_id);
      if (!mysqli_stmt_execute($update_stmt)) {
        throw new Exception("Error updating item price: " . mysqli_error($con));
      }
      $affected_rows = mysqli_stmt_affected_rows($update_stmt);
      mysqli_stmt_close($update_stmt);

      // Calculate new grand total
      $total_sql = "SELECT SUM(quantity * unitprice) AS grand_total FROM event_order_items WHERE orderid = ?";
      $total_stmt = mysqli_prepare($con, $total_sql);
      mysqli_stmt_bind_param($total_stmt, "i", $order_id);
      mysqli_stmt_execute($total_stmt);
      $total_result = mysqli_stmt_get_result($total_stmt);
      $grand_total = mysqli_fetch_assoc($total_result)['grand_total'] ?? 0;
      mysqli_stmt_close($total_stmt);

      // Update event_orders.total_amount using order_ref
      $order_ref_sql = "SELECT order_ref FROM event_orders WHERE id = ?";
      $order_ref_stmt = mysqli_prepare($con, $order_ref_sql);
      mysqli_stmt_bind_param($order_ref_stmt, "i", $order_id);
      mysqli_stmt_execute($order_ref_stmt);
      $order_ref_result = mysqli_stmt_get_result($order_ref_stmt);
      $order_ref = mysqli_fetch_assoc($order_ref_result)['order_ref'] ?? '';
      mysqli_stmt_close($order_ref_stmt);

      if (empty($order_ref)) {
        throw new Exception("Order reference not found.");
      }

      $update_order_sql = "UPDATE event_orders SET total_amount = ? WHERE order_ref = ?";
      $update_order_stmt = mysqli_prepare($con, $update_order_sql);
      mysqli_stmt_bind_param($update_order_stmt, "ds", $grand_total, $order_ref);
      if (!mysqli_stmt_execute($update_order_stmt)) {
        throw new Exception("Error updating order total: " . mysqli_error($con));
      }
      mysqli_stmt_close($update_order_stmt);

      // Commit transaction
      mysqli_commit($con);
      echo "<div class='alert alert-success'>Price updated successfully! Grand total updated to ₦" . number_format($grand_total) . "</div>";
    } catch (Exception $e) {
      mysqli_rollback($con);
      echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
    }
  }
}

// Fetch order details
$sql = "SELECT * FROM event_orders WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
  echo "<div class='alert alert-danger'>Order not found.</div>";
  mysqli_stmt_close($stmt);
  include "footer.php";
  exit;
}

$order = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$delivery_date = $order['delivery_date'] ?? '-';
$delivery_time = ($order['delivery_time'] && $order['delivery_time'] !== '00:00:00')
  ? date("g:i A", strtotime($order['delivery_time']))
  : '-';
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Event Order
    #<?php echo htmlspecialchars($order['order_ref'], ENT_QUOTES, 'UTF-8'); ?></h1>
  <a href="pending_event_orders.php" class="btn btn-secondary">← Back to Orders</a>
</div>



<div class="card mb-4">
  <div class="card-header">Customer Information</div>
  <div class="card-body">
    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone_number'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p><strong>Delivery date:</strong> <?php echo $delivery_date ?> <?php echo $delivery_time?>
    </p>
  </div>
</div>

<div class="card mb-4">
  <div class="card-header">Ordered Items</div>
  <div class="card-body">
    <?php
    $items_sql = "SELECT * FROM event_order_items WHERE orderid = ? ORDER BY id ASC";
    $items_stmt = mysqli_prepare($con, $items_sql);
    mysqli_stmt_bind_param($items_stmt, "i", $order_id);
    mysqli_stmt_execute($items_stmt);
    $items_result = mysqli_stmt_get_result($items_stmt);

    if ($items_result && mysqli_num_rows($items_result) > 0) {
      echo "<div class='table-responsive'>";
      echo "<table class='table table-bordered'>";
      echo "<thead><tr>
              <th>#</th>
              <th>Item</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Total</th>
            </tr></thead><tbody>";

      $counter = 1;
      $grand_total = 0;
      while ($item = mysqli_fetch_assoc($items_result)) {
        $item_total = $item['quantity'] * $item['unitprice'];
        $grand_total += $item_total;
        $is_editable = $item['itemid'] == 0;
        $price_input = $is_editable ?
          "<form method='post' style='display: inline;'>
             <input type='hidden' name='item_id' value='" . $item['id'] . "'>
             <input type='number' name='unitprice' value='" . $item['unitprice'] . "' step='0.01' min='0.01' style='width: 80px;' required>
             <button type='submit' name='update_price' class='btn btn-sm btn-primary'>Update</button>
           </form>" :
          number_format($item['unitprice']);
        echo "<tr>
                <td>{$counter}</td>
                <td>" . htmlspecialchars($item['item'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>₦{$price_input}</td>
                <td>₦" . number_format($item_total) . "</td>
              </tr>";
        $counter++;
      }

      echo "<tr>
              <td colspan='4' class='text-right'><strong>Grand Total:</strong></td>
              <td><strong>₦" . number_format($grand_total) . "</strong></td>
            </tr>";

      echo "</tbody></table></div>";
    } else {
      echo "<p>No items found for this order.</p>";
    }
    mysqli_stmt_close($items_stmt);
    ?>
  </div>
</div>

<div class="card mb-4">
  <div class="card-header">Order Information</div>
  <div class="card-body">
    <p><strong>Estimated Price:</strong> ₦<?php echo number_format($order['total_amount']); ?></p>
    <p><strong>Edited Price:</strong> ₦<?php echo number_format($order['edited_price']); ?></p>
    <p><strong>Status:</strong>
      <span class="badge 
        <?php echo ($order['status'] == 'completed') ? 'badge-success' :
          (($order['status'] == 'cancelled') ? 'badge-danger' : 'badge-warning'); ?>">
        <?php echo ucfirst($order['status']); ?>
      </span>
    </p>
    <p><strong>Created At:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
  </div>
</div>

<?php include "footer.php"; ?>