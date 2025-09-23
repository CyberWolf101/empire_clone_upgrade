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
              $sql = "SELECT * from saloon_orders where name!='' GROUP By name";
              $sql2 = mysqli_query($con, $sql);
              while ($row = mysqli_fetch_array($sql2)) {
                echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
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
          </div>

          <p style="text-align:center;">
            <input type='submit' name='pay' value='Complete Order' class='btn btn-primary'>
          </p>

          <script>
            function toggleAmountInput(checkboxId, inputId) {
              document.getElementById(checkboxId).addEventListener('change', function () {
                document.getElementById(inputId).style.display = this.checked ? 'block' : 'none';
              });
            }

            toggleAmountInput('payPos', 'payPosAmount');
            toggleAmountInput('payCash', 'payCashAmount');
            toggleAmountInput('payTransfer', 'payTransferAmount');
          </script>

        </form>
        </p>
      </div>
    </div>
  </div>







  <script>
    document.getElementById('customerType').addEventListener('change', function () {
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
    });


    const kitCheckboxYes = document.getElementById('kitCheckboxYes');
    const kitCheckboxNo = document.getElementById('kitCheckboxNo');
    const myForm = document.getElementById('myForm');

    kitCheckboxYes.addEventListener('change', function () {
      if (this.checked) {
        myForm.submit();
      }
    });

    kitCheckboxNo.addEventListener('change', function () {
      if (this.checked) {
        myForm.submit();
      }
    });
  </script>
</div>



<?php include "foodpay.php";
include "footer.php"; ?>