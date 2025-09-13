<?php
include "header.php";
include "food_page_logic.php";
?>

<!-- Food Preview Modal -->
<div class="modal fade" id="foodModal" tabindex="-1" aria-labelledby="foodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mx-3">
        <div class="modal-content">
            <div class="modal-header" style="background:#000; color:#fff;">
                <h5 class="modal-title" id="foodModalLabel"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="foodModalImg" src="" alt=""
                    style="max-height:300px; max-width:100%; border-radius:10px; margin-bottom:10px;" loading="lazy" />
                <div id="foodModalContent"></div>
            </div>
        </div>
    </div>
</div>

<?php include "food_page_styles.php"; ?>

<!-- ======= Pricing Section ======= -->
<a class="go_home m-2 mt-3" href="index.php">
    <i class="fa fa-fw fa-home"></i>
</a>
<section id="pricing" class="pricing section-bg" style="background-color:none; border:none;">
    <div class="container" style="width:100%; margin:auto;">
        <div class="p-2 small fw-bold" style="position: absolute; right: 60px; letter-spacing: 1px;">
            <a href="event_orders.php" style="color: #FEBF01; display: flex;"> Create event order <div
                    style="margin-left:3px" class="arrow">-></div></a>
        </div>
        <div class="section-title" style="color:#000;">
            <h3 style="text-decoration:none; color:#000">ORISHIRISHI<br>
                <span style="font-size:14px;">Get refreshed with our food options</span>
            </h3>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-4">
                <p style="color:#FEBF01;">Food, Snacks, Drinks and much more..</p>
            </div>
            <div class="col-lg-12 col-md-12">
                <div class="box" data-aos="zoom-in" data-aos-delay="100">
                    <!-- Search Bar -->
                    <div class="search-container d-flex flex-wrap align-items-center gap-2">
                        <input type="text" id="searchInput" class="search-input form-control"
                            placeholder="Search food items..." style="flex: 1; min-width: 200px;">
                        <button type="button" class="btn-buya" onclick="triggerSearch()">Search</button>
                        <button type="button" class="btn-reset" onclick="resetSearch()">Reset</button>
                        <div id="searchSuggestions" class="search-suggestions w-100"></div>
                    </div>

                    <p>
                        <button onclick="showCategory('all')" id="clocsButtonAll" value="all" type="button"
                            class="btn-buya">ALL ITEMS</button>
                        <?php
                        $sql = "SELECT name FROM food_categories ORDER BY s";
                        $sql2 = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_array($sql2)) {
                            $category_name = strtolower($row['name']);
                            echo '<button onclick="showCategory(\'' . $category_name . '\')" id="clocsButton' . $category_name . '" value="' . $category_name . '" type="button" class="btn-buya">' . $row['name'] . '</button>';
                        }
                        ?>
                    </p>

                    <table id="results" width="95%" border="0" cellspacing='0'
                        style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $itemsPerPage = 20;
                            $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
                            $offset = ($page - 1) * $itemsPerPage;
                            $category = isset($_GET['category']) ? mysqli_real_escape_string($con, $_GET['category']) : 'all';
                            $search = isset($_GET['search']) ? mysqli_real_escape_string($con, trim($_GET['search'])) : '';

                            // Build the SQL query with category and search filter
                            $sql = "SELECT s, item, price, file_name, quantity, type FROM food_menu WHERE 1=1";
                            $countSql = "SELECT COUNT(*) as total FROM food_menu WHERE 1=1";
                            if (!empty($search)) {
                                $sql .= " AND item LIKE '%$search%'";
                                $countSql .= " AND item LIKE '%$search%'";
                            }
                            if ($category !== 'all' && empty($search)) {
                                $sql .= " AND type='$category'";
                                $countSql .= " AND type='$category'";
                            }
                            $sql .= " ORDER BY item LIMIT $itemsPerPage OFFSET $offset";

                            $sql2 = mysqli_query($con, $sql);
                            if (!$sql2) {
                                error_log("Query failed: " . mysqli_error($con) . " | Query: $sql");
                            }
                            $totalItems = mysqli_fetch_array(mysqli_query($con, $countSql))['total'];
                            $totalPages = ceil($totalItems / $itemsPerPage);

                            while ($row = mysqli_fetch_array($sql2)) {
                                $imageURL = 'https://chbluxuryempire.com/orishirishi/' . $row["file_name"];
                                $itemId = (int) $row['s'];
                                $quantity = (int) $row['quantity'];
                                $inCart = isset($cartItems[$itemId]);
                                $cartQty = $inCart ? $cartItems[$itemId]['quantity'] : 0;
                                ?>
                                <form action="" method="post">
                                    <tr class="ter mx-3 <?php echo strtolower($row['type']); ?>">
                                        <td class="check">
                                            <input type="radio" style="pointer-events:none;"
                                                value="<?php echo $row['s']; ?>" name="food" hidden />
                                            <img src="<?php echo $imageURL; ?>" class="img" loading="lazy"
                                                style="height:50px; width:50px; border:2px solid #FEBF01; padding: 1px;"
                                                onclick="openModal('<?php echo $imageURL; ?>', '<?php echo addslashes($row['item']); ?>', '<?php echo $row['s']; ?>', '<?php echo $quantity; ?>', '<?php echo $row['price']; ?>')" />
                                        </td>
                                        <td class="check">
                                            <span><?php echo $row['item']; ?></span><br>&#8358;<?php echo $row['price']; ?>.00
                                        </td>
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
                    <?php if ($totalPages > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                        <a class="page-link"
                                            href="foodpage.php?category=<?php echo urlencode($category); ?>&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
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
        const search = document.getElementById('searchInput').value;
        window.location.href = 'foodpage.php?category=' + encodeURIComponent(category) + '&search=' + encodeURIComponent(search) + '&page=1';
    }

    function showAllItems() {
        const search = document.getElementById('searchInput').value;
        window.location.href = 'foodpage.php?category=all&search=' + encodeURIComponent(search) + '&page=1';
    }

    function openModal(imgUrl, itemName, foodId, maxQty, itemPrice) {
        document.getElementById('foodModalImg').src = imgUrl;
        document.getElementById('foodModalLabel').innerText = itemName;
        const contentDiv = document.getElementById('foodModalContent');
        if (maxQty > 0) {
            contentDiv.innerHTML = `
                <form action="" method="post">
                    <input type="hidden" id="foodModalId" name="food" value="${foodId}" />
                    <div>
                        <label>Quantity</label>
                        <input type="number" class="form-control" id="foodModalQty" name="value" min="1" max="${maxQty}" value="1" />
                    </div>
                    <button type="submit" name="addtocart" class="btn-buya mt-2">Add To Cart</button>
                </form>`;
        } else {
            contentDiv.innerHTML = `
                <form method="post">
                    <p>This item is out of stock. You can preorder it now!</p>
                    <input type="hidden" name="food" value="${foodId}" />
                    <label>Quantity</label>
                    <input type="number" name="value" id="preorderQty" class="form-control" value="1" min="1" oninput="updateModalTotalPrice(${itemPrice})" />
                    <p>Unit Price: ₦<span id="modalUnitPrice">${itemPrice}</span></p>
                    <p>Total Price: ₦<span id="modalTotalPrice">${itemPrice}</span></p>
                    <button type="submit" name="preorder" class="btn-buya mt-2">Confirm Preorder</button>
                </form>`;
        }
        new bootstrap.Modal(document.getElementById('foodModal')).show();
    }

    function updateModalTotalPrice(price) {
        const qty = document.getElementById('preorderQty').value;
        document.getElementById('modalTotalPrice').innerText = (qty * price).toFixed(2);
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
                    }
                    let cartBtn = document.getElementById("cartFloatingBtn");
                    let badgeSpan = cartBtn.querySelector(".badge");
                    let currentCount = badgeSpan ? parseInt(badgeSpan.innerText) : 0;
                    let newCount = currentCount + parseInt(qty);
                    if (badgeSpan) {
                        badgeSpan.innerText = newCount;
                    } else {
                        let newBadge = document.createElement("span");
                        newBadge.className = "badge bg-danger";
                        newBadge.style.position = "absolute";
                        newBadge.style.top = "-10px";
                        newBadge.style.right = "-10px";
                        newBadge.style.fontSize = "10px";
                        newBadge.style.borderRadius = "50%";
                        newBadge.style.padding = "4px 8px";
                        newBadge.innerText = newCount;
                        cartBtn.appendChild(newBadge);
                    }
                } else {
                    console.error("Cart API error:", data.message);
                }
            })
            .catch(err => console.error("Fetch error:", err));
    }

    // Search and Suggestions Functionality
    document.getElementById('searchInput').addEventListener('input', function (e) {
        clearTimeout(debounceTimeout);
        const query = e.target.value.trim();
        const suggestionsDiv = document.getElementById('searchSuggestions');

        if (query.length > 0) {
            debounceTimeout = setTimeout(() => {
                fetchSuggestions(query, suggestionsDiv);
            }, 300);
        } else {
            suggestionsDiv.style.display = 'none';
            suggestionsDiv.innerHTML = '';
        }
    });

    function fetchSuggestions(query, suggestionsDiv) {
        fetch('search_api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ search: query })
        })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok: ' + res.status);
                }
                return res.json();
            })
            .then(data => {
                suggestionsDiv.innerHTML = '';
                if (data.length > 0) {
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
            })
            .catch(err => {
                console.error('Fetch suggestions error:', err);
                suggestionsDiv.innerHTML = '<div>Error loading suggestions. Please try again.</div>';
                suggestionsDiv.style.display = 'block';
            });
    }

    function triggerSearch() {
        const query = document.getElementById('searchInput').value.trim();
        window.location.href = 'foodpage.php?search=' + encodeURIComponent(query) + '&page=1';
    }

    function resetSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('searchSuggestions').style.display = 'none';
        window.location.href = 'foodpage.php?category=all&page=1';
    }
</script>
<?php include "foodpageExtras.php"; ?>
<?php include "footer.php"; ?>