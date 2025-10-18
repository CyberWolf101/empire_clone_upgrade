<?php include "header.php";

//Add bottle water
$bot = "SELECT all* from refreshments where orderid='$saloon' && item='Free coffee,Tea or Portable Water'";
$bot2 = mysqli_query($con, $bot);
if (mysqli_affected_rows($con) == 0) {
  $submit = mysqli_query($con, "insert into refreshments(orderid,itemid,item,unitprice,quantity,totalprice,status) values ('$saloon','','Free coffee,Tea or Portable Water','0','$type','0','processing')") or die('Could not connect: ' . mysqli_error($con));
}

//Delete
$service_delete = $_GET['rowid'];
$del = mysqli_query($con, "DELETE from appointments where s='$service_delete'") or die('Could not connect: ' . mysqli_error($con));


$item_delete = $_GET['rowitem'];
$del = mysqli_query($con, "DELETE from refreshments where s='$item_delete'") or die('Could not connect: ' . mysqli_error($con));

//Delete
if (isset($_GET['pedicure'])) {
  $pedicure = $_GET['pedicure'];
  $insert = mysqli_query($con, "UPDATE saloon_orders SET saloonkit='0' where id='$saloon'") or die('Could not connect: ' . mysqli_error($con));
  header("location: cart.php");
}
?>

<script type="text/javascript">
  window.onload = () => {
    $('#myModal').modal('show');
  }
</script>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="vertical-alignment-helper">
    <div class="modal-dialog vertical-align-center">
      <div class="modal-content">
        <div class="modal-header" style="background-color:#000000; color:#FFFFFF;">
          <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Hello there!<i class="bx bx-wink-smile"
              style="font-size:20px;"></i></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
            style="color:#FFFFFF;"></button>
        </div>
        <div class="modal-body w-100 text-center" style="background-color:#000000; color:#FFFFFF;">
          <p style="font-weight:600;">Would you like to be refreshed with food and drinks during your appointment?</p>
          <p style="color:black;" style="margin-bottom:30px;">
            <a href="foodpage.php" class="submitn">Yes,Proceed</a>
          </p>
          <p><button class="submitn" data-bs-dismiss="modal">No,Cancel</button></p>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- ======= Pricing Section ======= -->
