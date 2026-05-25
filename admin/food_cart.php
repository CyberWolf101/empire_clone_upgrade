<?php
// First query to check if there are items in the cart
$bot = "SELECT * FROM refreshments WHERE orderid = ?";
$stmt1 = $con->prepare($bot);
$stmt1->bind_param("s", $saloon);
$stmt1->execute();
$bot2 = $stmt1->get_result();
$stmt1->close(); // Close the first statement

if ($bot2->num_rows > 0) {
?>
    <!-- Datatables -->
    <div class="col-lg-12" style="margin-top:2%;">
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Refreshment Cart</h6>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-items-center table-flush text-primary">
                    <thead class="thead-light">
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Second query to fetch and display items
                        $sql = "SELECT r.*, f.quantity as available_quantity FROM refreshments r 
            JOIN food_menu f ON r.itemid = f.s 
            WHERE r.orderid = ? ORDER BY r.s ASC";
                        $stmt2 = $con->prepare($sql);
                        $stmt2->bind_param("s", $saloon);
                        $stmt2->execute();
                        $sql2 = $stmt2->get_result();
                        while ($row = $sql2->fetch_assoc()) {
                            $available_quantity = $row['available_quantity'];
                            echo "
        <tr data-item-id='" . htmlspecialchars($row['s']) . "'>
            <td>" . htmlspecialchars($row['item']) . "</td>
            <td>&#8358;" . htmlspecialchars($row['unitprice']) . "</td>
            <td>
                <div class='input-group input-group-sm' style='width: 120px;'>
                    <button class='btn btn-outline-danger btn-decrease' type='button' data-item-id='" . htmlspecialchars($row['s']) . "' data-max-quantity='" . htmlspecialchars($available_quantity) . "'>-</button>
                    <input type='number' class='form-control text-center quantity-input' value='" . htmlspecialchars($row['quantity']) . "' min='1' max='" . htmlspecialchars($available_quantity) . "' data-item-id='" . htmlspecialchars($row['s']) . "' data-max-quantity='" . htmlspecialchars($available_quantity) . "' data-last-valid='" . htmlspecialchars($row['quantity']) . "'>
                    <button class='btn btn-outline-success btn-increase' type='button' data-item-id='" . htmlspecialchars($row['s']) . "' data-max-quantity='" . htmlspecialchars($available_quantity) . "'>+</button>
                </div>
            </td>
            <td class='total-price'>&#8358;" . htmlspecialchars($row['totalprice']) . "</td>
            <td>
                <form action='' method='post' onsubmit='return confirm(\"Are you sure you want to delete this item (" . htmlspecialchars($row['item']) . ")?\");'>
                    <input type='hidden' name='categoryid' value='" . htmlspecialchars($row['s']) . "' required>
                    <input type='submit' name='delete' value='Delete Item' class='btn btn-sm btn-danger'>
                </form>
            </td>
        </tr>";
                        }
                        $stmt2->close(); // Close the second statement
                        ?>
                    </tbody>
                </table>
                <?php
                if (isset($_POST["take-order-as-credit"])) {
                    $orderID = $_POST["orderid"];
                    $customer = $_POST["customer"];
                    $password = $_POST["password"];
                    if ($password == $pass) {
                        $orders = [];
                        $selectOrders = "SELECT * FROM refreshments WHERE orderid = '$orderID'";
                        $result = mysqli_query($con, $selectOrders);
                        while ($row = mysqli_fetch_assoc($result)) {
                            $orders[] = $row;
                        }
                        foreach ($orders as $order) {
                    // var_dump($orders);
                            /**
                             * array(1) { 
                             * [0]=> array(16) 
                             * { ["s"]=> string(1) "0" 
                             * ["orderid"]=> string(6) "c387ca" 
                             * ["itemid"]=> string(2) "69" 
                             * ["item"]=> string(22) "Beef shawarma plus 407" 
                             * ["unitprice"]=> string(4) "1000" 
                             * ["quantity"]=> string(1) "2" 
                             * ["totalprice"]=> string(4) "2000" 
                             * ["status"]=> string(10) "processing" 
                             * ["preorder"]=> string(1) "0" 
                             * ["preorder_date"]=> NULL 
                             * ["total_left"]=> string(1) "0" 
                             * ["date"]=> string(19) "2026-05-12 12:06:42" 
                             * ["pre_order_complete"]=> string(1) "0" 
                             * ["item_category"]=> string(5) "bread" 
                             * ["discount_added"]=> string(1) "0" 
                             * ["amount_paid"]=> string(0) "" } }
                             */
                            $orderid = $order["orderid"];
                            $itemid = $order["itemid"];
                            $item = $order["item"];
                            $unitprice = $order["unitprice"];
                            $quantity = $order["quantity"];
                            $totalprice = $order["totalprice"];
                            $item_category = $order["item_category"];
                            $insertSQL = "INSERT INTO credit_sales(orderid,itemid,item,unitprice,quantity, totalprice,item_category,customer) VALUES 
                        ('$orderid','$itemid','$item','$unitprice','$quantity','$totalprice','$item_category','$customer')";
                            $stmt = mysqli_query($con, $insertSQL);
                            if ($stmt) {
                                $_SESSION["success"] = "Product taken as credit";
                                $deleteSQL = "DELETE FROM refreshments WHERE orderid = '$orderID'";
                                mysqli_query($con, $deleteSQL);
                            } else {
                                $_SESSION["error"] = "Failed to take product as credit";
                            }
                        }
                    }
                }
                ?>
                <div class="" id="takeCreditModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="post">
                                <div class="modal-header">
                                    <p class="modal-text">Take order as credit</p>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="orderid" value="<?= $_COOKIE["orderID"] ?? "" ?>">
                                    <label for="customer" class="form-label">Select Customer</label>
                                    <select name="customer" id="customer" class="form-control">
                                        <option value="">---- Select Customer ----</option>
                                        <?php
                                        $eligibleCustomers = [];
                                        $query = "SELECT * FROM customers WHERE credit_sales_eligibility = 'true'";
                                        $result = mysqli_query($con, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            $eligibleCustomers[] = $row;
                                        }
                                        if (!count($eligibleCustomers) > 0) {
                                        ?>
                                            <option value="" disabled>No eligible customers present.</option>
                                        <?php
                                        }
                                        foreach ($eligibleCustomers as $eligibleCustomer) {
                                        ?>
                                            <option value="<?= $eligibleCustomer["unique_id"] ?>"><?= $eligibleCustomer["name"] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                    <button class="btn btn-warning" type="submit" name="take-order-as-credit">Proceed</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <center>
                    <p><a href="javascript:void(0);" class='btn btn-sm btn-warning' data-bs-toggle="modal" data-bs-target="#takeCreditModal">Take as credit</a></p>
                    <p><a href="foodcheckout.php" class='btn btn-sm btn-warning'>Proceed to checkout</a></p>
                </center>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            console.log('Document ready - initializing quantity controls');

            // Handle quantity increase
            $('.btn-increase').click(function() {
                console.log('Increase button clicked');
                var button = $(this);
                var itemId = button.data('item-id');
                var maxQuantity = parseInt(button.data('max-quantity'));
                var input = button.siblings('.quantity-input');
                var currentQuantity = parseInt(input.val()) || parseInt(input.data('last-valid'));

                console.log('Increase - Item ID:', itemId, 'Current Qty:', currentQuantity, 'Max Qty:', maxQuantity);

                if (currentQuantity < maxQuantity) {
                    var newQuantity = currentQuantity + 1;
                    console.log('Increasing to:', newQuantity);
                    updateQuantity(itemId, newQuantity, input, button);
                } else {
                    console.log('Cannot increase - exceeds max');
                    alert('Cannot exceed available quantity of ' + maxQuantity);
                }
            });

            // Handle quantity decrease
            $('.btn-decrease').click(function() {
                console.log('Decrease button clicked');
                var button = $(this);
                var itemId = button.data('item-id');
                var input = button.siblings('.quantity-input');
                var currentQuantity = parseInt(input.val()) || parseInt(input.data('last-valid'));

                console.log('Decrease - Item ID:', itemId, 'Current Qty:', currentQuantity);

                if (currentQuantity > 1) {
                    var newQuantity = currentQuantity - 1;
                    console.log('Decreasing to:', newQuantity);
                    updateQuantity(itemId, newQuantity, input, button);
                } else {
                    console.log('Cannot decrease below 1');
                }
            });

            // Handle direct input in quantity field
            $('.quantity-input').on('change', function() {
                console.log('Quantity input changed (change event)');
                validateAndUpdateQuantity($(this));
            });

            $('.quantity-input').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    console.log('Enter key pressed in quantity input');
                    e.preventDefault(); // Prevent form submission
                    validateAndUpdateQuantity($(this));
                }
            });

            function validateAndUpdateQuantity(input) {
                var itemId = input.data('item-id');
                var maxQuantity = parseInt(input.data('max-quantity'));
                var lastValid = parseInt(input.data('last-valid'));
                var newQuantity = parseInt(input.val());
                var button = input.siblings('.btn-increase'); // Use for finding row

                console.log('Validate - Item ID:', itemId, 'New Qty:', newQuantity, 'Max Qty:', maxQuantity, 'Last Valid:', lastValid);

                if (isNaN(newQuantity) || newQuantity < 1) {
                    console.log('Invalid input - less than 1 or not a number, reverting to last valid');
                    alert('Quantity must be at least 1');
                    input.val(lastValid);
                } else if (newQuantity > maxQuantity) {
                    console.log('Invalid input - exceeds max, reverting to max');
                    alert('Cannot exceed available quantity of ' + maxQuantity);
                    input.val(maxQuantity);
                    updateQuantity(itemId, maxQuantity, input, button);
                } else {
                    console.log('Valid input - updating quantity');
                    input.data('last-valid', newQuantity); // Update last valid value
                    updateQuantity(itemId, newQuantity, input, button);
                }
            }

            function updateQuantity(itemId, newQuantity, input, button) {
                var orderId = '<?php echo htmlspecialchars($saloon); ?>';
                var requestData = {
                    item_id: itemId,
                    quantity: newQuantity,
                    order_id: orderId
                };

                console.log('updateQuantity called with data:', requestData);

                $.ajax({
                    type: 'POST',
                    url: 'update_quantity.php',
                    data: requestData,
                    dataType: 'json',
                    beforeSend: function(xhr, settings) {
                        console.log('AJAX beforeSend - URL:', settings.url);
                        console.log('AJAX beforeSend - Data:', settings.data);
                    },
                    success: function(response, textStatus, xhr) {
                        console.log('AJAX success triggered');
                        console.log('Raw responseText:', xhr.responseText);
                        console.log('Parsed response:', response);

                        if (response && response.success) {
                            console.log('Update successful - updating UI');
                            input.val(newQuantity);
                            input.data('last-valid', newQuantity); // Update last valid value
                            var row = button.closest('tr');
                            var unitPrice = parseFloat(response.unit_price);
                            console.log('Unit price from response:', unitPrice);
                            var newTotal = (newQuantity * unitPrice).toFixed(2);
                            console.log('New total:', newTotal);
                            row.find('.total-price').html('&#8358;' + newTotal);
                            console.log('UI updated successfully');
                        } else {
                            console.log('Update failed or no success flag');
                            var errorMsg = response && response.error ? response.error : 'Unknown error - no success flag';
                            console.error('Error message:', errorMsg);
                            alert('Error updating quantity: ' + errorMsg);
                            input.val(input.data('last-valid')); // Revert to last valid
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log('AJAX error triggered');
                        console.log('textStatus:', textStatus);
                        console.log('errorThrown:', errorThrown);
                        console.log('Raw responseText:', xhr.responseText);
                        console.log('Status code:', xhr.status);

                        var errorMsg = 'Error connecting to server: ' + textStatus + ' - ' + errorThrown + '\nStatus: ' + xhr.status + '\nRaw Response: ' + xhr.responseText;
                        console.error(errorMsg);
                        alert(errorMsg);
                        input.val(input.data('last-valid')); // Revert to last valid
                    },
                    complete: function(xhr, textStatus) {
                        console.log('AJAX complete - Status:', textStatus);
                    }
                });
            }
        });
    </script>
<?php
}
?>