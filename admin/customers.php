<?php
include "header.php";
include "../mailer.php";

$email_status = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
  $to = $_POST['to'] ?? '';
  $subject = $_POST['subject'] ?? '';
  $message = $_POST['message'] ?? '';

  if ($to && $subject && $message) {
    if (sendEmail($to, $subject, $message)) {
      $email_status = "<p style='color:green;'>✅ Email sent successfully.</p>";
    } else {
      $email_status = "<p style='color:red;'>❌ Failed to send email.</p>";
    }
  } else {
    $email_status = "<p style='color:red;'>❌ Please provide a valid email, subject, and message.</p>";
  }
}

// Determine filter from GET parameter
$filter = $_GET['filter'] ?? 'name'; // Default to sorting by name
$valid_filters = [
  'name' => ['column' => 'name', 'direction' => 'ASC'],
  'highest_spent' => ['column' => 'total_spent', 'direction' => 'DESC'],
  'lowest_spent' => ['column' => 'total_spent', 'direction' => 'ASC'],
  'highest_orders' => ['column' => 'order_count', 'direction' => 'DESC'],
  'lowest_orders' => ['column' => 'order_count', 'direction' => 'ASC'],
  'last_registered' => ['column' => 'first_order_date', 'direction' => 'DESC'],
  'first_registered' => ['column' => 'first_order_date', 'direction' => 'ASC']
];

$order_by = $valid_filters[$filter] ?? $valid_filters['name'];

// Fetch unique customers with aggregated data
$sql = "SELECT 
            s.name, 
            s.email, 
            s.phone, 
            COALESCE(SUM(r.totalprice), 0) as total_spent, 
            COUNT(DISTINCT s.id) as order_count, 
            MIN(s.date) as first_order_date
        FROM saloon_orders s 
        LEFT JOIN refreshments r ON s.id = r.orderid";
$sql_result = mysqli_query($con, $sql) or die('Database error: ' . mysqli_error($con));
$customers = [];
$customersToQuery = [];
while ($row = mysqli_fetch_assoc($sql_result)) {
  $customersToQuery[] = $row;
}
if (count($customersToQuery) > 0) {
  foreach ($customersToQuery as $customerToQuery) {
    $name = $customerToQuery["name"];
    $email = $customerToQuery["email"];
    $phone = $customerToQuery["phone"];
    $total_spent = $customerToQuery["total_spent"];
    $order_count = $customerToQuery["order_count"];
    $first_order_date = $customerToQuery["first_order_date"];
    $searchIfExists = mysqli_query($con, "SELECT * FROM customers WHERE email = '$email'");
    if (!mysqli_fetch_assoc($searchIfExists) > 0) {
      $addCustomerQuery = "INSERT INTO
     customers(name,email,phone,total_spent,order_count,first_order_date) 
     VALUES ('$name','$email','$phone','$total_spent','$order_count','$first_order_date')";
      mysqli_query($con, $addCustomerQuery);
    }
  }
}
$selectCustomersSql = "SELECT * FROM customers
        WHERE name != '' 
        GROUP BY name, email, phone 
        ORDER BY {$order_by['column']} {$order_by['direction']}";