<section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
  <div class="container" style="width:100%; margin:auto;">
    <div class="section-title" style="color:#FFFFFF;">
      <h2>CART CHECKOUT - PAYMENT</h2>
      <p>Pay with card. we make things flexible!</p>

      <div class="container-fluid mt-5">
        <div class="d-flex" style="overflow: auto;">
          <div class="col-md-12">
            <table class="table table-bordered text-center" style="border-collapse: collapse;">
              <thead style="background: #FFC700; color: white; border-style: 1px solid #FFC700;">
                <tr>
                  <td style="border-right-style: hidden;"></td>
                  <td style="border-right-style: hidden;"></td>
                  <td style="border-right-style: hidden; text-align: left;">Product</td>
                  <td style="border-right-style: hidden;">Price</td>
                  <td style="border-right-style: hidden;">Booking</td>
                  <td>Total</td>
                </tr>
              </thead>


              <tbody>
                <?php

                $sql = "SELECT * from appointments where id='$saloon' ORDER BY s ASC";
                $sql2 = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_array($sql2)) {
                  $cname = $row['name'];
                  $cmail = $row['email'];
                  $cmob = $row['phone'];
                  $sev = $row['service'];
                  $tev = $row['timef'];
                  $nom = $row['nom'];
                  $dear = $row['date'];
                  $perd = $row['price'];



                  echo '
                                <tr style="white-space: nowrap;  color:#FFFFFF;">
                                <td width="80" style="vertical-align: middle; border-right-style: hidden;">
                                <form action="" method="get"><input type="text" value="' . $row['s'] . '" name="rowid" hidden/>
                                <button class="btn" type="submit">
                                <i class="bx bxs-x-circle" style="font-size: 2rem; color: #FFC700;"></i></button></form></i></td>
                                <td width="80"></td>
                                <td style="vertical-align: middle; border-left-style: hidden; text-align: left; color: white; font-size: 0.7rem; font-family: "Poppins", sans-serif;">
                                    
									<div><span style="font-weight: 500;">' . $row['servicename'] . '</span></div>
                                    <div style="color:#FFC700;">Appointement Details</div>
                                    <div>Date: ' . $row['date'] . '</div>
                                    <div>From: ' . $row['start_time'] . '</div>
									<div>To: ' . $row['end_time'] . '</div>
                                    <div>Staff: ' . $row['staffname'] . '</div>
                                </td>
                                <td style="vertical-align: middle; border-left-style: hidden;">&#8358;' . $row['price'] . '</td>
                                <td width="50" style="vertical-align: middle; border-left-style: hidden;">1</td>
                                <td style="vertical-align: middle; border-left-style: hidden;">&#8358;' . $row['price'] . '</td>
                                </tr>';
                }

                if ($kit > 0) {

                  echo '
                                <tr style="white-space: nowrap;  color:#FFFFFF;">
                                <td width="80" style="vertical-align: middle; border-right-style: hidden;">
                                <form action="" method="get"><input type="text" value="0" name="pedicure" hidden/>
                                <button class="btn" type="submit">
                                <i class="bx bxs-x-circle" style="font-size: 2rem; color: #FFC700;"></i></button></form></i></td>
                                <td width="80"></td>
                                <td style="vertical-align: middle; border-left-style: hidden; text-align: left; color: white; font-size: 0.7rem; font-family: "Poppins", sans-serif;">
                                    
									<div><span style="font-weight: 500;">Pedicure Spa Kit</span></div>
                                </td>
                                <td style="vertical-align: middle; border-left-style: hidden;">&#8358;' . $kit . '</td>
                                <td width="50" style="vertical-align: middle; border-left-style: hidden;">1</td>
                                <td style="vertical-align: middle; border-left-style: hidden;">&#8358;' . $kit . '</td>
                                </tr>';
                }

                ?>
              </tbody>
            </table>
            <h3>REFRESHMENTS</h3>
            <table class="table table-bordered text-center" style="border-collapse: collapse;">
              <thead style="background: #FFC700; color: white; border-style: 1px solid #FFC700;">
                <tr>
                  <td style="border-right-style: hidden;"></td>
                  <td style="border-right-style: hidden;"></td>
                  <td style="border-right-style: hidden; text-align: left;">Item</td>
                  <td style="border-right-style: hidden;">Price</td>
                  <td style="border-right-style: hidden;">Quantity</td>
                  <td>Total</td>
                </tr>
              </thead>
              <tbody>
                <?php
                //All refreshment orders
                $sql = "SELECT all* from refreshments where orderid='$saloon' ";
                $sql2 = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_array($sql2)) {
                  echo '
                                <tr style="white-space: nowrap;  color:#FFFFFF;">
                                <td width="80" style="vertical-align: middle; border-right-style: hidden;"><form action="" method="get"><input type="text" value="' . $row['s'] . '" name="rowitem" hidden/>
                                <button class="btn" type="submit"><i class="bx bxs-x-circle" style="font-size: 2rem; color: #FFC700;"></i></button></form></i></td>
                                <td width="80"></td>
                                <td style="vertical-align: middle; border-left-style: hidden; text-align: left; color: white; font-size: 0.7rem; font-family: "Poppins", sans-serif;">
                                <div><span style="font-weight: 500;">' . $row['item'] . '</span></div>
                                </td>
                                <td style="vertical-align: middle; border-left-style: hidden;">&#8358;' . $row['unitprice'] . '</td>
                                <td width="50" style="vertical-align: middle; border-left-style: hidden;">' . $row['quantity'] . '</td>
                                <td style="vertical-align: middle; border-left-style: hidden;">&#8358;' . $row['totalprice'] . '</td>
                            </tr>';

                }
                ?>
              </tbody>
            </table>



















            <table class="table table-bordered text-center" style="border-collapse: collapse;">
              <tfoot>
                <tr style="white-space: nowrap;">
                  <form action="" method="post">
                    <td colspan="2"><input style="font-size:12px; height:35px;" type="text"
                        placeholder="Enter giftcard serial" id="giftcard"><input type="text" id="orderid"
                        value='<?php echo $saloon; ?>' hidden></td>
                    <td colspan="3" class="text-left align-middle"
                      style="border-left-style: hidden; border-right-style: hidden;">
                      <button type="submit" name="addcoupon" id="addcoupon"
                        style="color:#FFC700; font-size: 0.8rem; font-weight: 600;" class="btn btn-light">Apply
                        Giftcard</button>
                    </td>
                  </form>
                  <td colspan="2" class="text-right"><a href="category.php"
                      style="font-size: 0.8rem; color: rgb(209, 209, 209); font-weight: 600;"
                      class="btn btn-secondary">Update Cart</a></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="d-flex justify-content-end flex-wrap my-5" style="overflow: auto;">
          <div class="container border p-0">
            <h5 class="bg-light p-3" style="color: #FFC700;">Cart Total</h5>
            <table class="table" style="color: white; font-weight: 600;">
              <tbody>
                <tr style="border-top-style: hidden;">
                  <th scope="row"></th>
                  <td>Subtotal</td>
                  <td>&#8358;<?php echo $total_all; ?>.00</td>
                  <td></td>
                </tr>


                <tr>
                  <th scope="row"></th>
                  <td>Total</td>
                  <td>&#8358;<?php echo $total_all; ?>.00</td>
                  <td></td>
                </tr>

                <tr class="topay" style="display:none; color: #FFC700;">
                  <th scope="row"></th>
                  <td>Amount Left To Pay</td>
                  <td>&#8358;<span id="amounttopay"></span>.00</td>
                  <td></td>
                </tr>


                <div class="modal fade" id="myMod" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="vertical-alignment-helper">
                    <div class="modal-dialog vertical-align-center">
                      <div class="modal-content">
                        <div class="modal-header" style="background-color:#000000; color:#FFFFFF;">
                          <h5 class="modal-title w-100 text-center" id="exampleModalLabel">Add More Services!</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="color:#FFFFFF;"></button>
                        </div>
                        <div class="modal-body w-100 text-center" style="background-color:#000000; color:#FFFFFF;">
                          <p style="font-weight:600; font-size:13px;">Your booking type requires that you add more
                            services else you can't checkout<br>i.e 2 or more services for couple booking or 3 or more
                            services for family booking</p>
                          <p><a href="more.php"><button class="submitn">Okay,Choose more</button></a></p>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>






                <script>
                  $(document).ready(function () {
                    $('#addcoupon').click(function () {
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
                        success: function (response) {
                          if (response === 'success') {
                            alert('Payment has been initiated and is being processed.');
                            window.location.href = 'https://chbluxuryempire.com/saloon/success?status=completed&tx_ref=<?php echo $saloon; ?>';
                          } else if (response === 'half-success') {
                            alert('Giftcard applied successsfully.Please pay up the rest of your invoice with your bank card');
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
                      data: { orderno: orderValue },
                      success: function (data) {
                        // Update the values in your HTML
                        $('.topay').show();
                        $('#realamount').val(data);
                        $('#amounttopay').text(data);


                        var $element = $(".topay");
                        if ($element.length) {
                          var offsetTop = $element.offset().top;
                          $("html, body").animate({
                            scrollTop: offsetTop
                          }, 1000);
                        }

                      },
                      error: function () {
                        alert('Failed to fetch data from the database.');
                      }
                    });
                  }





                </script>





                <form method="post" action="https://checkout.flutterwave.com/v3/hosted/pay">
                  <input type="hidden" name="public_key" value="<?php echo $apikey; ?>" />
                  <input type="email" name="customer[email]"
                    style="border:0; color:#fff; width:300px;  outline:0; background:transparent; border-bottom:2px solid #fff;"
                    value=" <?php echo $c_email; ?>" placeholder="Enter Email" hidden />
                  <input type="hidden" name="customer[phone_number]"
                    style="border:0; color:#fff; width:300px;  outline:0; background:transparent; border-bottom:2px solid #fff;"
                    value=" <?php echo $c_phone; ?>" placeholder="Enter Phone Number" />
                  <input type="hidden" name="customer[name]"
                    style="border:0; color:#fff; width:300px;  outline:0; background:transparent; border-bottom:2px solid #fff;"
                    value=" <?php echo $username; ?> " />
          </div><br>


          <input type="hidden" name="tx_ref" value=" <?php echo $saloon; ?>" />
          <input type="hidden" id="realamount" name="amount" value=" <?php echo $total_all; ?> " />
          <input type="hidden" name="currency" value="NGN" />
          <input type="hidden" name="meta[token]" value="54" />
          <input type="hidden" name="redirect_url" value="https://chbluxuryempire.com/saloon/success.php" />
          <tr style="border-bottom-style: hidden;">
            <th scope="row"></th>
            <?php
            //Checkout Condition
            


            if ($total_all > 1) {
              if ($type == "1" && $count_services < $type) {
                echo '<script type="text/javascript">
			                            $(document).ready(function(){
			                            $("#myMod").modal("show");	});\</script>';


                echo "<script type='text/javascript'>
                              window.onload = () => {
                              $('#myModal').modal('hide');} </script>";
              } else {
                echo '<td colspan="2" class="align-middle"><button type="submit" class="form-control" style="font-weight: 600; font-size: 0.8rem; color: #FFC700;">
                               Proceed To Checkout</button></td>';
              }
            }

            ?>
          </tr>
          </tbody>
          </table>
          </form>
        </div>
      </div>
    </div>




  </div>
  </div>
</section><!-- End Pricing Section -->


<?php include "footer.php"; ?>