<?php
include "header.php";

if (!empty($_SESSION['success_message'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
    unset($_SESSION['success_message']); // Clear after showing
}

// Initialize event cart
$eventCart = $_SESSION['eventCart'] ?? [];
$order_ref = '';
$shipping_type = isset($_SESSION['shipping_type']) ? $_SESSION['shipping_type'] : 'delivery';
$delivery_address = isset($_SESSION['delivery_address']) ? $_SESSION['delivery_address'] : '';

function generateOrderRef($length = 7)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Ensure unique reference
do {
    $order_ref = generateOrderRef();
    $check = mysqli_query($con, "SELECT id FROM event_orders WHERE order_ref='$order_ref'");
} while (mysqli_num_rows($check) > 0);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['placeOrder'])) {
    $customer_name = mysqli_real_escape_string($con, $_POST['customer_name']);
    $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $items = $_POST['items'] ?? [];
    $total_amount = isset($_POST['total_amount']) ? (float) $_POST['total_amount'] : 0.00;
    $date = mysqli_real_escape_string($con, $_POST['date']);
    $time = mysqli_real_escape_string($con, $_POST['time']);
    $shipping_type = mysqli_real_escape_string($con, $_POST['delivery_option'] ?? 'delivery');
    $delivery_address = isset($_POST['delivery_address']) ? mysqli_real_escape_string($con, $_POST['delivery_address']) : '';
    $referral_code = !empty($_POST['referral_code']) ? mysqli_real_escape_string($con, $_POST['referral_code']) : "";

    // Store delivery details in session
    $_SESSION['shipping_type'] = $shipping_type;
    $_SESSION['delivery_address'] = $delivery_address;
    $sql11 = "SELECT * FROM admin WHERE staff_code = '$referral_code' AND code_status = 'active'";
    $real_price = 0;
    $stmt = mysqli_query($con, $sql11);
    $sql12 = "SELECT * FROM staff WHERE staff_code = '$referral_code' AND code_status = 'active'";
    $stmt2 = mysqli_query($con, $sql12);
    if (mysqli_num_rows($stmt) > 0) {

        $real_price = $total_amount - ($total_amount * 0.05);

    } elseif (mysqli_num_rows($stmt2) > 0) {

        $real_price = $total_amount - ($total_amount * 0.05);

        $row = mysqli_fetch_assoc($stmt2);

        $newWallet = $row['wallet'] + ($total_amount * 0.05);

        $sqlll = "UPDATE staff 
              SET wallet = '$newWallet' 
              WHERE staff_code = '$referral_code'";

        mysqli_query($con, $sqlll);

    } else {

        $real_price = $total_amount;

    }

    $sql = "INSERT INTO event_orders (order_ref, customer_name, phone_number, email, total_amount, status, pay_status, section, type, created_at, edited_price, delivery_date, delivery_time, shipping_type, delivery_address, referral_code) 
            VALUES ('$order_ref', '$customer_name', '$phone_number', '$email', $total_amount, 'pending', 'pending', 'refreshments', 'event', NOW(), $real_price, '$date', '$time', '$shipping_type', '$delivery_address','$referral_code')";

    if (mysqli_query($con, $sql)) {


        // Insert menu items into event_order_items
        if (!empty($items['menu'])) {
            foreach ($items['menu'] as $itemid => $data) {
                $quantity = (int) $data['quantity'];
                if ($quantity > 0) {
                    $res = mysqli_query($con, "SELECT item, price FROM food_menu WHERE s='$itemid'");
                    if ($row = mysqli_fetch_assoc($res)) {
                        $item_name = mysqli_real_escape_string($con, $row['item']);
                        $item_price = (float) $row['price'];
                        $total_price = $quantity * $item_price;

                        $order_id = mysqli_insert_id($con);
                        $query = "INSERT INTO event_order_items (orderid, itemid, item, unitprice, quantity, totalprice, edited_price) 
                                  VALUES ('$order_id', '$itemid', '$item_name', '$item_price', '$quantity', '$real_price', 0)";
                        if (!mysqli_query($con, $query)) {
                            error_log("Insert menu item failed: " . mysqli_error($con));
                        }
                    }
                }
            }
        }

        // Insert custom items into event_order_items
        if (!empty($items['custom'])) {
            foreach ($items['custom'] as $data) {
                $quantity = (int) $data['quantity'];
                if ($quantity > 0) {
                    $item_name = mysqli_real_escape_string($con, $data['name']);
                    $query = "INSERT INTO event_order_items (orderid, itemid, item, unitprice, quantity, totalprice, edited_price) 
                              VALUES ('$order_id', 0, '$item_name', 0.00, '$quantity', 0, 0)";
                    if (!mysqli_query($con, $query)) {
                        error_log("Insert custom item failed: " . mysqli_error($con));
                    }
                }
            }
        }

        // Clear event cart
        unset($_SESSION['eventCart']);
        $_SESSION['success_message'] = "Event order request submitted successfully!";
        header("Location: event_orders.php");
        exit;
    } else {
        $error = mysqli_error($con);
        echo "<script>alert('Error submitting request: " . addslashes($error) . "'); window.location.href='event_orders.php';</script>";
        exit;
    }
}
?>

