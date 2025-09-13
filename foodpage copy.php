<?php
session_start();
include "header.php";

// Validate order ID
$safeOrderId = !empty($saloon) ? mysqli_real_escape_string($con, $saloon) : '';

// Preload cart items from session or database
if (!isset($_SESSION['cartItems'][$safeOrderId]) && !empty($safeOrderId)) {
    $cartRes = mysqli_query($con, "SELECT itemid, quantity, preorder FROM refreshments WHERE orderid='$safeOrderId' AND status='processing'");
    while ($c = mysqli_fetch_assoc($cartRes)) {
        $_SESSION['cartItems'][$safeOrderId][(int)$c['itemid']] = [
            'quantity' => (int)$c['quantity'],
            'preorder' => (int)$c['preorder']
        ];
    }
}
$cartItems = $_SESSION['cartItems'][$safeOrderId] ?? [];

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['updateCart'])) {
        foreach ($_POST['qty'] as $id => $newQty) {
            $id = (int)$id;
            $newQty = max(1, (int)$newQty);
            $priceRes = mysqli_query($con, "SELECT price FROM food_menu WHERE s='$id'");
            if ($row = mysqli_fetch_assoc($priceRes)) {
                $itemPrice = (float)$row['price'];
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
            $id = (int)$id;
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
        $itemid = (int)($_POST['food'] ?? 0);
        $qty = max(1, (int)($_POST['value'] ?? 1));
        if ($itemid === 0) {
            error_log("Add to cart error: Invalid itemid");
            exit("Invalid item ID");
        }
        $res = mysqli_query($con, "SELECT item, price FROM food_menu WHERE s='$itemid'");
        if ($row = mysqli_fetch_assoc($res)) {
            $itemName = $row['item'];
            $itemPrice = (float)$row['price'];
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
        $itemid = (int)($_POST['food'] ?? 0);
        $qty = max(1, (int)($_POST['value'] ?? 1));
        $preorder_date = date('Y-m-d');
        if (empty($safeOrderId) || $itemid === 0) {
            error_log("Preorder error: Invalid orderId=$safeOrderId or itemid=$itemid");
            exit("Invalid request");
        }
        $res = mysqli_query($con, "SELECT item, price FROM food_menu WHERE s='$itemid'");
        if ($row = mysqli_fetch_assoc($res)) {
            $itemName = $row['item'];
            $itemPrice = (float)$row['price'];
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
?>

<!-- Food Preview Modal -->
<div class="modal fade" id="foodModal" tabindex="-1" aria-labelledby="foodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:#000; color:#fff;">
                <h5 class="modal-title" id="foodModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <form action="" method="post">
                    <img id="foodModalImg" src="" alt="" style="max-width:100%; border-radius:10px; margin-bottom:10px;" loading="lazy" />
                    <input type="hidden" id="foodModalId" name="food" value="" />
                    <div>
                        <label>Quantity</label>
                        <input type="number" class="form-control" id="foodModalQty" name="value" min="1" value="1" />
                    </div>
                    <button type="submit" name="addtocart" class="btn-buya mt-2">Add To Cart</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .ter { background-color: #fff; padding: 0 10px; }
    .check { padding: 2%; font-size: 12px; width: 25%; }
    .check span { font-size: 13px; font-weight: 600; }
    .img { max-width: 50%; max-height: 50%; border-radius: 50%; cursor: pointer; }
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
    .btn-buya:hover { font-size: 12px !important; font-weight: 800; background: #000; }
    .form-control { height: 40px; border-radius: none !important; }
    .section-title h2::after {
        content: ""; position: absolute; display: block; width: 80px; background: none; bottom: 0; left: calc(2% - 25px);
    }
    .box { border-radius: 0px; }
    .pricing .box {
        padding: 20px 0 0;
        background: #f8f8f8;
        text-align: center;
        box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.12);
        border-radius: 0px;
        position: relative;
        overflow: hidden;
    }
</style>

<!-- ======= Pricing Section ======= -->
<section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none; border:none;">
    <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#000;">
            <h3 style="text-decoration:none; color:#000;">ORISHIRISHI<br><span style="font-size:14px;">Get refreshed with our food options</span></h3>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <p style="color:#FEBF01;">Food, Snacks, Drinks and much more..</p>
            </div>
            <div class="col-lg-12 col-md-12">
                <div class="box" data-aos="zoom-in" data-aos-delay="100">
                    <p>
                        <button onclick="showAllItems()" id="clocsButtonAll" value="all" type="button" class="btn-buya">ALL ITEMS</button>
                        <?php
                        $sql = "SELECT name FROM food_categories ORDER BY s";
                        $sql2 = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_array($sql2)) {
                            echo '<button onclick="showCategory(\'' . $row['name'] . '\')" id="clocsButton' . $row['name'] . '" value="' . $row['name'] . '" type="button" class="btn-buya">' . $row['name'] . '</button>';
                        }
                        ?>
                    </p>
                    <table id="results" width="95%" border="0" cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
                        <thead><tr><th></th><th></th><th></th></tr></thead>
                        <tbody>
                            <?php
                            $itemsPerPage = 20;
                            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
                            $offset = ($page - 1) * $itemsPerPage;
                            $sql = "SELECT s, item, price, file_name, quantity, type FROM food_menu ORDER BY item LIMIT $itemsPerPage OFFSET $offset";
                            $sql2 = mysqli_query($con, $sql);
                            $totalItems = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) as total FROM food_menu"))['total'];
                            $totalPages = ceil($totalItems / $itemsPerPage);

                            while ($row = mysqli_fetch_array($sql2)) {
                                $imageURL = 'https://chbluxuryempire.com/orishirishi/' . $row["file_name"];
                                $itemId = (int)$row['s'];
                                $quantity = (int)$row['quantity'];
                                $inCart = isset($cartItems[$itemId]);
                                $cartQty = $inCart ? $cartItems[$itemId]['quantity'] : 0;
                                ?>
                                <form action="" method="post">
                                    <tr class="ter mx-3 <?php echo $row['type']; ?>">
                                        <td class="check">
                                            <input type="radio" style="pointer-events:none;" value="<?php echo $row['s']; ?>" name="food" hidden />
                                            <img src="<?php echo $imageURL; ?>" class="img" loading="lazy" onclick="openModal('<?php echo $imageURL; ?>', '<?php echo addslashes($row['item']); ?>', '<?php echo $row['s']; ?>', '<?php echo $quantity; ?>')" />
                                        </td>
                                        <td class="check"><span><?php echo $row['item']; ?></span><br>&#8358;<?php echo $row['price']; ?>.00</td>
                                        <td class="check" style="font-size:14px;">
                                            <?php
                                            if ($quantity > 0) {
                                                if ($inCart) {
                                                    echo "<span class='badge bg-success' style='font-size:11px;' id='cartBadge$itemId'>In Cart: $cartQty</span><br><button type='button' onclick='openCartModal()' class='btn-buya'>Edit Cart</button>";
                                                } else {
                                                    echo "<input type='hidden' name='food' value='$itemId' />Quantity<input class='form-control' type='number' max='$quantity' min='1' name='value' value='1' /><br><button type='submit' name='addtocart' class='btn-buya'>Add To Cart</button>";
                                                }
                                            } else {
                                                echo "<p style='font-size:14px; color:#FFC700;'>Out Of Stock.</p><button type='button' class='btn-buya' onclick=\"openPreorderModal('" . addslashes($row['item']) . "', '$itemId', '{$row['price']}')\">Preorder</button>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </form>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="btn-wrap">
                        <a href="cart.php" name="submit" class="btn-buya">Proceed to Payment</a>
                    </div>
                    <?php if ($totalPages > 1) : ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                        <a class="page-link" href="foodpage.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <script>
        let debounceTimeout;
        function showCategory(category) {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                document.querySelectorAll('.ter').forEach(item => item.style.display = 'none');
                document.querySelectorAll('.' + category).forEach(item => item.style.display = 'table-row');
            }, 100);
        }
        function showAllItems() {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                document.querySelectorAll('.ter').forEach(item => item.style.display = 'table-row');
            }, 100);
        }
        function openModal(imgUrl, itemName, foodId, maxQty) {
            document.getElementById('foodModalImg').src = imgUrl;
            document.getElementById('foodModalLabel').innerText = itemName;
            document.getElementById('foodModalId').value = foodId;
            document.getElementById('foodModalQty').max = maxQty;
            new bootstrap.Modal(document.getElementById('foodModal')).show();
        }
        function addToCart(itemId) {
            const qty = document.getElementById('foodModalQty')?.value || 1;
            const orderid = "<?php echo $saloon; ?>";
            fetch("cart_api.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ action: "add", orderid: orderid, itemid: itemId, qty: qty })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "ok") {
                        const badge = document.getElementById("cartBadge" + itemId);
                        if (badge) {
                            let current = parseInt(badge.innerText.replace("In Cart: ", "")) || 0;
                            badge.innerText = "In Cart: " + (current + parseInt(qty));
                        } else {
                            location.reload();
                        }
                    } else {
                        console.error("Cart API error:", data.message);
                    }
                })
                .catch(err => console.error("Fetch error:", err));
        }
    </script>
<?php include "foodpageExtras.php"; ?>
<?php include "footer.php"; ?>