$selectResult = mysqli_query($con, $selectCustomersSql);
while ($result = mysqli_fetch_assoc($selectResult)) {
  $customers[] = $result;
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h5 mb-0 text-gray-800">Customer Database</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Customer Database</li>
  </ol>
</div>

<div class="col-xl-12 col-lg-12 mb-4">
  <center>
    <div style="text-align:left;">
      <p>View customers and send emails from the Orishirishi customer database.</p>
      <?php if ($email_status): ?>
        <?= $email_status ?>
      <?php endif; ?>
      <!-- Filter Dropdown -->
      <div class="mb-3">
        <label for="filterSelect" class="form-label">Filter by:</label>
        <select id="filterSelect" class="form-control form-select" style="width: 200px;" onchange="applyFilter()">
          <option value="name" <?= $filter === 'name' ? 'selected' : '' ?>>Sort by Name</option>
          <option value="highest_spent" <?= $filter === 'highest_spent' ? 'selected' : '' ?>>Highest Spent</option>
          <option value="lowest_spent" <?= $filter === 'lowest_spent' ? 'selected' : '' ?>>Lowest Spent</option>
          <option value="highest_orders" <?= $filter === 'highest_orders' ? 'selected' : '' ?>>Highest Orders</option>
          <option value="lowest_orders" <?= $filter === 'lowest_orders' ? 'selected' : '' ?>>Lowest Orders</option>
          <option value="last_registered" <?= $filter === 'last_registered' ? 'selected' : '' ?>>Last Registered</option>
          <option value="first_registered" <?= $filter === 'first_registered' ? 'selected' : '' ?>>First Registered</option>
        </select>
      </div>
      <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-warning">Customers</h6>
        </div>
        <div class="table-responsive p-3">
          <table class="table align-items-center table-flush text-primary">
            <thead class="thead-light">
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Unique Id</th>
                <th>Total Spent</th>
                <th>Orders</th>
                <th>First Order</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($customers) > 0): ?>
                <?php foreach ($customers as $customer): ?>
                  <tr>
                    <td><?= htmlspecialchars($customer['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($customer['email'] ?: '-', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($customer['phone'] ?: '-', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($customer['unique_id'] ?: '-', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>&#8358;<?= number_format($customer['total_spent'], 2) ?></td>
                    <td><?= htmlspecialchars($customer['order_count'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($customer['first_order_date'] ? date('Y-m-d', strtotime($customer['first_order_date'])) : '-', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                      <div class='dropdown'>
                        <button class='btn btn-sm btn-primary dropdown-toggle' type='button' id='dropdownMenu_$id' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                          Action
                        </button>
                        <div class='dropdown-menu' aria-labelledby='dropdownMenu_$id'>
                          <a data-bs-toggle="modal" data-bs-target="#emailModal"
                            data-email="<?= htmlspecialchars($customer['email'] ?: '', ENT_QUOTES, 'UTF-8') ?>"
                            data-name="<?= htmlspecialchars($customer['name'], ENT_QUOTES, 'UTF-8') ?>" href="" class="dropdown-item">
                            Send Email
                          </a>
                          <a href="editcustomer.php?customer_unique_id=<?= htmlspecialchars($customer["unique_id"]) ?>" class="dropdown-item">Edit customer</a>
                        </div>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center">No customers found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </center>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="emailModalLabel">Send Email to Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" id="email-form">
        <div class="modal-body">
          <div class="form-group">
            <label for="emailInput">Email</label>
            <input type="email" id="emailInput" name="to" class="form-control" readonly required>
            <small id="noEmailWarning" class="form-text text-danger" style="display:none;">No email available for this customer.</small>
          </div>
          <div class="form-group">
            <label for="subjectInput">Subject</label>
            <input type="text" id="subjectInput" name="subject" class="form-control" placeholder="Enter subject" required>
          </div>
          <div class="form-group">
            <label for="messageInput">Message</label>
            <textarea id="messageInput" name="message" class="form-control" rows="5" placeholder="Enter message" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="sendEmailBtn" name="send_email" class="btn btn-primary">Send Email</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Filter application
  function applyFilter() {
    const filterSelect = document.getElementById('filterSelect');
    const filterValue = filterSelect.value;
    console.log('Applying filter:', filterValue);
    window.location.href = '?filter=' + encodeURIComponent(filterValue);
  }

  // Update modal fields when opened
  document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing email modal');
    const emailModal = document.getElementById('emailModal');
    emailModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const email = button.dataset.email || '';
      const name = button.dataset.name || 'Customer';

      console.log('Opening modal - Customer Name:', name, 'Email:', email);

      const emailInput = emailModal.querySelector('#emailInput');
      const noEmailWarning = emailModal.querySelector('#noEmailWarning');
      const sendEmailBtn = emailModal.querySelector('#sendEmailBtn');
      const modalTitle = emailModal.querySelector('#emailModalLabel');

      emailInput.value = email || '';
      emailInput.readOnly = true;
      noEmailWarning.style.display = email ? 'none' : 'block';
      sendEmailBtn.disabled = !email;
      modalTitle.textContent = 'Send Email to ' + name;

      emailModal.querySelector('#subjectInput').value = '';
      emailModal.querySelector('#messageInput').value = '';
    });

    emailModal.addEventListener('hidden.bs.modal', function() {
      console.log('Modal closed - resetting fields');
      const emailInput = emailModal.querySelector('#emailInput');
      const noEmailWarning = emailModal.querySelector('#noEmailWarning');
      const sendEmailBtn = emailModal.querySelector('#sendEmailBtn');
      const subjectInput = emailModal.querySelector('#subjectInput');
      const messageInput = emailModal.querySelector('#messageInput');

      emailInput.value = '';
      emailInput.readOnly = true;
      noEmailWarning.style.display = 'none';
      sendEmailBtn.disabled = false;
      subjectInput.value = '';
      messageInput.value = '';
    });
  });
</script>

<?php include "footer.php"; ?>