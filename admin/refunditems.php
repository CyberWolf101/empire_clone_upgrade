<?php include "header.php"; ?>

<?php
// Handle refund processing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['refund_selected']) || isset($_POST['refund_all']))) {
  $order_id = mysqli_real_escape_string($con, $_POST['order_id']);
  $admin_password = $_POST['admin_password'] ?? '';
  $items_to_refund = isset($_POST['items']) ? $_POST['items'] : [];
  $refund_quantities = isset($_POST['refund_quantity']) ? $_POST['refund_quantity'] : [];
  $items_to_restock = isset($_POST['restock']) ? $_POST['restock'] : [];
  $refund_all = isset($_POST['refund_all']);
  $statusMsg = '';

  // Validate admin password
  $sql = "SELECT COUNT(*) AS valid_admin FROM admin WHERE password = ? AND status IN ('superadmin', 'subadmin')";
  $stmt = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($stmt, "s", $admin_password);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $valid_admin = mysqli_fetch_assoc($result)['valid_admin'];
  mysqli_stmt_close($stmt);

  if ($valid_admin == 0) {
    $statusMsg = "Invalid admin password. Only superadmin or subadmin can authorize refunds.";
    header("Location: refunditems.php?order=" . urlencode($order_id) . "&error=" . urlencode($statusMsg));
    exit;
  }

  // Start transaction
  mysqli_begin_transaction($con);

  try {
    // Fetch all items if refund_all is triggered
    if ($refund_all) {
      $sql = "SELECT item, quantity FROM refreshments WHERE orderid = ? AND status = 'processed'";
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt, "s", $order_id);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $items_to_refund = [];
      $refund_quantities = [];
      while ($row = mysqli_fetch_assoc($result)) {
        $items_to_refund[] = $row['item'];
        $refund_quantities[$row['item']] = $row['quantity']; // Refund full quantity
        if (isset($_POST['restock_all'])) {
          $items_to_restock[] = $row['item'];
        }
      }
      mysqli_stmt_close($stmt);
    }

    if (empty($items_to_refund)) {
      throw new Exception("No items selected for refund.");
    }

    // Process refunds
    $update_sql = "UPDATE refreshments SET quantity = ?, totalprice = ?, status = ? WHERE orderid = ? AND item = ? AND status = 'processed'";
    $update_stmt = mysqli_prepare($con, $update_sql);
    foreach ($items_to_refund as $item) {
      $refund_qty = isset($refund_quantities[$item]) ? (int)$refund_quantities[$item] : 0;
      // Fetch current item details
      $sql = "SELECT quantity, unitprice FROM refreshments WHERE orderid = ? AND item = ? AND status = 'processed'";
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt, "ss", $order_id, $item);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $row = mysqli_fetch_assoc($result);
      $current_quantity = $row['quantity'] ?? 0;
      $unitprice = $row['unitprice'] ?? 0;
      mysqli_stmt_close($stmt);

      if ($refund_qty <= 0 || $refund_qty > $current_quantity) {
        throw new Exception("Invalid refund quantity for item '$item'. Must be between 1 and $current_quantity.");
      }

      // Calculate new quantity and totalprice
      $new_quantity = $current_quantity - $refund_qty;
      $new_totalprice = $new_quantity * $unitprice;
      $new_status = $new_quantity == 0 ? 'refunded' : 'processed';

      // Update refreshments
      mysqli_stmt_bind_param($update_stmt, "iisss", $new_quantity, $new_totalprice, $new_status, $order_id, $item);
      if (!mysqli_stmt_execute($update_stmt)) {
        throw new Exception("Failed to update refreshments: " . mysqli_error($con));
      }

      // Handle restocking
      if (in_array($item, $items_to_restock) && $refund_qty > 0) {
        // Get current food_menu quantity
        $sql = "SELECT quantity FROM food_menu WHERE item = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $item);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $prev_quantity = mysqli_fetch_assoc($result)['quantity'] ?? 0;
        mysqli_stmt_close($stmt);

        // Update food_menu quantity
        $new_food_quantity = $prev_quantity + $refund_qty;
        $sql = "UPDATE food_menu SET quantity = ? WHERE item = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "is", $new_food_quantity, $item);
        if (!mysqli_stmt_execute($stmt)) {
          throw new Exception("Failed to update food_menu: " . mysqli_error($con));
        }
        mysqli_stmt_close($stmt);

        // Insert into stock_transactions
        $action = 'refund';
        $datetime = date('Y-m-d H:i:s');
        $sql = "INSERT INTO stock_transactions (item, quantity, action, date, total_left) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sissi", $item, $refund_qty, $action, $datetime, $new_food_quantity);
        if (!mysqli_stmt_execute($stmt)) {
          throw new Exception("Failed to log stock transaction: " . mysqli_error($con));
        }
        mysqli_stmt_close($stmt);
      }
    }
    mysqli_stmt_close($update_stmt);

    // Check if all items are refunded
    $sql = "SELECT COUNT(*) AS remaining_count FROM refreshments WHERE orderid = ? AND status = 'processed' AND quantity > 0";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $order_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $remaining_count = mysqli_fetch_assoc($result)['remaining_count'];
    mysqli_stmt_close($stmt);

    // Update saloon_orders status
    $new_status = $remaining_count > 0 ? 'partly-refunded' : 'refunded';
    $sql = "UPDATE saloon_orders SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $new_status, $order_id);
    if (!mysqli_stmt_execute($stmt)) {
      throw new Exception("Failed to update saloon_orders status: " . mysqli_error($con));
    }
    mysqli_stmt_close($stmt);

    // Commit transaction
    mysqli_commit($con);
    $statusMsg = "Items successfully refunded! Order status updated to '$new_status'.";
    header("Location: refunditems.php?order=" . urlencode($order_id) . "&success=" . urlencode($statusMsg));
    exit;
  } catch (Exception $e) {
    mysqli_rollback($con);
    $statusMsg = "Error processing refund: " . $e->getMessage();
    header("Location: refunditems.php?order=" . urlencode($order_id) . "&error=" . urlencode($statusMsg));
    exit;
  }
}

