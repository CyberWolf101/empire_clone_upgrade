<?php include "header.php";

// Fetch categories
$category_sql = "SELECT name FROM food_categories ORDER BY name ASC";
$category_result = mysqli_query($con, $category_sql);

$categories = [];
while ($row = mysqli_fetch_array($category_result)) {
    $categories[] = strtolower($row['name']);
}

// Fetch all items
$sql = "SELECT * FROM food_menu ORDER BY item ASC";
$sql2 = mysqli_query($con, $sql);

$items = [];
while ($row = mysqli_fetch_array($sql2)) {
    $items[] = [
        'item' => htmlspecialchars($row['item']),
        'price' => htmlspecialchars($row['price']),
        'type' => strtolower(htmlspecialchars($row['type'])),
        'quantity' => htmlspecialchars($row['quantity']),
        's' => htmlspecialchars($row['s'])
    ];
}

$items_json = json_encode($items);
$categories_json = json_encode($categories);
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Deficient Items</h1>
</div>

<div class="row">
    <div class="col-lg-12" style="margin-top:2%;">
        <div class="card mb-4">
            <div class="card-header py-3">
                <div
                    style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: space-between;">

                    <!-- Items per page -->
                    <div>
                        <label style="font-size:11px;">Items per page:</label>
                        <select id="itemsPerPage" class="form-control" onchange="changeItemsPerPage()">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    <!-- Stock filter -->
                    <div>
                        <label style="font-size:11px;">Stock Filter:</label>
                        <select id="stockFilter" class="form-control" onchange="applyStockFilter()">
                            <option value="all">All</option>
                            <option value="0" style="color:red;">0 (Out of stock)</option>
                            <option value="1-5" style="color:orange;">1 - 5 (Low)</option>
                            <option value="6-10" style="color:green;">6 - 10 (Okay)</option>
                            <option value="custom" style="color:purple;">Custom Range</option>
                        </select>
                    </div>

                    <!-- Custom range -->
                    <div id="customRangeBox" style="display:none;">
                        <input type="number" id="minStock" placeholder="Min" class="form-control mb-1">
                        <input type="number" id="maxStock" placeholder="Max" class="form-control mb-1">
                        <button class="btn btn-sm btn-primary" onclick="applyStockFilter()">Apply</button>
                    </div>

                    <!-- Search -->
                    <div>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search items...">
                    </div>

                </div>

                <!-- Scrollable Category Buttons -->
                <div id="categoryButtons"
                    style="margin-top:10px; display:flex; overflow-x:auto; gap:5px; padding-bottom:5px; white-space: nowrap;">
                    <button class="btn btn-sm btn-outline-primary active" onclick="selectCategory('all')">ALL</button>
                </div>
            </div>

            <div class="table-responsive p-3">
                <table class="table table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Type</th>
                            <th>In-Stock</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>

            <nav>
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>
        </div>
    </div>
</div>

<script>
    const items = <?php echo $items_json; ?>;
    const categories = <?php echo $categories_json; ?>;

    let currentPage = 1;
    let itemsPerPage = 50;
    let filteredItems = items;
    let stockFilterValue = "0";
    let categoryFilterValue = "all";

    // INIT
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('itemsPerPage').value = itemsPerPage;
        document.getElementById('stockFilter').value = stockFilterValue;
        document.getElementById('searchInput').addEventListener('input', filterItems);

        // Add category buttons
        const catContainer = document.getElementById('categoryButtons');
        categories.reverse().forEach(cat => {
            const btn = document.createElement('button');
            btn.className = "btn btn-sm btn-outline-primary";
            btn.textContent = cat.toUpperCase();
            btn.onclick = () => selectCategory(cat);
            catContainer.appendChild(btn);
        });

        filterItems();
    });

    // Category button click
    function selectCategory(category) {
        categoryFilterValue = category;
        currentPage = 1;

        // Highlight active button
        document.querySelectorAll('#categoryButtons button').forEach(btn => {
            btn.classList.remove('active');
            if (btn.textContent.toLowerCase() === category.toLowerCase()) {
                btn.classList.add('active');
            }
        });

        filterItems();
    }

    // Change items per page
    function changeItemsPerPage() {
        itemsPerPage = parseInt(document.getElementById('itemsPerPage').value);
        currentPage = 1;
        renderTable();
        renderPagination();
    }

    // Stock filter
    function applyStockFilter() {
        stockFilterValue = document.getElementById('stockFilter').value;
        document.getElementById('customRangeBox').style.display =
            stockFilterValue === "custom" ? "block" : "none";
        filterItems();
    }

    // Main filter
    function filterItems() {
        const search = document.getElementById('searchInput').value.toLowerCase();

        filteredItems = items.filter(item => {
            const qty = parseInt(item.quantity);
            let matchesSearch = item.item.toLowerCase().includes(search) || item.type.toLowerCase().includes(search);

            let matchesStock = true;
            if (stockFilterValue === "0") matchesStock = qty === 0;
            else if (stockFilterValue === "1-5") matchesStock = qty >= 1 && qty <= 5;
            else if (stockFilterValue === "6-10") matchesStock = qty >= 6 && qty <= 10;
            else if (stockFilterValue === "custom") {
                const min = parseInt(document.getElementById('minStock').value) || 0;
                const max = parseInt(document.getElementById('maxStock').value) || Infinity;
                matchesStock = qty >= min && qty <= max;
            }

            let matchesCategory = categoryFilterValue === 'all' || item.type === categoryFilterValue;

            return matchesSearch && matchesStock && matchesCategory;
        });

        currentPage = 1;
        renderTable();
        renderPagination();
    }

    // Stock color
    function getStockColor(qty) {
        qty = parseInt(qty);
        if (qty === 0) return "red";
        if (qty >= 1 && qty <= 5) return "orange";
        if (qty >= 6 && qty <= 10) return "green";
        return "purple";
    }

    // Render table
    function renderTable() {
        const tableBody = document.getElementById('tableBody');
        tableBody.innerHTML = '';
        const start = (currentPage - 1) * itemsPerPage;
        const paginatedItems = filteredItems.slice(start, start + itemsPerPage);

        paginatedItems.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
      <td>${item.item}</td>
      <td>${item.price}</td>
      <td>${item.type}</td>
      <td style="font-weight:bold; color:${getStockColor(item.quantity)}">${item.quantity}</td>
    `;
            tableBody.appendChild(row);
        });
    }

    // Pagination
    function renderPagination() {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';
        const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${currentPage === i ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            li.onclick = () => { currentPage = i; renderTable(); renderPagination(); };
            pagination.appendChild(li);
        }
    }
</script>

<?php include "footer.php"; ?>