<!-- Event Orders Section -->
<section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none; border:none;">
    <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#000;">
            <h3 style="text-decoration:none; color:#000;">EVENT ORDERS<br><span style="font-size:14px;">Request bulk
                    orders from our menu or customize your items</span></h3>
        </div>
        <?php include "event_order_warning.php" ?>
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <p style="color:#FEBF01;">Food, Snacks, Drinks and much more...</p>
            </div>
            <div class="col-lg-12 col-md-12">
                <div class="box" data-aos="zoom-in" data-aos-delay="100">
                    <form action="" method="post" id="eventOrderForm" onsubmit="return confirmSubmission()">
                        <div class="form-group mb-4">
                            <label for="customer_name">Customer Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="phone_number">Phone Number</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="date">Expected Date of delivery</label>
                            <center>
                                <input type="date" style="width: 100%;" class="form-control" id="date" name="date"
                                    required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            </center>

                        </div>
                        <div class="form-group mb-4">
                            <label for="time">Expected Time of delivery</label>
                            <center>
                                <input type="time" style="width: 10%0;" class="form-control" id="time" name="time"
                                    required>
                            </center>
                            <!-- Delivery Options -->
                            <div class="delivery-options mt-4">
                                <h6 style="color: black;">Delivery Options</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="delivery_option" id="pickup"
                                        value="pickup" <?php echo $shipping_type === 'pickup' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="pickup" style="color: black;">
                                        Pickup at Store
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="delivery_option" id="delivery"
                                        value="delivery" <?php echo $shipping_type === 'delivery' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="delivery" style="color: black;">
                                        Delivery within Lagos
                                    </label>
                                </div>
                                <!-- Pickup Details -->
                                <div id="pickup-details"
                                    style="display: <?php echo $shipping_type === 'pickup' ? 'block' : 'none'; ?>;"
                                    class="border border-1 p-2 mt-4">
                                    <div class="mb-1">
                                        <b>PICKUP ADDRESS:</b>
                                    </div>
                                    <p class="text-black"><b>Address:</b> 19 Olowu St, Opebi 101233, Ikeja, Lagos</p>
                                    <p class="text-black"><b>Phone:</b> 09025572552</p>
                                    <p class="text-black"><b>Pickup Code:</b>
                                        <?php echo htmlspecialchars($order_ref); ?></p>
                                </div>
                                <!-- Delivery Address Input -->
                                <div id="delivery-details"
                                    style="display: <?php echo $shipping_type === 'delivery' ? 'block' : 'none'; ?>;"
                                    class="border border-1 p-2 mt-4">
                                    <div class="mb-1">
                                        <b>DELIVERY ADDRESS:</b>
                                    </div>
                                    <div class="form-group">
                                        <label for="delivery_address" style="color: black;">Enter your delivery
                                            address:</label>
                                        <input type="text" class="form-control" id="delivery_address"
                                            name="delivery_address"
                                            value="<?php echo isset($delivery_address) ? htmlspecialchars($delivery_address) : ''; ?>"
                                            placeholder="Enter your address in Lagos" <?php echo $shipping_type === 'delivery' ? 'required' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                            <!-- Tabs -->
                            <ul class="nav nav-tabs mb-4">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#menuItemsTab">Menu Items</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#customItemsTab">Custom Items</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <!-- Menu Items Tab -->
                                <div class="tab-pane fade show active" id="menuItemsTab">
                                    <!-- Search Bar -->
                                    <div class="search-container">
                                        <input type="text" id="searchInput" class="search-input"
                                            placeholder="Search food items...">
                                        <button type="button" class="btn-buya" onclick="triggerSearch()">Search</button>
                                        <div id="searchSuggestions" class="search-suggestions"></div>
                                    </div>
                                    <p>
                                        <button type="button" onclick="showAllItems()" class="btn-buya">ALL
                                            ITEMS</button>
                                        <?php
                                        $sql = "SELECT name FROM food_categories ORDER BY s";
                                        $sql2 = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_array($sql2)) {
                                            $category = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
                                            echo "<button type='button' onclick='showCategory(\"$category\")' class='btn-buya'>$category</button>";
                                        }
                                        ?>
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush" id="menuItems">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT s, item, price, type, quantity FROM food_menu ORDER BY item";
                                                $result = mysqli_query($con, $sql);
                                                if (!$result) {
                                                    echo "<tr><td colspan='3'>Error fetching menu: " . htmlspecialchars(mysqli_error($con)) . "</td></tr>";
                                                } else if (mysqli_num_rows($result) == 0) {
                                                    echo "<tr><td colspan='3'>No menu items available.</td></tr>";
                                                } else {
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        $id = (int) $row['s'];
                                                        $name = htmlspecialchars($row['item'], ENT_QUOTES, 'UTF-8');
                                                        $price = htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8');
                                                        $type = htmlspecialchars($row['type'], ENT_QUOTES, 'UTF-8');
                                                        $quantity = (int) $row['quantity'];
                                                        $cartQty = isset($eventCart[$id]) ? (int) $eventCart[$id]['quantity'] : 0;
                                                        echo "
                                                        <tr class='ter $type'>
                                                            <td>$name</td>
                                                            <td>&#8358; $price</td>
                                                            <td>
                                                                <input type='number' class='form-control quantity' name='items[menu][$id][quantity]' min='0' value='$cartQty' 
                                                                       data-name='$name' data-price='$price' onchange='calculateEstimatedFee()'>
                                                                <input type='hidden' name='items[menu][$id][name]' value='$name'>
                                                                <input type='hidden' name='items[menu][$id][price]' value='$price'>
                                                            </td>
                                                        </tr>";
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- Custom Items Tab -->
                                <div class="tab-pane fade" id="customItemsTab">
                                    <div id="customItems">
                                        <div class="custom-item mb-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control"
                                                        name="items[custom][0][name]" placeholder="Item Name">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control quantity"
                                                        name="items[custom][0][quantity]" placeholder="Quantity" min="0"
                                                        value="0" onchange="calculateEstimatedFee()">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="removeCustomItem(this)">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-buya mb-3" onclick="addCustomItem()">Add Custom
                                        Item</button>
                                </div>
                            </div>
                            <!-- Estimated Fee -->
                            <div class="form-group mt-4">
                                <label for="estimated_fee">Estimated Fee</label>
                                <input type="hidden" class="form-control" id="estimated_fee" name="total_amount">
                                <div id="estimated-field"></div>
                            </div>
                            <div class="form-group mt-4" id="">
                                <label for="referral-code">Referral Code(Optional - 5% discount)</label>
                                <input type="text" class="form-control" id="referral_code" style="display: none;"
                                    name="referral_code">
                            </div>
                            <button type="submit" name="placeOrder" class="btn-buya">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Floating Cart Button -->
<button id="cartButton" class="btn"
    style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; background-color: #ffc700;">
    🛒
</button>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cartModalForm" action="" method="post" onsubmit="return confirmSubmission()">
                    <input type="hidden" name="customer_name" id="modal_customer_name">
                    <input type="hidden" name="phone_number" id="modal_phone_number">
                    <input type="hidden" name="email" id="modal_email">
                    <input type="hidden" name="total_amount" id="modal_total_amount">
                    <input type="hidden" name="date" id="modal_date">
                    <input type="hidden" name="time" id="modal_time">
                    <input type="hidden" name="delivery_option" id="modal_delivery_option">
                    <input type="hidden" name="delivery_address" id="modal_delivery_address">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="cartItemsTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="cartItemsBody"></tbody>
                        </table>
                    </div>
                    <div class="form-group mt-4">
                        <label for="modal_estimated_fee">Estimated Total</label>
                        <input type="text" class="form-control" id="modal_estimated_fee" readonly>
                    </div>
                    <button type="submit" name="placeOrder" class="btn-buya mt-3">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #cartButton {
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    #cartItemsTable input[type="number"] {
        width: 80px;
    }
