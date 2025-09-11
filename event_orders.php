<?php
// session_start();
include "header.php";

// Create event_orders table
$createEventOrdersQuery = "
    CREATE TABLE IF NOT EXISTS event_orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        customer_name VARCHAR(255) NOT NULL,
        order_ref VARCHAR(20) UNIQUE NOT NULL,
        phone_number VARCHAR(20) NOT NULL,
        email VARCHAR(255) NOT NULL,
        total_amount DECIMAL(10,2) DEFAULT 0,
        edited_price DECIMAL(10,2) DEFAULT 0,
        status VARCHAR(50) DEFAULT 'pending',
        pay_status VARCHAR(50) DEFAULT 'pending',
        section VARCHAR(50) DEFAULT 'refreshments',
        type VARCHAR(50) DEFAULT 'event',
        created_at DATETIME NOT NULL
    ) ENGINE=InnoDB";
if (!mysqli_query($con, $createEventOrdersQuery)) {
    error_log("Failed to create event_orders table: " . mysqli_error($con));
    echo "<script>alert('Error setting up event_orders table: " . addslashes(mysqli_error($con)) . "'); window.location.href='event_orders.php';</script>";
    exit;
}

// Add phone_number and email columns if they don't exist
$checkColumnsQuery = "SHOW COLUMNS FROM event_orders LIKE 'phone_number'";
if (mysqli_num_rows(mysqli_query($con, $checkColumnsQuery)) == 0) {
    $alterQuery = "ALTER TABLE event_orders ADD phone_number VARCHAR(20) NOT NULL AFTER customer_name";
    if (!mysqli_query($con, $alterQuery)) {
        error_log("Failed to add phone_number column: " . mysqli_error($con));
        echo "<script>alert('Error updating event_orders table: " . addslashes(mysqli_error($con)) . "'); window.location.href='event_orders.php';</script>";
        exit;
    }
}
$checkColumnsQuery = "SHOW COLUMNS FROM event_orders LIKE 'email'";
if (mysqli_num_rows(mysqli_query($con, $checkColumnsQuery)) == 0) {
    $alterQuery = "ALTER TABLE event_orders ADD email VARCHAR(255) NOT NULL AFTER phone_number";
    if (!mysqli_query($con, $alterQuery)) {
        error_log("Failed to add email column: " . mysqli_error($con));
        echo "<script>alert('Error updating event_orders table: " . addslashes(mysqli_error($con)) . "'); window.location.href='event_orders.php';</script>";
        exit;
    }
}

// Create event_order_items table
$createItemsQuery = "
    CREATE TABLE IF NOT EXISTS event_order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        orderid INT NOT NULL,
        itemid INT NOT NULL,
        item VARCHAR(255) NOT NULL,
        unitprice DECIMAL(10,2) NOT NULL,
        quantity INT NOT NULL,
        totalprice DECIMAL(10,2) NOT NULL,
        edited_price DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (orderid) REFERENCES event_orders(id) ON DELETE CASCADE
    ) ENGINE=InnoDB";
if (!mysqli_query($con, $createItemsQuery)) {
    // Fallback: Create without foreign key
    $createItemsQueryFallback = "
        CREATE TABLE IF NOT EXISTS event_order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            orderid INT NOT NULL,
            itemid INT NOT NULL,
            item VARCHAR(255) NOT NULL,
            unitprice DECIMAL(10,2) NOT NULL,
            quantity INT NOT NULL,
            totalprice DECIMAL(10,2) NOT NULL,
            edited_price DECIMAL(10,2) NOT NULL
        ) ENGINE=InnoDB";
    if (!mysqli_query($con, $createItemsQueryFallback)) {
        error_log("Failed to create event_order_items table: " . mysqli_error($con));
        echo "<script>alert('Error setting up event_order_items table: " . addslashes(mysqli_error($con)) . "'); window.location.href='event_orders.php';</script>";
        exit;
    }
}