// Check if order ID is provided
if (!isset($_GET['order']) || empty($_GET['order'])) {
  echo "<p class='text-danger mt-4'>No order ID provided.</p>";
  include "footer.php";
  exit;
}

$order_id = mysqli_real_escape_string($con, $_GET['order']);

// Verify if order exists in saloon_orders
$order_query = "SELECT id, name FROM saloon_orders WHERE id = ? AND section='refreshments' AND (pay_status='pending' OR pay_status='complete' OR pay_status='paid')";
$order_stmt = mysqli_prepare($con, $order_query);
mysqli_stmt_bind_param($order_stmt, "s", $order_id);
mysqli_stmt_execute($order_stmt);
$order_result = mysqli_stmt_get_result($order_stmt);
if (mysqli_num_rows($order_result) == 0) {
  echo "<p class='text-danger mt-4'>Invalid order ID or order not found.</p>";
  mysqli_stmt_close($order_stmt);
  include "footer.php";
  exit;
}
$order = mysqli_fetch_assoc($order_result);
$customer_name = htmlspecialchars($order['name'], ENT_QUOTES, 'UTF-8');
mysqli_stmt_close($order_stmt);

// Display success/error messages
if (isset($_GET['success'])) {
  echo "<div class='alert alert-success'>" . htmlspecialchars($_GET['success'], ENT_QUOTES, 'UTF-8') . "</div>";
}
if (isset($_GET['error'])) {
  echo "<div class='alert alert-danger'>" . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') . "</div>";
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Refund Items for Order #<?php echo htmlspecialchars($order_id, ENT_QUOTES, 'UTF-8'); ?></h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item"><a href="onlineorders.php">Online Orders</a></li>
    <li class="breadcrumb-item active" aria-current="page">Refund Items</li>
  </ol>
</div>

<div class="col-xl-12 col-lg-12 mb-4">
  <div class="card">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Items for Order #<?php echo htmlspecialchars($order_id, ENT_QUOTES, 'UTF-8'); ?> (Customer: <?php echo $customer_name; ?>)</h6>
    </div>

    <form method="post" onsubmit="return validateForm();">
      <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id, ENT_QUOTES, 'UTF-8'); ?>">
      <div class="form-group px-3 pt-3">
        <label for="admin_password">Administrator Password</label>
        <input type="password" name="admin_password" id="admin_password" class="form-control" style="max-width: 300px;" required>
      </div>
      <div class="table-responsive">
        <table class="table align-items-center table-flush">
          <thead class="thead-light">
            <tr>
              <th><span class="small">Mark item</span><input type="checkbox" id="select-all-items"></th>
              <th>Restock Item</th>
              <th>Item</th>
              <th>Quantity</th>
              <th>Refund Quantity</th>
              <th>Total Price</th>
              <!-- <th>Total Left</th> -->
            </tr>
          </thead>
          <tbody>
            <?php
            // Fetch items from refreshments
            $sql = "SELECT item, quantity, totalprice, total_left, status, unitprice FROM refreshments WHERE orderid = ? AND status = 'processed'";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "s", $order_id);
            mysqli_stmt_execute($stmt);
            $resultset = mysqli_stmt_get_result($stmt) or die("Database error: " . mysqli_error($con));

            if (mysqli_num_rows($resultset) > 0) {
              $i = 1;
              while ($row = mysqli_fetch_assoc($resultset)) {
                $item = htmlspecialchars($row['item'], ENT_QUOTES, 'UTF-8');
                $quantity = htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8');
                echo "
              <tr>
                <td><input type='checkbox' name='items[]' value='$item' class='item-checkbox'></td>
                <td><input type='checkbox' name='restock[]' value='$item' class='restock-checkbox'></td>

                <td>$item</td>
                <td>$quantity</td>
                <td><input type='number' name='refund_quantity[$item]' min='0' max='$quantity' value='1' class='form-control refund-quantity' style='width: 100px;'></td>
                <td>&#8358;" . htmlspecialchars($row['totalprice'], ENT_QUOTES, 'UTF-8') . "</td>
              </tr>";
              }
            } else {
              echo "<tr><td colspan='8' class='text-center'>No items found for this order.</td></tr>";
            }
            mysqli_stmt_close($stmt);
            ?>
          </tbody>
        </table>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-secondary btn-sm" id="mark-all-restock">Mark All for Restock</button>
        <button type="submit" name="refund_selected" class="btn btn-primary btn-sm">Refund Selected</button>
        <button type="submit" name="refund_all" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to refund all items?');">Refund All</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Select all items checkbox
  document.getElementById('select-all-items').addEventListener('change', function () {
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
      checkbox.checked = this.checked;
    });
    document.querySelectorAll('.refund-quantity').forEach(input => {
      input.value = this.checked ? input.max : 1; // Set to max for selected, 1 for unselected
    });
  });

  // Mark all for restock button
  document.getElementById('mark-all-restock').addEventListener('click', function () {
    document.querySelectorAll('.restock-checkbox').forEach(checkbox => {
      checkbox.checked = true;
    });
  });

  // Validate form before submission
  function validateForm() {
    const password = document.getElementById('admin_password').value;
    if (!password) {
      alert('Please enter an admin password.');
      return false;
    }
    const itemsChecked = document.querySelectorAll('.item-checkbox:checked').length;
    const isRefundSelected = document.querySelector('button[name="refund_selected"]') === document.activeElement;
    if (isRefundSelected && itemsChecked === 0) {
      alert('Please select at least one item to refund.');
      return false;
    }
    if (isRefundSelected) {
      let validQuantities = true;
      document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
        const item = checkbox.value;
        const qtyInput = document.querySelector(`input[name='refund_quantity[${item}]']`);
        const qty = parseInt(qtyInput.value);
        const max = parseInt(qtyInput.max);
        if (qty <= 0 || qty > max) {
          validQuantities = false;
          alert(`Invalid refund quantity for item '${item}'. Must be between 1 and ${max}.`);
        }
      });
      if (!validQuantities) return false;
    }
    return confirm(isRefundSelected ? 'Are you sure you want to refund the selected items?' : 'Are you sure you want to refund all items?');
  }
</script>

<?php include "footer.php"; ?>

<!-- chbluxury -->