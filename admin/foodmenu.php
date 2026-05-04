<?php include "header.php";

// Delete
if (isset($_GET['categoryid'])) {
  $service_delete = $_GET['categoryid'];
  $del = mysqli_query($con, "DELETE from food_menu where s='$service_delete'") or die('Could not connect: ' . mysqli_error($con));
  echo "<script>alert('Item Deleted successfully!'); window.location.href = 'foodmenu.php';</script>";
  exit();
}
// Fetch all items from the database

$sql = "SELECT * FROM food_menu ORDER BY item ASC";
$sql2 = mysqli_query($con, $sql);

$items = [];
while ($row = mysqli_fetch_array($sql2)) {
  $items[] = [
    'item' => ($row['item']),
    'price' => ($row['price']),
    'type' => ($row['type']),
    'quantity' => ($row['quantity']),
    's' => ($row['s']),
    'sub_category' => ($row['sub_category'] ?? '')
  ];
}
// Convert items to JSON for JavaScript
$items_json = json_encode($items);
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">All Orishirishi</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Orishirish</li>
  </ol>
</div>

<!-- Row -->
<div class="row">
  <!-- Datatables -->
  <div class="col-lg-12" style="margin-top:2%;">
    <div class="card mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary"></h6>
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
          <div class="mr-3">
            <label for="itemsPerPage" style="font-size:11px;">Items per page:</label>
            <select id="itemsPerPage" class="form-control d-inline-block" style="width: auto;" onchange="changeItemsPerPage()">
              <option value="5">5</option>
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
          </div>
          <div>
            <input type="text" class="form-control" id="searchInput" placeholder="Search items...">
          </div>
        </div>
      </div>
      <div class="table-responsive p-3">
        <table class="table align-items-center table-flush text-primary" id="" style="font-size:15px;">
          <thead class="thead-light">
            <tr>
              <th>Item</th>
              <th>Price</th>
              <th>Type</th>
              <th>In-Stock</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <!-- Table rows will be populated by JavaScript -->
          </tbody>
          <tfoot>
            <tr>
              <th>Item</th>
              <th>Price</th>
              <th>Type</th>
              <th>In-Stock</th>
              <th></th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- Pagination -->
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="pagination">
          <!-- Pagination links will be populated by JavaScript -->
        </ul>
      </nav>
    </div>
  </div>
</div>

<script>
  const items = <?php echo $items_json; ?>;
  let currentPage = 1;
  let itemsPerPage = localStorage.getItem('itemsPerPage') ? parseInt(localStorage.getItem('itemsPerPage')) : 10;
  let filteredItems = items;

  function changeItemsPerPage() {
    const select = document.getElementById('itemsPerPage');
    itemsPerPage = parseInt(select.value);
    localStorage.setItem('itemsPerPage', itemsPerPage);
    currentPage = 1;
    renderTable();
    renderPagination();
  }

  function renderTable() {
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = '';
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const paginatedItems = filteredItems.slice(start, end);

    paginatedItems.forEach(item => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${item.item}</td>
        <td>${item.price}</td>
        <td>${item.type}</td>
        <td>${item.quantity}</td>
        <td>
          <form action='editfood.php' method='get'>
            <input type='hidden' name='category' value='${item.s}'>  
            <input type='submit' name='edit' value='Edit Item' class='btn btn-sm btn-primary'>
          </form>
        </td>	
        <td>
          <form action='' method='get' onsubmit='return confirm("Are you sure you want to delete this service (${item.item})?");'>
            <input type='hidden' name='categoryid' value='${item.s}'>  
            <input type='submit' name='delete' value='Delete Item' class='btn btn-sm btn-danger'>
          </form>
        </td>`;
      tableBody.appendChild(row);
    });
  }

  function renderPagination() {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    const totalPages = Math.ceil(filteredItems.length / itemsPerPage);

    // Previous button
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${currentPage <= 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `<a class="page-link" href="#" onclick="if (currentPage > 1) { currentPage--; renderTable(); renderPagination(); }">Previous</a>`;
    pagination.appendChild(prevLi);

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
      const li = document.createElement('li');
      li.className = `page-item ${currentPage === i ? 'active' : ''}`;
      li.innerHTML = `<a class="page-link" href="#" onclick="currentPage = ${i}; renderTable(); renderPagination();">${i}</a>`;
      pagination.appendChild(li);
    }

    // Next button
    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${currentPage >= totalPages ? 'disabled' : ''}`;
    nextLi.innerHTML = `<a class="page-link" href="#" onclick="if (currentPage < ${totalPages}) { currentPage++; renderTable(); renderPagination(); }">Next</a>`;
    pagination.appendChild(nextLi);
  }

  function filterItems() {
    const filter = document.getElementById('searchInput').value.toLowerCase();
    filteredItems = items.filter(item =>
      item.item.toLowerCase().includes(filter) ||
      item.type.toLowerCase().includes(filter)
    );
    currentPage = 1; // Reset to first page on filter change
    renderTable();
    renderPagination();
  }

  document.addEventListener('DOMContentLoaded', () => {
    // Set items per page from localStorage
    const savedItemsPerPage = localStorage.getItem('itemsPerPage');
    if (savedItemsPerPage) {
      document.getElementById('itemsPerPage').value = savedItemsPerPage;
      itemsPerPage = parseInt(savedItemsPerPage);
    }

    // Live search filter
    document.getElementById('searchInput').addEventListener('input', filterItems);

    // Initial render
    renderTable();
    renderPagination();
  });
</script>

<?php include "footer.php"; ?>