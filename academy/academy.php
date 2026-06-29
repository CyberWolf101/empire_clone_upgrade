<?php include "header.php";

if (isset($_GET['category'])) {
  $category = mysqli_real_escape_string($con, $_GET['category']);
} else {
  header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
  exit();
}

$sql = "SELECT * from training where id='$category' ";
$sql2 = mysqli_query($con, $sql);
$name = "";
$describe = "";
while ($row = mysqli_fetch_array($sql2)) {
  $name = $row["name"];
  $describe = $row["description"];
}
?>

<script>
  function check() {
    document.getElementById('results').scrollIntoView();
  }
</script>
<style>
  .ter {
    background-color: #fff;
    padding: 0 10px;
    cursor: pointer;
  }

  .check {
    padding: 2%;
    font-size: 12px;
    width: 25%;
  }

  .check span {
    font-size: 13px;
    font-weight: 500;
  }

  .img {
    width: 30%;
    height: auto;
    border-radius: 50%;
  }

  .submitn {
    background: #FFC700;
    color: #fff;
    border-radius: 5px;
    padding: 10px;
    font-size: 10px;
    font-weight: 600;
    outline: none;
    border: none;
  }

  .submitn:hover {
    background: #000000;
    color: #fff;
  }

  .btn-buya {
    display: inline-block;
    padding: 10px;
    border: none;
    color: #fff;
    text-align: center;
    font-size: 14px;
    text-transform: uppercase;
    font-family: 'Poppins', Open sans;
    font-weight: 800;
    background: #FFC700;
    margin-bottom: 20px;
    width: 300px;
    text-decoration: none;
  }

  .btn-buya:hover {
    background: #000000;
    color: #fff;
  }

  .advert {
    background: #FFC700;
    width: 100%;
    height: 40px;
    font-weight: 800;
    font-size: 14px;
    color: #fff;
    padding: 10px;
  }

  #clocs,
  #cloch {
    display: none;
  }
</style>

<section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none; border:none;">
  <div class="container" style="width:100%; margin:auto; ">
    <div class="section-title" style="color:#FFFFFF;">
      <?php if (isset($count_services) && $count_services > 0) { ?><p style="float:right;"><a class="btn-buya" href="cart.php">VIEW CART</a></p><?php  } ?>
      <h2><?php echo htmlspecialchars($name); ?></h2>
    </div>

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <p style="color:#FEBF01;">Choose a duration</p>
      </div>

      <div class="col-lg-12 col-md-12">
        <div class="box">
          <div id="main">
            <form method='post' class='user-form'>
              <table id="results" width="95%" border="0" cellspacing='0' style="border-collapse:separate; margin:auto; border-spacing:0px 10px; padding:10px 0px;">
                <tbody>
                  <?php
                  $sql = "SELECT * FROM durations where category='$category'";
                  $sql2 = mysqli_query($con, $sql);
                  while ($row = mysqli_fetch_array($sql2)) {
                    echo '<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click();\' >
                    <td class="check"><input type="radio" style="pointer-events:none;" value="' . $row['s'] . '" name="item" required/></td>
                    <td class="check"><span>&nbsp; &nbsp; &nbsp; &nbsp; ' . htmlspecialchars($row['duration']);
                  ?>
                    <?php
                    $unitOutputs = [
                      ["short_form" => "d", "full_form" => "Day(s)"],
                      ["short_form" => "w", "full_form" => "Weeks(s)"],
                      ["short_form" => "m", "full_form" => "Months(s)"],
                      ["short_form" => "y", "full_form" => "Year(s)"]
                    ];
                    foreach ($unitOutputs as $output) {
                      if ($row['duration_unit'] == $output["short_form"]) {
                        echo " " . $output["full_form"];
                      }
                    }
                    ?>
                  <?php
                    echo '</span></td>
                    <td class="check" style="font-size:16px">&#8358;' . number_format($row['price'], 2) . '</td>
                    </tr>';
                  }
                  ?>
                </tbody>
              </table>
          </div>

          <?php
          $discountTOADD = "SELECT discount_added FROM training WHERE id = '$category'";
          $response = mysqli_query($con, $discountTOADD);
          $toAdd = 0;
          if ($rrrrrow = mysqli_fetch_assoc($response)) {
            $toAdd = (int)$rrrrrow["discount_added"];
          }
          ?>
          <p class="ms-4 mt-4"><strong>Select Training items</strong></p>
          <?php if ($toAdd > 0) { ?>
            <p class="ms-4" style="color: green; font-weight: 600;">Purchase all items to get <?= $toAdd ?>% discount on total bundle!</p>
          <?php } ?>

          <table width="95%" border="0" cellspacing='0' style="border-collapse:separate; margin:auto; border-spacing:0px 10px; padding:10px 0px;">
            <tbody>
              <?php
              $sql = "SELECT * FROM training_items where training_id='$category'";
              $sql2 = mysqli_query($con, $sql);
              while ($row = mysqli_fetch_array($sql2)) {
                echo '<tr class="ter mx-3" onclick=\'this.querySelector("input[type=checkbox]").click();\' >
                <td class="check"><input type="checkbox" onclick="event.stopPropagation();" value="' . htmlspecialchars($row['name']) . '" name="training_item[]" /></td>
                <td class="check"><span>&nbsp; &nbsp; &nbsp; &nbsp; ' . htmlspecialchars($row['name']) . '</span></td>
                <td class="check" style="font-size:16px">&#8358;' . number_format($row['price'], 2) . '</td>
                </tr>';
              }
              ?>
            </tbody>
          </table>

          <div class="btn-wrap" align="center" style="margin-bottom:40px; margin-top: 20px;">
            <button type="submit" name="submit" value="add" class="btn-buya">NEXT</button>
            </form>
          </div>
        </div>
      </div>
