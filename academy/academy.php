<?php include "header.php";


if (isset($_GET['category'])) {
  $category = $_GET['category'];
} else {
  header("Location: " . $_SERVER['HTTP_REFERER']);
}

$sql = "SELECT * from training where id='$category' ";
$sql2 = mysqli_query($con, $sql);
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
    outline: none;
    border: none;
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

  }

  .btn-buya:hover {
    display: inline-block;
    padding: 10px;
    border: none;
    color: #fff;
    text-align: center;
    font-size: 14px;
    text-transform: uppercase;
    font-family: 'Poppins', Open sans;
    font-weight: 800;
    background: #000000;
    margin-bottom: 20px;
    width: 300px;

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

  #clocs {
    display: none;
  }

  #cloch {
    display: none;
  }
</style>

<section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
  <div class="container" style="width:100%; margin:auto; ">
    <div class="section-title" style="color:#FFFFFF;">
      <?php if ($count_services > 0) { ?><p style="float:right;"><a class="btn-buya" href="cart.php">VIEW CART</a></p><?php  } ?>
      <h2><?php echo $name; ?></h2>
    </div>

    <div class="row">
      <div class="col-lg-12 col-md-12">
        <p style="color:#FEBF01;">Choose a duration</p>
        <div class="box" data-aos="zoom-in" data-aos-delay="100">
        </div>
      </div>

      <div class="col-lg-12 col-md-12">
        <div class="box" data-aos="zoom-in" data-aos-delay="100">



          <div id="main">
            <form method='post' class='user-form'>
              <table id="results" width="95%" border="0" cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px; padding:10px 0px;">
                <tbody>

                  <?php

                  $sql = "SELECT * FROM durations where category='$category'";
                  $sql2 = mysqli_query($con, $sql);
                  while ($row = mysqli_fetch_array($sql2)) {


                    echo '<tr class="ter mx-3" onclick=\'this.querySelector("input[type=radio]").click();\' >
	<td class="check"><input type="radio" style="pointer-events:none;"  value="' . $row['s'] . '" name="item"  required/></td>
	<td class="check"><span>&nbsp; &nbsp; &nbsp; &nbsp;  ' . $row['duration'];
                  ?>
                    <?php
                    $unitOutputs = [
                      [
                        "short_form" => "d",
                        "full_form" => "Day(s)"
                      ],
                      [
                        "short_form" => "w",
                        "full_form" => "Weeks(s)"
                      ],
                      [
                        "short_form" => "m",
                        "full_form" => "Months(s)"
                      ],
                      [
                        "short_form" => "y",
                        "full_form" => "Year(s)"
                      ]
                    ];
                    foreach ($unitOutputs as $output) {
                      if ($row['duration_unit'] == $output["short_form"]) {
                    ?>
                        <?= $output["full_form"] ?>
                    <?php
                      }
                    }
                    ?>
                  <?php
                    echo '</span></td>
    <td class="check" style="font-size:16px">&#8358;' . $row['price'] . '.00</td>
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
          while ($rrrrrow = mysqli_fetch_array($response)) {
            $toAdd += $rrrrrow["discount_added"];
          }
          ?>
          <p class="ms-4">Select Training items</p>
          <?php
          if ($toAdd > 0) {
          ?>
            <p class="ms-4" style="color: green;">Purchase all items to get <?= $toAdd ?>% discount</p>
          <?php
          }
          ?>

          <table id="results" width="95%" border="0" cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px; padding:10px 0px;">
            <tbody>

              <?php

              $sql = "SELECT * FROM training_items where training_id='$category'";
              $sql2 = mysqli_query($con, $sql);
              while ($row = mysqli_fetch_array($sql2)) {


                echo '<tr class="ter mx-3" onclick=\'this.querySelector("input[type=checkbox]").click();\' >
	<td class="check"><input type="checkbox" style=""  value="' . $row['name'] . '" name="training_item[]" /></td>
	<td class="check"><span>&nbsp; &nbsp; &nbsp; &nbsp;  ' . $row['name'] . '</span></td>
    <td class="check" style="font-size:16px">&#8358;' . $row['price'] . '.00</td>
    </tr>';
              }

              ?>


            </tbody>
          </table>

          <div class="btn-wrap" align="center" style="margin-bottom:40px;">
            <button type="submit" name="submit" value="add" class="btn-buya">NEXT</button><br />
            </form>
          </div>

        </div>
</section><!-- End Pricing Section -->

<?php
if (isset($_POST['submit'])) {
  $itemID = $_POST['item'];
  $trainingItems = $_POST['training_item'] ?? "";

  $sqk = "SELECT * FROM durations WHERE s= '$itemID'";
  $sqlp = mysqli_query($con, $sqk);
  if ($sqlp) {
    while ($rowe = mysqli_fetch_array($sqlp)) {
      $trainingname = $rowe['duration'];
      $itemprice = $rowe['price'];
    }
  }
  $fullItems = [];
  $fullSQL = "SELECT * FROM training_items WHERE training_id = '$category'";
  $sql2 = mysqli_query($con, $fullSQL);
  while ($rrrrr = mysqli_fetch_array($sql2)) {
    $fullItems[] = $rrrrr;
  }
  $items = [];
  // Confuser part
  // foreach ($trainingItems as $item) {
  //   $sqll = mysqli_query($con, "SELECT * FROM training_items WHERE name = '$item'");
  //   if ($sqll) {
  //     $ress = mysqli_fetch_array($sqll);
  //     while ($rrrow = $ress) {
  //       echo "<br>";
  //       var_dump($rrrow);
  //     }
  //   }
  // }
  $calculatedprice = 0;

  $calculatedprice += $itemprice;
  $discount = 0;
  foreach ($items as $oneItem) {
    $trainingId = $oneItem["training_id"];
    $itemId = $oneItem["item_id"];
    $submit2 = mysqli_query($con, "INSERT INTO academy_cart_training_items(training_item_id, training_id, item_for) VALUES ('$itemId','$trainingId','$saloon')");
    $calculatedprice += $oneItem["price"];
    if (count($trainingItems) == count($fullItems)) {
      $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT discount_added FROM training WHERE id = '$trainingId'"));

      foreach ($result as $r) {
        $discount += $result["discount_added"];
      }
      $amountToDiscount = $discount > 0 ? $calculatedprice / $discount : 0;
      if (($amountToDiscount > 0)) {
        $calculatedprice -= $amountToDiscount;
        $_SESSION["discount"] = [
          "status" => true,
          "percent" => $discount
        ];
        setcookie("discount", "[
          'status'=>true,
          'percent'=>$discount
        ]", time() - 3600, "/", "", true, true);
      }
    }
  }
  $discount_applied = $discount > 0 ? "true" : "false";
  $submit = mysqli_query($con, "INSERT INTO academy_cart(`id`, `training`, `trainingname`, `duration`, `durationname`, `price`,`discount_applied`) 
   VALUES ('$saloon','$category','$name','$itemID','$trainingname','$calculatedprice','$discount_applied')") or die('Could not connect: ' . mysqli_error($con));

  header("location:cart.php");
}

include "footer.php";  ?>