// Initialize event cart
$eventCart = $_SESSION['eventCart'] ?? [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['placeOrder'])) {
    $customer_name = mysqli_real_escape_string($con, $_POST['customer_name']);
    $phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $items = $_POST['items'] ?? [];
    $total_amount = isset($_POST['total_amount']) ? (float) $_POST['total_amount'] : 0.00;
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

    // Insert into event_orders
    $sql = "INSERT INTO event_orders (order_ref, customer_name, phone_number, email, total_amount, status, pay_status, section, type, created_at, edited_price) 
            VALUES ('$order_ref','$customer_name', '$phone_number', '$email', $total_amount, 'pending', 'pending', 'refreshments', 'event', NOW(), 0)";
    if (mysqli_query($con, $sql)) {
        $order_id = mysqli_insert_id($con);

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
                        $query = "INSERT INTO event_order_items (orderid, itemid, item, unitprice, quantity, totalprice, edited_price) 
                                  VALUES ('$order_id', '$itemid', '$item_name', '$item_price', '$quantity', '$total_price', 0)";
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
        echo "<script>alert('Event order request submitted successfully!'); window.location.href='event_orders.php';</script>";
    } else {
        $error = mysqli_error($con);
        echo "<script>alert('Error submitting request: " . addslashes($error) . "'); window.location.href='event_orders.php';</script>";
    }
}
?>

