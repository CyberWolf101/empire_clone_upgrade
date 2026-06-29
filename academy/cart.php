<?php include "header.php";

if (isset($_GET['rowitem'])) {
   $item_delete = mysqli_real_escape_string($con, $_GET['rowitem']);
   mysqli_query($con, "DELETE from academy_cart where s='$item_delete'") or die('Could not connect: ' . mysqli_error($con));
   mysqli_query($con, "DELETE from academy_cart_training_items where item_for='$saloon'") or die('Could not connect: ' . mysqli_error($con));
   header("refresh:0; url=cart.php");
   exit();
}

if ($username == "") {
   header("location: user_details.php");
   exit();
}
?>

<style>
   .section-title h2 {
      font-size: 32px;
      font-weight: bold;
      text-transform: capitalize;
      margin-bottom: 20px;
      padding-bottom: 20px;
      position: relative;
      letter-spacing: 0px;
   }

   .section-title h2::after {
      background: none;
   }

   .addon-list-cart {
      margin-top: 5px;
      padding-left: 15px;
      list-style-type: square;
      font-size: 0.75rem;
      color: #ccc;
      text-align: left;
   }
</style>

<section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none; border:none;">
   <div class="container" style="width:100%; margin:auto;">
      <div class="section-title" style="color:#FFFFFF;">
         <h2>CART CHECKOUT - PAYMENT</h2>
         <p>Pay with card or bank transfer. we make things flexible!</p>
         <div class="container-fluid mt-5">
            <div class="d-flex" style="overflow: auto;">
               <div class="col-md-12">
                  <table class="table table-bordered text-center" style="border-collapse: collapse;">
                     <thead style="background: #FFC700; color: white; border-style: 1px solid #FFC700;">
                        <tr>
                           <td style="border-right-style: hidden;"></td>
                           <td style="border-right-style: hidden;"></td>
                           <td style="border-right-style: hidden; text-align: left;">Training Description</td>
                           <td style="border-right-style: hidden;">Duration</td>
                           <td style="border-right-style: hidden;">Discount (%)</td>
                           <td style="border-right-style: hidden;">Tuition Price</td>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        // Initialize running totals
                        $total_tuition = 0;
                        $total_addons = 0;
                        $total_discounts = 0;
                        $total_all = 0; // Grand total payload sent to payment gateways

                        $sql = "SELECT ac.*,t.discount_added from academy_cart ac LEFT JOIN training t ON ac.training = t.id where ac.id='$saloon' ";
                        $sql2 = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_array($sql2)) {
                           $courseId = $row['id'];

                           // Accumulate running financial matrix paths
                           $total_tuition += $row['price'];
                           $total_addons += ($row['items_price'] ?? 0);
                           $discountAmount = $row['discount_applied'] == 'true' ? ($row['price'] * $row['discount_added']) / 100 : 0;

                           $total_discounts += $discountAmount;
                           $total_all += ($row['final_total'] ?? $row['price']);
                           $total_all -= $discountAmount;
                        ?>
                           <tr style="white-space: nowrap; color:#FFFFFF;">
                              <td width="80" style="vertical-align: middle; border-right-style: hidden;">
                                 <form action="" method="get">
                                    <input type="text" value="<?= $row['s'] ?>" name="rowitem" hidden />
                                    <button class="btn" type="submit"><i class="bx bxs-x-circle" style="font-size: 2rem; color: #FFC700;"></i></button>
                                 </form>
                              </td>
                              <td width="20"></td>
                              <td style="vertical-align: middle; border-left-style: hidden; text-align: left; color: white; font-size: 0.8rem; font-family: 'Poppins', sans-serif;">
                                 <div><span style="font-weight: 600; font-size:0.9rem; color:#FFC700;"><?= htmlspecialchars($row['trainingname']) ?></span></div>

                                 <ul class="addon-list-cart">
                                    <?php
                                    //$itemsQuery = "SELECT ti.name, ti.price FROM academy_cart_training_items acti LEFT JOIN training_items ti ON acti.training_item_id = ti.item_id
                                    // WHERE acti.item_for = '$saloon' AND acti.training_id = '$courseId'";
                                    $itemsQuery = "SELECT act.*,it.price
FROM academy_cart_training_items act
LEFT JOIN training_items it ON it.item_id = act.training_item_id
WHERE act.item_for = '$saloon'";
                                    $itemsResult = mysqli_query($con, $itemsQuery);
                                    if (mysqli_num_rows($itemsResult) > 0) {
                                       while ($addon = mysqli_fetch_assoc($itemsResult)) {
                                          $total_all += $addon["price"];
                                          echo "<li>" . htmlspecialchars($addon['training_item_id']) . " (&#8358;" . number_format($addon['price'], 2) . ")</li>";
                                       }
                                    } else {
                                       echo "<li style='list-style:none; color:#777; padding-left:0;'>No separate item tools selected</li>";
                                    }
                                    ?>
                                 </ul>
                              </td>
                              <td style="vertical-align: middle; border-left-style: hidden;"><?= htmlspecialchars($row['durationname']) ?></td>
                              <td style="vertical-align: middle; border-left-style: hidden; color: #ff4d4d;">
                                 <?php if ($row['discount_applied'] == 'true' && $row['discount_added'] > 0) { ?>
                                    <?= $row['discount_added'] ?>%
                                 <?php } else {
                                    echo "0%";
                                 } ?>
                              </td>
                              <td style="vertical-align: middle; border-left-style: hidden;">&#8358;<?= number_format($row['price'], 2) ?></td>
                           </tr>
                        <?php
                        }
                        ?>
                     </tbody>
                  </table>
                  <table class="table table-bordered text-center" style="border-collapse: collapse;">
                     <tfoot>
                        <tr style="white-space: nowrap;">
                           <form action="" method="post">
                              <td colspan="2"><input style="font-size:12px; height:35px;" type="text" placeholder="Enter giftcard serial" id="giftcard"><input type="text" id="orderid" value='<?php echo $saloon; ?>' hidden></td>
                              <td colspan="3" class="text-left align-middle" style="border-left-style: hidden; border-right-style: hidden;">
                                 <button type="submit" name="addcoupon" id="addcoupon" style="color:#FFC700; font-size: 0.8rem; font-weight: 600;" class="btn btn-light">Apply Giftcard</button>
                              </td>
                           </form>
                           <td colspan="" class="text-right">
                              <a href="index.php" style="font-size: 0.8rem; color: rgb(209, 209, 209); font-weight: 600;" class="btn btn-secondary">Update Cart</a>
                           </td>
                        </tr>
                     </tfoot>
                  </table>
               </div>
            </div>

            <div class="d-flex justify-content-end flex-wrap my-5" style="overflow: auto;">
               <div class="container border p-0">
                  <h5 class="bg-light p-3" style="color: #FFC700;">Cart Total Summary Breakdown</h5>
                  <table class="table" style="color: white; font-weight: 600;">
                     <tbody>
                        <tr style="border-top-style: hidden;">
                           <th scope="row"></th>
                           <td>Course Tuition Subtotal</td>
                           <td>&#8358;<?php echo number_format($total_tuition, 2); ?></td>
                           <td></td>
                        </tr>
                        <tr>
                           <th scope="row"></th>
                           <td>Training Items & Materials Cost</td>
                           <td>&#8358;<?php echo number_format($total_addons, 2); ?></td>
                           <td></td>
                        </tr>
                        <?php if ($total_discounts > 0) { ?>
                           <tr style="color: #28a745;">
                              <th scope="row"></th>
                              <td>Bundle Discount Deducted</td>
                              <td>-&#8358;<?php echo number_format($total_discounts, 2); ?></td>
                              <td></td>
                           </tr>
                        <?php } ?>
                        <tr>
                           <th scope="row"></th>
                           <td>Grand Total Payable Balance</td>
                           <td>&#8358;<?php echo number_format($total_all, 2); ?></td>
                           <td></td>
                        </tr>

                        <tr class="topay" style="display:none; color: #FFC700;">
                           <th scope="row"></th>
                           <td>Amount Left To Pay (Giftcard Applied)</td>
                           <td>&#8358;<span id="amounttopay"></span>.00</td>
                           <td></td>
                        </tr>

                        <script>
                           $(document).ready(function() {
                              $('#addcoupon').click(function(e) {
                                 e.preventDefault();
                                 var giftcardValue = $('#giftcard').val();
                                 var orderValue = $('#orderid').val();
                                 $("#addcoupon").attr("disabled", "disabled");

                                 $.ajax({
                                    url: 'deductgiftcard.php',
                                    type: 'POST',
                                    data: {
                                       giftcard: giftcardValue,
                                       orderno: orderValue
                                    },
                                    success: function(response) {
                                       if (response === 'success') {
                                          alert('Payment has been initiated and is being processed.');
                                          window.location.href = 'https://chbluxuryempire.com/academy/success?status=completed&tx_ref=<?php echo $saloon; ?>';
                                       } else if (response === 'half-success') {
                                          alert('Giftcard applied successfully. Please pay up the rest of your invoice with your bank card');
                                          updateValues();
                                       } else {
                                          alert(response);
                                          $("#addcoupon").removeAttr("disabled");
                                       }
                                    }
                                 });
                              });
                           });

                           function updateValues() {
                              var orderValue = $('#orderid').val();
                              $.ajax({
                                 url: 'fetchamount.php',
                                 type: 'POST',
                                 data: {
                                    orderno: orderValue
                                 },
                                 success: function(data) {
                                    $('.topay').show();
                                    $('#realamount').val(data);
                                    $('#amounttopay').text(data);

                                    // Update bank transfer destination links dynamically if values change
                                    if (document.getElementById('bankTransferLink')) {
                                       document.getElementById('bankTransferLink').href = "banktransfer.php?amount_payable=" + data + "&tuition=" + <?= $total_tuition ?> + "&addons=" + <?= $total_addons ?>;
                                    }

                                    var $element = $(".topay");
                                    if ($element.length) {
                                       var offsetTop = $element.offset().top;
                                       $("html, body").animate({
                                          scrollTop: offsetTop
                                       }, 1000);
                                    }
                                 },
                                 error: function() {
                                    alert('Failed to fetch data from the database.');
                                 }
                              });
                           }
                        </script>

                        <div class="payment-methods p-3" style="background:#111; border:1px solid #FFC700; border-radius:10px; text-align: left;">
                           <h4 style="color:#FFC700;">Choose payment method</h4>
                           <div class="mb-3" style="max-width:360px;">
                              <select id="paymentMethod" class="form-control" style="background:#000; color:#fff; border:1px solid #FFC700;">
                                 <option value="flutterwave">Flutterwave (Card / USSD)</option>
                                 <option value="banktransfer">Bank Transfer (Manual Invoice Upload)</option>
                              </select>
                           </div>

                           <div id="flutterwaveSection">
                              <form id="flutterwaveForm" method="post" action="https://checkout.flutterwave.com/v3/hosted/pay">
                                 <input type="hidden" name="tx_ref" value="<?php echo $saloon; ?>" />
                                 <input type="hidden" name="public_key" value="<?php echo $apikey; ?>" />
                                 <input type="email" name="customer[email]" value="<?php echo $c_email; ?>" hidden />
                                 <input type="hidden" name="customer[phone_number]" value="<?php echo $c_phone; ?>" />
                                 <input type="hidden" name="customer[name]" value="<?php echo $username; ?>" />

                                 <input type="hidden" id="realamount" name="amount" value="<?php echo $total_all; ?>" />
                                 <input type="hidden" name="currency" value="NGN" />
                                 <input type="hidden" name="meta[token]" value="54" />
                                 <input type="hidden" name="redirect_url" value="https://chbluxuryempire.com/academy/success.php" />

                                 <?php if ($total_all > 0) {
                                    if (isset($type) && $type == "1" && isset($count_services) && $count_services < $type) {
                                       echo '<script type="text/javascript">$(document).ready(function(){ $("#myMod").modal("show"); });</script>';
                                    } else {
                                       echo '<button type="submit" class="form-control" style="font-weight: 600; font-size: 0.8rem; color: #FFC700; background:#000; border:1px solid #FFC700; cursor:pointer;">Proceed To Flutterwave Checkout (&#8358;' . number_format($total_all, 2) . ')</button>';
                                    }
                                 } ?>
                              </form>
                           </div>

                           <div id="bankTransferSection" style="display:none; margin-top:20px;">
                              <p style="color:#fff; font-size:0.85rem; margin-bottom:15px;">You selected bank transfer. Click below to view bank account payment parameters and submit transaction screenshots.</p>
                              <?php if ($total_all > 0) { ?>
                                 <form id="bankTransferForm" method="GET" action="banktransfer.php">
                                    <input type="hidden" name="amount_payable" value="<?php echo $total_all; ?>" />
                                    <input type="hidden" name="tuition" value="<?php echo $total_tuition; ?>" />
                                    <input type="hidden" name="addons" value="<?php echo $total_addons; ?>" />
                                    <input type="hidden" name="discount" value="<?php echo $total_discounts; ?>" />
                                    <button type="submit" class="form-control" style="font-weight: 600; font-size: 0.8rem; color: #000; background:#FFC700; border:1px solid #FFC700; cursor:pointer;">Proceed With Bank Transfer (&#8358;<?php echo number_format($total_all, 2); ?>)</button>
                                 </form>
                              <?php } ?>
                           </div>
                        </div>

                        <script>
                           document.addEventListener('DOMContentLoaded', function() {
                              var paymentMethod = document.getElementById('paymentMethod');
                              var flutterwaveSection = document.getElementById('flutterwaveSection');
                              var bankTransferSection = document.getElementById('bankTransferSection');

                              paymentMethod.addEventListener('change', function() {
                                 if (paymentMethod.value === 'banktransfer') {
                                    flutterwaveSection.style.display = 'none';
                                    bankTransferSection.style.display = 'block';
                                 } else {
                                    flutterwaveSection.style.display = 'block';
                                    bankTransferSection.style.display = 'none';
                                 }
                              });
                           });
                        </script>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<?php include "footer.php"; ?>