</style>

<script>
    let customItemIndex = 1;
    let debounceTimeout;
    let itemAdditionOrder = 0;

    function addCustomItem() {
        const customItems = document.getElementById('customItems');
        if (!customItems) {
            console.warn('Element with ID "customItems" not found.');
            return;
        }
        const newItem = document.createElement('div');
        newItem.className = 'custom-item mb-3';
        newItem.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <input type="text" class="form-control" name="items[custom][${customItemIndex}][name]" placeholder="Item Name">
            </div>
            <div class="col-md-4">
                <input type="number" class="form-control quantity" name="items[custom][${customItemIndex}][quantity]" placeholder="Quantity" min="0" value="0" onchange="calculateEstimatedFee()">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeCustomItem(this)">Remove</button>
            </div>
        </div>`;
        customItems.appendChild(newItem);
        customItemIndex++;
        updateCartModal();
    }

    function removeCustomItem(button) {
        button.closest('.custom-item').remove();
        calculateEstimatedFee();
        updateCartModal();
    }

    function calculateEstimatedFee() {
        const estimatedFeeInput = document.getElementById('estimated_fee');
        const modalEstimatedFeeInput = document.getElementById('modal_estimated_fee');
        const modalTotalAmountInput = document.getElementById('modal_total_amount');
        if (!estimatedFeeInput || !modalEstimatedFeeInput || !modalTotalAmountInput) {
            console.warn('One or more estimated fee inputs not found.');
            return;
        }
        let total = 0;
        document.querySelectorAll('#menuItemsTab .quantity').forEach(input => {
            const quantity = parseFloat(input.value) || 0;
            const price = parseFloat(input.dataset.price) || 0;
            total += quantity * price;
        });
        estimatedFeeInput.value = total.toFixed(2);
        modalEstimatedFeeInput.value = total.toFixed(2);
        modalTotalAmountInput.value = total.toFixed(2);
        document.querySelector("#estimated-field").innerHTML = total.toFixed(2);
        console.log(estimatedFeeInput)
        setTimeout(() => {
            if (parseInt(estimatedFeeInput.value) >= 50000) {
                document.getElementById("referral_code").style.display = "block";
            } else {
                document.getElementById("referral_code").style.display = "none";
            }
        }, 100);
        updateCartModal();
    }

    function showCategory(category) {
        const searchInput = document.getElementById('searchInput');
        if (!searchInput) {
            console.warn('Element with ID "searchInput" not found.');
            return;
        }
        const searchValue = searchInput.value.trim();
        if (searchValue) {
            triggerSearch();
        } else {
            console.log("Filtering by category:", category);
            document.querySelectorAll('.ter').forEach(item => item.style.display = 'none');
            document.querySelectorAll('.' + category).forEach(item => item.style.display = 'table-row');
        }
    }

    function showAllItems() {
        const searchInput = document.getElementById('searchInput');
        if (!searchInput) {
            console.warn('Element with ID "searchInput" not found.');
            return;
        }
        const searchValue = searchInput.value.trim();
        if (searchValue) {
            triggerSearch();
        } else {
            console.log("Showing all items");
            document.querySelectorAll('.ter').forEach(item => item.style.display = 'table-row');
        }
    }

    function confirmSubmission() {
        return confirm('Are you sure you want to submit this event order request?');
    }

    function fetchSuggestions(query, suggestionsDiv) {
        if (typeof $ === 'undefined') {
            console.error("jQuery is not loaded. Please include jQuery.");
            suggestionsDiv.innerHTML = '<div>jQuery is not loaded. Contact support.</div>';
            suggestionsDiv.style.display = 'block';
            return;
        }

        $.ajax({
            url: 'search_api.php',
            type: 'POST',
            data: { search: query },
            dataType: 'json',
            success: function (data) {
                console.log("Suggestions received:", data);
                suggestionsDiv.innerHTML = '';
                if (data.error) {
                    suggestionsDiv.innerHTML = '<div>Error: ' + data.error + '</div>';
                    suggestionsDiv.style.display = 'block';
                } else if (data.length > 0) {
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.textContent = item.item;
                        div.addEventListener('click', () => {
                            const searchInput = document.getElementById('searchInput');
                            if (searchInput) {
                                searchInput.value = item.item;
                                suggestionsDiv.style.display = 'none';
                                triggerSearch();
                            }
                        });
                        suggestionsDiv.appendChild(div);
                    });
                    suggestionsDiv.style.display = 'block';
                } else {
                    suggestionsDiv.innerHTML = '<div>No matching items found</div>';
                    suggestionsDiv.style.display = 'block';
                }
            },
            error: function (xhr, status, error) {
                console.error('Fetch suggestions error:', status, error, 'Status code:', xhr.status);
                suggestionsDiv.innerHTML = '<div>Error loading suggestions: ' + (xhr.status === 404 ? 'search_api.php not found' : 'Server error') + '. Please try again.</div>';
                suggestionsDiv.style.display = 'block';
            }
        });
    }

    function triggerSearch() {
        const searchInput = document.getElementById('searchInput');
        const searchSuggestions = document.getElementById('searchSuggestions');
        if (!searchInput || !searchSuggestions) {
            console.warn('Search input or suggestions div not found.');
            return;
        }
        const query = searchInput.value.trim().toLowerCase();
        console.log("Triggering search for:", query);
        document.querySelectorAll('#menuItems .ter').forEach(row => {
            const itemName = row.querySelector('td:first-child').textContent.toLowerCase().replace('(out of stock)', '').trim();
            row.style.display = query ? (itemName.includes(query) ? 'table-row' : 'none') : 'table-row';
        });
        searchSuggestions.style.display = 'none';
    }

    function updateCartModal() {
        const cartItemsBody = document.getElementById('cartItemsBody');
        if (!cartItemsBody) {
            console.warn('Element with ID "cartItemsBody" not found.');
            return;
        }
        cartItemsBody.innerHTML = '';

        const allItems = [];

        // Menu items
        document.querySelectorAll('#menuItemsTab .quantity').forEach(input => {
            const quantity = parseInt(input.value) || 0;
            if (quantity > 0) {
                const name = input.dataset.name;
                const price = parseFloat(input.dataset.price) || 0;
                const itemId = input.name.match(/\[menu\]\[(\d+)\]/)[1];
                if (!input.dataset.additionOrder) {
                    input.dataset.additionOrder = itemAdditionOrder++;
                }
                allItems.push({
                    type: 'menu',
                    name,
                    price,
                    quantity,
                    itemId,
                    additionOrder: parseInt(input.dataset.additionOrder)
                });
            }
        });

        // Custom items
        document.querySelectorAll('#customItems .custom-item').forEach((item, index) => {
            const nameInput = item.querySelector('input[name$="[name]"]');
            const quantityInput = item.querySelector('input[name$="[quantity]"]');
            const name = nameInput.value;
            const quantity = parseInt(quantityInput.value) || 0;
            if (quantity > 0 && name) {
                if (!quantityInput.dataset.additionOrder) {
                    quantityInput.dataset.additionOrder = itemAdditionOrder++;
                }
                allItems.push({
                    type: 'custom',
                    name,
                    quantity,
                    index,
                    additionOrder: parseInt(quantityInput.dataset.additionOrder)
                });
            }
        });

        // Sort items by additionOrder (descending)
        allItems.sort((a, b) => b.additionOrder - a.additionOrder);

        // Add items to cart table
        allItems.forEach(item => {
            const row = document.createElement('tr');
            if (item.type === 'menu') {
                const total = (item.quantity * item.price).toFixed(2);
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>₦${item.price.toFixed(2)}</td>
                    <td><input type="number" class="form-control quantity" name="items[menu][${item.itemId}][quantity]" min="0" value="${item.quantity}" data-name="${item.name}" data-price="${item.price}" data-addition-order="${item.additionOrder}" onchange="syncQuantities(this, ${item.itemId})"></td>
                    <td>₦${total}</td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteCartItem('menu', ${item.itemId})">Delete</button></td>
                    <input type="hidden" name="items[menu][${item.itemId}][name]" value="${item.name}">
                    <input type="hidden" name="items[menu][${item.itemId}][price]" value="${item.price}">
                `;
            } else {
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>-</td>
                    <td><input type="number" class="form-control quantity" name="items[custom][${item.index}][quantity]" min="0" value="${item.quantity}" data-addition-order="${item.additionOrder}" onchange="syncCustomQuantities(this, ${item.index})"></td>
                    <td>-</td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="deleteCartItem('custom', ${item.index})">Delete</button></td>
                    <input type="hidden" name="items[custom][${item.index}][name]" value="${item.name}">
                `;
            }
            cartItemsBody.appendChild(row);
        });
    }

    function syncQuantities(input, itemId) {
        const mainInput = document.querySelector(`input[name="items[menu][${itemId}][quantity]"]`);
        if (mainInput) {
            mainInput.value = input.value;
            mainInput.dataset.additionOrder = input.dataset.additionOrder || itemAdditionOrder++;
        }
        calculateEstimatedFee();
    }

    function syncCustomQuantities(input, index) {
        const mainInput = document.querySelector(`input[name="items[custom][${index}][quantity]"]`);
        if (mainInput) {
            mainInput.value = input.value;
            mainInput.dataset.additionOrder = input.dataset.additionOrder || itemAdditionOrder++;
        }
        calculateEstimatedFee();
    }

    function deleteCartItem(type, id) {
        if (type === 'menu') {
            const mainInput = document.querySelector(`input[name="items[menu][${id}][quantity]"]`);
            if (mainInput) {
                mainInput.value = 0;
            }
        } else if (type === 'custom') {
            const mainInput = document.querySelector(`input[name="items[custom][${id}][quantity]"]`);
            if (mainInput) {
                mainInput.value = 0;
            }
        }
        calculateEstimatedFee();
    }

    // Initialize JavaScript with null checks
    document.addEventListener('DOMContentLoaded', function () {
        // Search input
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function (e) {
                clearTimeout(debounceTimeout);
                const query = e.target.value.trim();
                const suggestionsDiv = document.getElementById('searchSuggestions');
                if (!suggestionsDiv) {
                    console.warn('Element with ID "searchSuggestions" not found.');
                    return;
                }
                if (query.length > 0) {
                    debounceTimeout = setTimeout(() => {
                        console.log("Fetching suggestions for query:", query);
                        fetchSuggestions(query, suggestionsDiv);
                    }, 300);
                } else {
                    suggestionsDiv.style.display = 'none';
                    suggestionsDiv.innerHTML = '';
                    triggerSearch();
                }
            });
        } else {
            console.warn('Element with ID "searchInput" not found.');
        }

        // Cart button
        const cartButton = document.getElementById('cartButton');
        if (cartButton) {
            cartButton.addEventListener('click', function () {
                updateCartModal();
                const customerName = document.getElementById('customer_name');
                const phoneNumber = document.getElementById('phone_number');
                const email = document.getElementById('email');
                const date = document.getElementById('date');
                const time = document.getElementById('time');
                const deliveryAddress = document.getElementById('delivery_address');
                const modalCustomerName = document.getElementById('modal_customer_name');
                const modalPhoneNumber = document.getElementById('modal_phone_number');
                const modalEmail = document.getElementById('modal_email');
                const modalDate = document.getElementById('modal_date');
                const modalTime = document.getElementById('modal_time');
                const modalDeliveryOption = document.getElementById('modal_delivery_option');
                const modalDeliveryAddress = document.getElementById('modal_delivery_address');

                if (customerName && modalCustomerName) modalCustomerName.value = customerName.value;
                if (phoneNumber && modalPhoneNumber) modalPhoneNumber.value = phoneNumber.value;
                if (email && modalEmail) modalEmail.value = email.value;
                if (date && modalDate) modalDate.value = date.value;
                if (time && modalTime) modalTime.value = time.value;
                if (modalDeliveryOption) {
                    modalDeliveryOption.value = document.querySelector('input[name="delivery_option"]:checked')?.value || 'delivery';
                }
                if (deliveryAddress && modalDeliveryAddress) modalDeliveryAddress.value = deliveryAddress.value;

                const cartModal = new bootstrap.Modal(document.getElementById('cartModal'), {});
                cartModal.show();
            });
        } else {
            console.warn('Element with ID "cartButton" not found.');
        }

        // Delivery toggle logic
        const pickupRadio = document.getElementById('pickup');
        const deliveryRadio = document.getElementById('delivery');
        const pickupDetails = document.getElementById('pickup-details');
        const deliveryDetails = document.getElementById('delivery-details');
        const deliveryAddressInput = document.getElementById('delivery_address');

        if (pickupRadio && deliveryRadio && pickupDetails && deliveryDetails && deliveryAddressInput) {
            function toggleDetails() {
                if (pickupRadio.checked) {
                    pickupDetails.style.display = 'block';
                    deliveryDetails.style.display = 'none';
                    deliveryAddressInput.removeAttribute('required');
                } else if (deliveryRadio.checked) {
                    pickupDetails.style.display = 'none';
                    deliveryDetails.style.display = 'block';
                    deliveryAddressInput.setAttribute('required', 'required');
                }
            }

            pickupRadio.addEventListener('change', toggleDetails);
            deliveryRadio.addEventListener('change', toggleDetails);
            deliveryAddressInput.addEventListener('input', function () {
                const modalDeliveryAddress = document.getElementById('modal_delivery_address');
                if (deliveryRadio.checked && modalDeliveryAddress) {
                    modalDeliveryAddress.value = deliveryAddressInput.value;
                }
            });

            toggleDetails();
        } else {
            console.warn('One or more delivery form elements not found.');
        }

        // Initialize estimated fee
        calculateEstimatedFee();
    });
</script>

<?php include "footer.php"; ?>