<style>
    .ter {
        background-color: #fff;
        padding: 0 10px;
    }

    .check {
        padding: 2%;
        font-size: 12px;
        width: 25%;
    }

    .check span {
        font-size: 13px;
        font-weight: 600;
    }

    .btn-buya {
        display: inline-block;
        padding: 6px !important;
        border: none;
        color: #fff;
        font-size: 10px !important;
        text-transform: uppercase;
        font-family: "Poppins", sans-serif;
        font-weight: 600;
        transition: 0.3s;
        background: #FEBF01;
        margin: 4px;
    }

    .btn-buya:hover {
        font-size: 12px !important;
        font-weight: 800;
        background: #000;
    }

    .form-control {
        height: 40px;
        border-radius: none !important;
    }

    .section-title h2::after {
        content: "";
        position: absolute;
        display: block;
        width: 80px;
        background: none;
        bottom: 0;
        left: calc(2% - 25px);
    }

    .box {
        border-radius: 0px;
    }

    .pricing .box {
        padding: 20px 0 0;
        background: #f8f8f8;
        text-align: center;
        box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.12);
        border-radius: 0px;
        position: relative;
        overflow: hidden;
    }

    .nav-tabs .nav-link.active {
        background-color: #FEBF01;
        color: #fff;
    }

    .nav-tabs .nav-link {
        color: #000;
    }

    /* Search Bar and Suggestions Styles */
    .search-container {
        position: relative;
        margin-bottom: 20px;
        width: 100%;
        max-width: 500px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-input {
        flex-grow: 1;
        padding: 10px;
        font-size: 14px;
        border: 2px solid #FEBF01;
        border-radius: 5px;
        outline: none;
    }

    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
    }

    .search-suggestions div {
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    .search-suggestions div:hover {
        background: #f0f0f0;
    }

    .search-suggestions div:last-child {
        border-bottom: none;
    }
</style>

<!-- Event Orders Section -->
<section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none; border:none;">
    <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#000;">
            <h3 style="text-decoration:none; color:#000;">EVENT ORDERS<br><span style="font-size:14px;">Request bulk
                    orders from our menu or customize your items</span></h3>
        </div>
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
                                    <input type="text" id="searchInput" class="search-input" placeholder="Search food items...">
                                    <button type="button" class="btn-buya" onclick="triggerSearch()">Search</button>
                                    <div id="searchSuggestions" class="search-suggestions"></div>
                                </div>

                                <p>
                                    <button type="button" onclick="showAllItems()" class="btn-buya">ALL ITEMS</button>
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
                                                <input type="text" class="form-control" name="items[custom][0][name]"
                                                    placeholder="Item Name">
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
                            <input type="text" class="form-control" id="estimated_fee" name="total_amount" readonly>
                        </div>

                        <button type="submit" name="placeOrder" class="btn-buya">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
</section>

<script>
    let customItemIndex = 1;
    let debounceTimeout;

    function addCustomItem() {
        const customItems = document.getElementById('customItems');
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
    }

    function removeCustomItem(button) {
        button.closest('.custom-item').remove();
        calculateEstimatedFee();
    }

    function calculateEstimatedFee() {
        let total = 0;
        document.querySelectorAll('#menuItemsTab .quantity').forEach(input => {
            const quantity = parseFloat(input.value) || 0;
            const price = parseFloat(input.dataset.price) || 0;
            total += quantity * price;
        });
        document.getElementById('estimated_fee').value = total.toFixed(2);
    }

    function showCategory(category) {
        const searchInput = document.getElementById('searchInput').value.trim();
        if (searchInput) {
            // If search is active, don't filter by category
            triggerSearch();
        } else {
            console.log("Filtering by category:", category); // Debug log
            document.querySelectorAll('.ter').forEach(item => item.style.display = 'none');
            document.querySelectorAll('.' + category).forEach(item => item.style.display = 'table-row');
        }
    }

    function showAllItems() {
        const searchInput = document.getElementById('searchInput').value.trim();
        if (searchInput) {
            // If search is active, don't show all items
            triggerSearch();
        } else {
            console.log("Showing all items"); // Debug log
            document.querySelectorAll('.ter').forEach(item => item.style.display = 'table-row');
        }
    }

    function confirmSubmission() {
        return confirm('Are you sure you want to submit this event order request?');
    }

    // Search and Suggestions Functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(debounceTimeout);
        const query = e.target.value.trim();
        const suggestionsDiv = document.getElementById('searchSuggestions');

        if (query.length > 0) {
            debounceTimeout = setTimeout(() => {
                console.log("Fetching suggestions for query:", query); // Debug log
                fetchSuggestions(query, suggestionsDiv);
            }, 300);
        } else {
            suggestionsDiv.style.display = 'none';
            suggestionsDiv.innerHTML = '';
            triggerSearch(); // Show all items when search is cleared
        }
    });

    function fetchSuggestions(query, suggestionsDiv) {
        // Ensure jQuery is available
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
            success: function(data) {
                console.log("Suggestions received:", data); // Debug log
                suggestionsDiv.innerHTML = '';
                if (data.error) {
                    suggestionsDiv.innerHTML = '<div>Error: ' + data.error + '</div>';
                    suggestionsDiv.style.display = 'block';
                } else if (data.length > 0) {
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.textContent = item.item;
                        div.addEventListener('click', () => {
                            document.getElementById('searchInput').value = item.item;
                            suggestionsDiv.style.display = 'none';
                            triggerSearch();
                        });
                        suggestionsDiv.appendChild(div);
                    });
                    suggestionsDiv.style.display = 'block';
                } else {
                    suggestionsDiv.innerHTML = '<div>No matching items found</div>';
                    suggestionsDiv.style.display = 'block';
                }
            },
            error: function(xhr, status, error) {
                console.error('Fetch suggestions error:', status, error, 'Status code:', xhr.status); // Debug log
                suggestionsDiv.innerHTML = '<div>Error loading suggestions: ' + (xhr.status === 404 ? 'search_api.php not found' : 'Server error') + '. Please try again.</div>';
                suggestionsDiv.style.display = 'block';
            }
        });
    }

    function triggerSearch() {
        const query = document.getElementById('searchInput').value.trim().toLowerCase();
        console.log("Triggering search for:", query); // Debug log
        document.querySelectorAll('#menuItems .ter').forEach(row => {
            const itemName = row.querySelector('td:first-child').textContent.toLowerCase().replace('(out of stock)', '').trim();
            row.style.display = query ? (itemName.includes(query) ? 'table-row' : 'none') : 'table-row';
        });
        document.getElementById('searchSuggestions').style.display = 'none';
    }

    // Initialize estimated fee on page load
    document.addEventListener('DOMContentLoaded', calculateEstimatedFee);
</script>

<?php include "footer.php"; ?>