</section>

<?php
if (isset($_POST['submit'])) {
  $itemID = mysqli_real_escape_string($con, $_POST['item']);
  $trainingItems = $_POST['training_item'] ?? [];

  // Get base training details
  $itemprice = 0;
  $trainingname = "";
  $sqk = "SELECT * FROM durations WHERE s= '$itemID'";
  $sqlp = mysqli_query($con, $sqk);
  if ($rowe = mysqli_fetch_assoc($sqlp)) {
    $trainingname = $rowe['duration'];
    $itemprice = $rowe['price'];
  }

  // Get total possible list items for this course to evaluate global bundled group eligibility
  $fullItemsCount = 0;
  $fullSQL = "SELECT COUNT(*) as total FROM training_items WHERE training_id = '$category'";
  $fullResult = mysqli_query($con, $fullSQL);
  if ($fRow = mysqli_fetch_assoc($fullResult)) {
    $fullItemsCount = (int)$fRow['total'];
  }

  // Fetch clean Key-Value structural matches for selected choices
  $items = [];
  foreach ($trainingItems as $trItem) {
    $safeItem = mysqli_real_escape_string($con, $trItem);
    $sqlItem = mysqli_query($con, "SELECT * FROM training_items WHERE name = '$safeItem' AND training_id = '$category'");
    if ($rowItem = mysqli_fetch_assoc($sqlItem)) {
      $items[] = $rowItem;
    }
  }

  // Tracking isolated component calculations
  $total_items_price = 0; // Using native session string indicator mapping fallback reference code placeholder

  foreach ($items as $oneItem) {
    $trainingId = $oneItem["training_id"];
    $dbItemId = $oneItem["name"];
    $itemRowPrice = $oneItem["price"];

    $total_items_price += $itemRowPrice;

    // Record items selection dependencies explicitly mapping back 
    mysqli_query($con, "INSERT INTO academy_cart_training_items(training_item_id, training_id, item_for) VALUES ('$dbItemId','$trainingId','$saloon')");
  }

  // Calculate composite combined absolute prices
  $subtotal = $itemprice + $total_items_price;
  $discount_percentage = 0;
  $discount_amount = 0;

  // Bundle criteria match requirement evaluations: Must purchase all matching options 
  if ($fullItemsCount > 0 && count($trainingItems) === $fullItemsCount) {
    $discount_percentage = (int)$toAdd;
    if ($discount_percentage > 0) {
      // FIXED FORMULA: (Price * Percent) / 100
      $discount_amount = ($subtotal * $discount_percentage) / 100;
    }
  }

  $final_grand_total = $subtotal - $discount_amount;
  $discount_applied = $discount_amount > 0 ? "true" : "false";

  // --- DATABASE SAVING STRATEGY ---
  // To keep things clean, make sure your academy_cart table has columns for:
  // `training_price`, `items_price`, `discount_amount`, and `final_total`
  // Adjust these field names to match your schema setup perfectly
  $query = "INSERT INTO academy_cart (
              `id`, `training`, `trainingname`, `duration`, `durationname`, 
              `price`, `discount_applied`
            ) VALUES (
              '$saloon', '$category', '$name', '$itemID', '$trainingname', 
              '$itemprice', '$discount_applied'
            )";

  mysqli_query($con, $query) or die('Database Insertion Error: ' . mysqli_error($con));

  header("Location: cart.php");
  exit();
}

include "footer.php"; ?>