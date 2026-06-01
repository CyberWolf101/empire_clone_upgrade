<?php include "header.php";

/// Delete
if (isset($_GET['foodid'])) {
  $service_delete = $_GET['foodid'];
  $del = mysqli_query($con, "DELETE from refreshments where s='$service_delete'") or die('Could not connect: ' . mysqli_error($con));
  echo "<script> window.location.href = 'salooncheckout.php';</script>";
  exit(); // Make sure to exit the script after the alert
}




if (isset($_COOKIE['orderID'])) {
  $saloon = $_COOKIE['orderID'];


  $sql = "SELECT * from saloon_orders where id='$saloon' ";
  $sql2 = mysqli_query($con, $sql);
  while ($row = mysqli_fetch_array($sql2)) {
    $type = $row["bookingtype"];
    $username = $row["name"];
  }
} else {
  header("location:orderfood.php");
}



$today = date("Y-m-d");




//refreshments
$sam = "SELECT sum(totalprice) from refreshments where orderid='$saloon' ";
$sam2 = mysqli_query($con, $sam);
while ($row = mysqli_fetch_array($sam2))
  $total_items = $row[0];



//Grand Total
$total_all = $total_items;
$insert = mysqli_query($con, "UPDATE saloon_orders SET total_amount='$total_all' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h5 mb-0 text-gray-800">Order ID #<?php echo $saloon; ?></h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
  </ol>
</div>







<!-- Row -->
<div class="row justify-content-center mb-8">
  <div class="col-lg-12" style="margin-top:2%;">
    <center>
      <p><a href="orderfood.php" class='btn btn-sm btn-warning'>Add More To cart</a></p>
    </center>
  </div>

  <!-- Datatables -->
  <div class="col-lg-12">
    <div class="card mb-4">


      <?php
      $bot = "SELECT all* from refreshments where orderid='$saloon' ";
      $bot2 = mysqli_query($con, $bot);
      if (mysqli_affected_rows($con) > 0) {
      ?>




        <!-- Datatables -->

        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-warning">Refreshment Cart</h6>
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
              $sql = "SELECT * from refreshments where orderid='$saloon' ORDER BY s ASC";
              $sql2 = mysqli_query($con, $sql);
              while ($row = mysqli_fetch_array($sql2)) {

                echo "
                         <tr>
                         <td>" . $row['item'] . "</td>
                        <td>&#8358;" . $row['unitprice'] . "</td>	
                        <td>" . $row['quantity'] . "</td>
                        <td>&#8358;" . $row['totalprice'] . " </td>
                        <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this item (" . $row['item'] . ")?\");'>
		                <input type='text' name='foodid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete Item' class='btn btn-sm btn-danger' ></form></td>	
                        </tr>";
              }
              ?>

            </tbody>
          </table>


        <?php } ?>




        <center>
          <h4 class="font-weight-bold">GRAND TOTAL: &#8358;<?php echo $total_all; ?> </h4>
        </center>

        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-warning">Customer details</h6>
        </div>
        <form method="post">
          <p><select id="customerType" class="form-control" name="customertype" required>
              <option value="" selected>- Select Customer -</option>
              <option value="new">New Customer</option>
              <option value="old">Regular Customer</option>
              <option value="nil">Nil Customer</option>
            </select></p>



          <div id="newCustomerFields" style="display: none;">
            <input type="text" id="name" class="form-control" placeholder="Enter customer name" name="customername">
            <br>
            <input type="email" id="email" class="form-control" placeholder="Enter customer email(optional)"
              name="customeremail">
            <br>
            <input type="tel" id="phone" class="form-control" placeholder="Enter customer phone number"
              name="customerphone">
          </div>

          <!-- CUSTOMER DATABASE -->
          <div id="oldCustomerFields" style="display: none;">
            <select id="oldCustomer" name="customer" class="select2-single-placeholder form-control"
              style="width:100%;">
              <option value="" selected>- Select Regular Customer -</option>
              <?php
              $sql = "SELECT DISTINCT unique_id, name FROM customers WHERE name != '' ORDER BY name";
              $sql2 = mysqli_query($con, $sql);
              while ($row = mysqli_fetch_array($sql2)) {
                echo '<option value="' . $row['unique_id'] . '">' . htmlspecialchars($row['name']) . ' (' . htmlspecialchars($row['unique_id']) . ')</option>';
              }
              ?>
            </select>
          </div>
          <!-- CUSTOMER DATABASE -->




          <!-- ************** FORMER LOGIC ************** -->
          <!-- <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-warning">Select Payment Method</h6>
          </div> -->
          <!-- <p><input type="radio" name="method" value="pos" required /> <label> POS</label>
            <input type="radio" name="method" value="cash" required /> <label> Cash</label>
            <input type="radio" name="method" value="transfer" required /> <label> Bank Transfer</label>
          </p>

          <p style="text-align:center;"><input type='submit' name='pay' value='Complete Order' class='btn btn-primary'> -->
          <!-- ************** FORMER LOGIC ************** -->
          <script>
            function setCreditEligibilityStatus(isEligible, message) {
              var creditChk = document.getElementById('credit');
              var creditAmt = document.getElementById('creditAmount');
              var overridePanel = document.getElementById('creditOverridePanel');
              var overrideMessage = document.getElementById('creditOverrideMessage');
              var overrideFlag = document.getElementById('creditOverrideFlag');
              if (!creditChk || !overridePanel || !overrideMessage || !overrideFlag) return;

              if (isEligible) {
                creditChk.disabled = false;
                overridePanel.style.display = 'none';
                overrideMessage.textContent = '';
                overrideFlag.value = '0';
              } else {
                creditChk.checked = false;
                creditChk.disabled = true;
                if (creditAmt) {
                  creditAmt.style.display = 'none';
                }
                overridePanel.style.display = 'block';
                overrideMessage.textContent = message || 'Selected customer is not eligible for credit sales.';
                overrideFlag.value = '0';
              }
            }

            function checkCustomerEligibility(customerId) {
              if (!customerId) {
                setCreditEligibilityStatus(true);
                return;
              }
              fetch('customer_eligibility_check.php', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json'
                  },
                  body: JSON.stringify({ customer_id: customerId })
                })
                .then(function(res) {
                  return res.json();
                })
                .then(function(data) {
                  if (data.status === true) {
                    setCreditEligibilityStatus(data.eligible === true, data.eligible === false ? 'Selected customer is not eligible for credit sales.' : '');
                  } else {
                    setCreditEligibilityStatus(true);
                  }
                })
                .catch(function() {
                  setCreditEligibilityStatus(true);
                });
            }

            var oldCustomerSelect = document.getElementById('oldCustomer');
            if (oldCustomerSelect) {
              function handleOldCustomerChange() {
                checkCustomerEligibility(oldCustomerSelect.value);
              }
              oldCustomerSelect.addEventListener('change', handleOldCustomerChange);
              oldCustomerSelect.addEventListener('input', handleOldCustomerChange);
              if (window.jQuery) {
                $(oldCustomerSelect).on('select2:select select2:unselect', handleOldCustomerChange);
              }
            }
          </script>
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-warning">Select Payment Methods</h6>
          </div>

          <div id="paymentMethods">
            <div class="form-check">
              <input type="checkbox" id="payPos" name="payment[pos][enabled]" class="form-check-input">
              <label class="form-check-label" for="payPos">POS</label>
              <input type="number" step="0.01" min="0" class="form-control mt-1" id="payPosAmount"
                name="payment[pos][amount]" placeholder="Enter POS amount" style="display:none;">
            </div>

            <div class="form-check mt-2">
              <input type="checkbox" id="payCash" name="payment[cash][enabled]" class="form-check-input">
              <label class="form-check-label" for="payCash">Cash</label>
              <input type="number" step="0.01" min="0" class="form-control mt-1" id="payCashAmount"
                name="payment[cash][amount]" placeholder="Enter Cash amount" style="display:none;">
            </div>

            <div class="form-check mt-2">
              <input type="checkbox" id="payTransfer" name="payment[transfer][enabled]" class="form-check-input">
              <label class="form-check-label" for="payTransfer">Bank Transfer</label>
              <input type="number" step="0.01" min="0" class="form-control mt-1" id="payTransferAmount"
                name="payment[transfer][amount]" placeholder="Enter Transfer amount" style="display:none;">
            </div>
            <div class="form-check mt-2">
              <input type="checkbox" id="credit" name="payment[credit][enabled]" class="form-check-input">
              <label class="form-check-label" for="credit">Credit</label>
              <input type="number" step="0.01" min="0" class="form-control mt-1" id="creditAmount"
                name="payment[credit][amount]" placeholder="Enter Credit part payment(Leave empty if none)" style="display:none;">
            </div>
            <div id="creditOverridePanel" style="display:none; margin-top:10px;">
              <p class="mb-2 text-danger">This customer is not eligible for credit sales. Enter admin password to enable credit.</p>
              <input type="password" id="adminCreditPassword" name="admin_credit_password" class="form-control mb-2"
                placeholder="Admin password">
              <button type="button" id="enableCreditOverride" class="btn btn-sm btn-warning">Enable Credit</button>
              <div id="creditOverrideMessage" class="text-danger small mt-2"></div>
            </div>
            <input type="hidden" id="creditOverrideFlag" name="credit_override" value="0">
          </div>

          <p style="text-align:center;">
            <input type='submit' name='pay' value='Complete Order' class='btn btn-primary'>
          </p>

          <script>
            function toggleAmountInput(checkboxId, inputId, checkboxType) {
              document.getElementById(checkboxId).addEventListener('change', function() {
                document.getElementById(inputId).style.display = this.checked ? 'block' : 'none';
              });
            }

            toggleAmountInput('payPos', 'payPosAmount', 'POS');
            toggleAmountInput('payCash', 'payCashAmount', 'Cash');
            toggleAmountInput('payTransfer', 'payTransferAmount', "Transfer");
            toggleAmountInput('credit', 'creditAmount', "Credit");
          </script>

          <script>
            document.addEventListener('DOMContentLoaded', function() {
              var grandTotal = <?php echo json_encode($total_all); ?>;
              // Only make other methods disabled when Credit is selected.
              var others = [
                {chk: 'payPos', amt: 'payPosAmount', names: ['payment[pos][enabled]', 'payment[pos][amount]']},
                {chk: 'payCash', amt: 'payCashAmount', names: ['payment[cash][enabled]', 'payment[cash][amount]']},
                {chk: 'payTransfer', amt: 'payTransferAmount', names: ['payment[transfer][enabled]', 'payment[transfer][amount]']}
              ];

              var paymentContainer = document.getElementById('paymentMethods');
              if (!paymentContainer) return;
              var form = paymentContainer.closest('form') || document.querySelector('form');
              var creditChk = document.getElementById('credit');
              var creditAmt = document.getElementById('creditAmount');

              function addHidden(name, value) {
                var h = document.querySelector('input[type="hidden"][name="' + name + '"]');
                if (!h) { h = document.createElement('input'); h.type = 'hidden'; h.name = name; form.appendChild(h); }
                h.value = value;
              }

              function removeHidden(name) {
                var h = document.querySelector('input[type="hidden"][name="' + name + '"]');
                if (h) h.parentNode.removeChild(h);
              }

              function disableOthers() {
                others.forEach(function(o) {
                  var chk = document.getElementById(o.chk);
                  var amt = document.getElementById(o.amt);
                  if (chk) { chk.checked = false; chk.disabled = true; }
                  if (amt) { amt.style.display = 'none'; amt.value = '0'; amt.disabled = true; }
                  o.names.forEach(function(n) { addHidden(n, '0'); });
                });
              }

              function enableOthers() {
                others.forEach(function(o) {
                  var chk = document.getElementById(o.chk);
                  var amt = document.getElementById(o.amt);
                  if (chk) { chk.disabled = false; }
                  if (amt) { amt.disabled = false; amt.style.display = (chk && chk.checked) ? 'block' : 'none'; }
                  o.names.forEach(function(n) { removeHidden(n); });
                });
              }

              function validateAdminPassword(password) {
                return fetch('validate_admin_password.php', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json'
                  },
                  body: JSON.stringify({ password: password })
                })
                .then(function(res) {
                  return res.json();
                });
              }

              function applyAdminOverride() {
                var creditChk = document.getElementById('credit');
                var creditAmt = document.getElementById('creditAmount');
                var overrideFlag = document.getElementById('creditOverrideFlag');
                var overrideMessage = document.getElementById('creditOverrideMessage');
                var passwordInput = document.getElementById('adminCreditPassword');

                if (!passwordInput || !passwordInput.value.trim()) {
                  overrideMessage.textContent = 'Admin password is required to enable credit.';
                  return;
                }

                validateAdminPassword(passwordInput.value.trim())
                  .then(function(result) {
                    if (result.valid === true) {
                      overrideMessage.textContent = 'Admin password validated. Credit is enabled.';
                      if (creditChk) {
                        creditChk.disabled = false;
                        creditChk.checked = true;
                        creditChk.dispatchEvent(new Event('change'));
                      }
                      if (creditAmt) {
                        creditAmt.style.display = 'block';
                        creditAmt.disabled = false;
                        creditAmt.readOnly = false;
                      }
                      if (overrideFlag) {
                        overrideFlag.value = '1';
                      }
                    } else {
                      overrideMessage.textContent = result.message || 'Invalid admin password. Credit override denied.';
                      if (overrideFlag) {
                        overrideFlag.value = '0';
                      }
                    }
                  })
                  .catch(function() {
                    overrideMessage.textContent = 'Unable to validate admin password. Please try again.';
                    if (overrideFlag) {
                      overrideFlag.value = '0';
                    }
                  });
              }

              if (!creditChk) return;
              var overrideButton = document.getElementById('enableCreditOverride');

              // Do not pre-check credit; only act when user selects it
              creditChk.addEventListener('change', function() {
                if (this.checked) {
                  if (creditAmt) {
                    creditAmt.style.display = 'block';
                    creditAmt.disabled = false;
                    creditAmt.readOnly = false;
                    creditAmt.value = grandTotal;
                  }
                  disableOthers();
                  addHidden('payment[credit][enabled]', '1');
                } else {
                  if (creditAmt) {
                    creditAmt.style.display = 'none';
                    creditAmt.readOnly = false;
                    creditAmt.value = '';
                  }
                  enableOthers();
                  removeHidden('payment[credit][enabled]');
                }
              });

              if (overrideButton) {
                overrideButton.addEventListener('click', applyAdminOverride);
              }

              var oldCustomerSelect = document.getElementById('oldCustomer');
              if (oldCustomerSelect) {
                function handleOldCustomerChange() {
                  checkCustomerEligibility(oldCustomerSelect.value);
                }
                oldCustomerSelect.addEventListener('change', handleOldCustomerChange);
                oldCustomerSelect.addEventListener('input', handleOldCustomerChange);
                if (window.jQuery) {
                  $(oldCustomerSelect).on('select2:select select2:unselect', handleOldCustomerChange);
                }
                if (oldCustomerSelect.value) {
                  checkCustomerEligibility(oldCustomerSelect.value);
                }
              }

              var customerTypeSelect = document.getElementById('customerType');
              if (customerTypeSelect) {
                customerTypeSelect.addEventListener('change', function() {
                  if (this.value === 'old') {
                    if (oldCustomerSelect && oldCustomerSelect.value) {
                      checkCustomerEligibility(oldCustomerSelect.value);
                    } else {
                      setCreditEligibilityStatus(true);
                    }
                  } else {
                    setCreditEligibilityStatus(true);
                  }
                });
              }

              // Initialize amount fields visibility for non-credit checkboxes
              others.forEach(function(o) {
                var chk = document.getElementById(o.chk);
                var amt = document.getElementById(o.amt);
                if (chk && amt) { amt.style.display = chk.checked ? 'block' : 'none'; }
              });
            });
          </script>

        </form>
        </p>
        </div>
    </div>
  </div>







  <script>
    document.getElementById('customerType').addEventListener('change', function() {
      var selectedValue = this.value;
      var newCustomerFields = document.getElementById('newCustomerFields');
      var oldCustomerFields = document.getElementById('oldCustomerFields');

      if (selectedValue === 'new') {
        newCustomerFields.style.display = 'block';
        oldCustomerFields.style.display = 'none';
      } else if (selectedValue === 'old') {
        newCustomerFields.style.display = 'none';
        oldCustomerFields.style.display = 'block';
      } else if (selectedValue === 'nil') {
        newCustomerFields.style.display = 'none';
        oldCustomerFields.style.display = 'none';
      }

      if (selectedValue !== 'old' && typeof setCreditEligibilityStatus === 'function') {
        setCreditEligibilityStatus(true);
      }
    });


    const kitCheckboxYes = document.getElementById('kitCheckboxYes');
    const kitCheckboxNo = document.getElementById('kitCheckboxNo');
    const myForm = document.getElementById('myForm');

    kitCheckboxYes.addEventListener('change', function() {
      if (this.checked) {
        myForm.submit();
      }
    });

    kitCheckboxNo.addEventListener('change', function() {
      if (this.checked) {
        myForm.submit();
      }
    });
  </script>
</div>



<?php include "foodpay.php";
include "footer.php"; ?>