<?php
include "header.php";

$item_delete = mysqli_real_escape_string($con, $_GET['rowitem'] ?? '');
if (!empty($item_delete)) {
    $del = mysqli_query($con, "DELETE FROM refreshments WHERE s='$item_delete'") or die('Could not connect: ' . mysqli_error($con));
}
if (empty($_COOKIE['username'])) {
    header("location: userdetails.php");
    exit;
} else {
    $username = $_COOKIE['username'];
}

$saloon = mysqli_real_escape_string($con, $saloon);
$del = mysqli_query($con, "DELETE FROM giftcard_history WHERE orderid='$saloon' AND status='processing'") or die('Could not connect: ' . mysqli_error($con));

// Get subtotal (cart items only)
$subtotal = 0;
$sql_total = "SELECT SUM(totalprice) as total, item_category FROM refreshments WHERE orderid='$saloon'";
$res_total = mysqli_query($con, $sql_total);
if ($row_total = mysqli_fetch_assoc($res_total)) {
    $subtotal = (int) $row_total['total'];
}
// Get shipping fee, type, and selected place from session or saloon_orders
$shipping_fee = isset($_SESSION['shipping_fee']) ? (int) $_SESSION['shipping_fee'] : 0;
$shipping_type = isset($_SESSION['shipping_type']) ? $_SESSION['shipping_type'] : 'pickup';
$selected_place = isset($_SESSION['selected_place']) ? $_SESSION['selected_place'] : '';

$sql_shipping = "SELECT shipping_fee, shipping_type FROM saloon_orders WHERE id='$saloon'";
$res_shipping = mysqli_query($con, $sql_shipping);
if ($res_shipping && $row_shipping = mysqli_fetch_assoc($res_shipping)) {
    $shipping_fee = (int) $row_shipping['shipping_fee'];
    $shipping_type = $row_shipping['shipping_type'];
    // Update session with database values if they exist
    $_SESSION['shipping_fee'] = $shipping_fee;
    $_SESSION['shipping_type'] = $shipping_type;
} else {
    error_log("Failed to fetch shipping details from saloon_orders: " . mysqli_error($con));
}

// Calculate total_all (subtotal + shipping_fee)
$total_all = ($subtotal) + $shipping_fee;
// Save in session
// $_SESSION['cart_total'] = $subtotal;
// $_SESSION['shipping_fee'] = $shipping_fee;
// $_SESSION['shipping_type'] = $shipping_type;
// $_SESSION['selected_place'] = $selected_place;
setcookie('cart_total',$total_all,time() + 600, "/");
setcookie('shipping_fee',$shipping_fee,time() + 600, "/");
setcookie('shipping_type',$shipping_type,time() + 600, "/");
setcookie('selected_place',$selected_place,time() + 600, "/");
// Now fetch items
$sql = "SELECT * FROM refreshments WHERE orderid='$saloon'";
$sql2 = mysqli_query($con, $sql);
?>

<!-- Alert Display -->
<?php
if (!empty($_SESSION['success_message'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
    unset($_SESSION['error_message']);
}
?>

<script type="text/javascript">
window.onload = () => {
    $('#myModal').modal('show');
}
</script>

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

.alert {
    border: 1px solid #ffc700;
    background-color: #fff;
    color: #000;
    margin: 20px auto;
    width: 50%;
    max-width: 600px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

#pickup-details,
#delivery-info {
    color: white;
    font-size: 0.8rem;
    margin-top: 10px;
}
</style>

<!-- ======= Pricing Section ======= -->
<section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none; border:none;">
    <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#FFFFFF;">
            <h2>CART CHECKOUT - PAYMENT</h2>
            <p>Pay with card or bank transfer. We make things flexible!</p>

            <div class="container-fluid mt-5">
                <div class="d-flex" style="overflow: auto;">
                    <div class="col-md-12">
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
                                while ($row = mysqli_fetch_array($sql2)) {
                                    echo '
                                        <tr style="white-space: nowrap; color:#FFFFFF;">
                                            <td width="80" style="vertical-align: middle; border-right-style: hidden;">
                                                <form action="" method="get">
                                                    <input type="text" value="' . $row['s'] . '" name="rowitem" hidden/>
                                                    <button class="btn" type="submit"><i class="bx bxs-x-circle" style="font-size: 2rem; color: #FFC700;"></i></button>
                                                </form>
                                            </td>
                                            <td width="80"></td>
                                            <td style="vertical-align: middle; border-left-style: hidden; text-align: left; color: white; font-size: 0.7rem; font-family: \"Poppins\", sans-serif;">
                                                <div><span style="font-weight: 500;">' . $row['item'] . ($row['preorder'] ? ' <span class="badge bg-warning text-dark" style="font-size:11px;">Preorder</span>' : '') . '</span></div>
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
                                                placeholder="Enter giftcard serial" id="giftcard"><input type="text"
                                                id="orderid" value='<?php echo $saloon; ?>' hidden></td>
                                        <td colspan="3" class="text-left align-middle"
                                            style="border-left-style: hidden; border-right-style: hidden;">
                                            <button type="submit" name="addcoupon" id="addcoupon"
                                                style="color:#FFC700; font-size: 0.8rem; font-weight: 600;"
                                                class="btn btn-light">Apply Giftcard</button>
                                        </td>
                                    </form>
                                    <td colspan="" class="text-right"><a href="foodpage.php"
                                            style="font-size: 0.8rem; color: rgb(209, 209, 209); font-weight: 600;"
                                            class="btn btn-secondary">Update Cart</a></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Delivery Options -->
                <div class="delivery-options mt-4">
                    <h6 style="color: #FFFFFF;">Delivery Options</h6>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="delivery_option" id="pickup" value="pickup"
                            <?php echo $shipping_type === 'pickup' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="pickup" style="color: #FFFFFF;">
                            Pickup at Store
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="delivery_option" id="delivery"
                            value="delivery" <?php echo $shipping_type === 'delivery' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="delivery" style="color: #FFFFFF;">
                            Delivery within Lagos
                        </label>
                    </div>
                    <div id="pickup-details"
                        style="display: <?php echo $shipping_type === 'pickup' ? 'block' : 'none'; ?>;"
                        class="border border-1 p-2 mt-4">
                        <div class="mb-1">
                            <b>PICKUP ADDRESS:</b>
                        </div>
                        <p><b>Address:</b> 19 Olowu St, Opebi 101233, Ikeja, Lagos</p>
                        <p><b>Phone:</b> 09025572552</p>
                        <p><b>Pickup Code:</b> <?php echo $saloon ?></p>
                    </div>
                    <?php include "google_maps_autocomplete.php"; ?>
                    <div id="delivery-info"></div>
                </div>

                <div class="d-flex justify-content-end flex-wrap my-5" style="overflow: auto;">
                    <div class="container border p-0">
                        <h5 class="bg-light p-3" style="color: #FFC700;">Cart Total</h5>
                        <table class="table" style="color: white; font-weight: 600;">
                            <tbody>
                                <tr style="border-top-style: hidden;">
                                    <th scope="row"></th>
                                    <td>Subtotal</td>
                                    <td>&#8358;<span id="subtotal"><?php echo $subtotal; ?></span>.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row"></th>
                                    <td>Shipping Fee</td>
                                    <td>&#8358;<span id="shipping-fee"><?php echo $shipping_fee; ?></span>.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row"></th>
                                    <td>Total</td>
                                    <td>&#8358;<span id="total-amount"><?php echo $total_all; ?></span>.00</td>
                                    <td></td>
                                </tr>
                                <tr class="topay" style="display:none; color: #FFC700;">
                                    <th scope="row"></th>
                                    <td>Amount Left To Pay</td>
                                    <td>&#8358;<span id="amounttopay"></span>.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row"></th>
                                    <td>Payment Method</td>
                                    <td>
                                        <select id="paymentMethod" name="payment_method" onchange="updateFormAction()">
                                            <option value="banktransfer">Bank Transfer</option>
                                            <option value="flutterwave" disabled>Flutterwave</option>
                                        </select>
                                    </td>
                                    <td></td>
                                </tr>
                                <form id="paymentForm" method="post" action="banktransfer.php">
                                    <input type="hidden" name="public_key" value="<?php echo $apikey; ?>" />
                                    <input type="email" name="customer[email]"
                                        style="border:0; color:#fff; width:300px; outline:0; background:transparent; border-bottom:2px solid #fff;"
                                        value="<?php echo $c_email; ?>" placeholder="Enter Email" hidden />
                                    <input type="hidden" name="customer[phone_number]"
                                        style="border:0; color:#fff; width:300px; outline:0; background:transparent; border-bottom:2px solid #fff;"
                                        value="<?php echo $c_phone; ?>" placeholder="Enter Phone Number" />
                                    <input type="hidden" name="customer[name]"
                                        style="border:0; color:#fff; width:300px; outline:0; background:transparent; border-bottom:2px solid #fff;"
                                        value="<?php echo $username; ?>" />
                                    <input type="hidden" name="tx_ref" value="<?php echo $saloon; ?>" />
                                    <input type="hidden" id="realamount" name="amount"
                                        value="<?php echo $total_all; ?>" />
                                    <input type="hidden" id="shipping-type" name="shipping_type"
                                        value="<?php echo $shipping_type; ?>" />
                                    <input type="hidden" id="shipping-cost" name="shipping_cost"
                                        value="<?php echo $shipping_fee; ?>" />
                                    <input type="hidden" name="currency" value="NGN" />
                                    <input type="hidden" name="meta[token]" value="54" />
                                    <input type="hidden" name="redirect_url"
                                        value="https://chbluxuryempire.com/success.php" />
                                    <tr style="border-bottom-style: hidden;">
                                        <th scope="row"></th>
                                        <?php
                                        if ($subtotal <= 0) {
                                            echo '<td colspan="2" class="align-middle"><a href="foodpage.php" class="form-control" style="font-weight: 600; font-size: 0.8rem; color: #FFC700;">
                                                    Add Items To Cart</a></td>';
                                        } else {
                                            echo '<td colspan="2" class="align-middle"><button type="submit" id="checkout-btn" class="form-control" style="font-weight: 600; font-size: 0.8rem; color: #FFC700;">
                                                    Proceed To Checkout</button></td>';
                                        }
                                        ?>
                                    </tr>
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Initialize delivery info and UI based on shipping type
    const shippingType = '<?php echo $shipping_type; ?>';
    const subtotal = parseInt($('#subtotal').text()) || 0;
    const shippingFee = parseInt($('#shipping-fee').text()) || 0;
    const selectedPlace = $('#selected-place').val(); // Check if a place is already selected

    if (shippingType === 'pickup') {
        $('#pickup-details').show();
        $('#delivery-autocomplete').hide();
        $('#delivery-info').html(''); // Clear delivery info
        $('#shipping-type').val('pickup');
        $('#shipping-fee').text('0');
        $('#shipping-cost').val('0');
        $('#checkout-btn').prop('disabled', subtotal <= 0);
        updateTotalAmount();
    } else if (shippingType === 'delivery') {
        $('#pickup-details').hide();
        $('#delivery-autocomplete').show();
        // Only show message if no place is selected
        if (!selectedPlace) {
            $('#delivery-info').html('<p style="color: white;">Enter a location to calculate shipping fee</p>');
        } else {
            $('#delivery-info').html(''); // Will be updated by Google Maps script if place is selected
        }
        $('#shipping-type').val('delivery');
        $('#checkout-btn').prop('disabled', shippingFee === 0 || subtotal <= 0);
        updateTotalAmount();
    }

    $('#addcoupon').click(function() {
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
                    window.location.href =
                        'https://chbluxuryempire.com/success?status=completed&tx_ref=<?php echo $saloon; ?>';
                } else if (response === 'half-success') {
                    alert(
                        'Giftcard applied successfully. Please pay up the rest of your invoice with your bank card'
                    );
                    updateValues();
                } else {
                    alert(response);
                    $("#addcoupon").removeAttr("disabled");
                }
            }
        });
    });

    // Toggle delivery options and update shipping type
    $('input[name="delivery_option"]').change(function() {
        const option = $(this).val();
        $('#shipping-type').val(option);
        if (option === 'pickup') {
            $('#pickup-details').show();
            $('#delivery-autocomplete').hide();
            $('#delivery-info').html('');
            $('#shipping-fee').text('0');
            $('#shipping-cost').val('0');
            $('#checkout-btn').prop('disabled', subtotal <= 0);
            updateTotalAmount();
            // Save to session
            $.ajax({
                url: 'update_session.php',
                type: 'POST',
                data: {
                    shipping_type: 'pickup',
                    shipping_fee: 0,
                    selected_place: ''
                },
                success: function(response) {
                    console.log('Session updated: pickup');
                },
                error: function(xhr, status, error) {
                    console.error('Failed to update session for pickup:', error);
                }
            });
        } else if (option === 'delivery') {
            $('#pickup-details').hide();
            $('#delivery-autocomplete').show();
            $('#delivery-info').html(
                '<p style="color: white;">Enter a location to calculate shipping fee</p>');
            $('#shipping-fee').text('0');
            $('#shipping-cost').val('0');
            $('#checkout-btn').prop('disabled', true);
            updateTotalAmount();
            // Save to session
            $.ajax({
                url: 'update_session.php',
                type: 'POST',
                data: {
                    shipping_type: 'delivery',
                    shipping_fee: 0,
                    selected_place: ''
                },
                success: function(response) {
                    console.log('Session updated: delivery');
                },
                error: function(xhr, status, error) {
                    console.error('Failed to update session for delivery:', error);
                }
            });
        }
    });

    // Ensure session is updated before form submission
    $('#paymentForm').on('submit', function(e) {
        const shippingType = $('#shipping-type').val();
        const shippingFee = parseInt($('#shipping-fee').text()) || 0;
        const selectedPlace = $('#selected-place').val();
        $.ajax({
            url: 'update_session.php',
            type: 'POST',
            async: false, // Synchronous to ensure session is updated before redirect
            data: {
                shipping_type: shippingType,
                shipping_fee: shippingFee,
                selected_place: selectedPlace
            },
            success: function(response) {
                console.log('Session updated before form submission');
            },
            error: function(xhr, status, error) {
                console.error('Failed to update session before form submission:', error);
            }
        });
    });

    // Function to update total amount
    function updateTotalAmount() {
        const subtotal = parseInt($('#subtotal').text()) || 0;
        const shippingFee = parseInt($('#shipping-fee').text()) || 0;
        const total = subtotal + shippingFee;
        $('#total-amount').text(total);
        $('#realamount').val(total);
    }

    // Expose function for google_maps_autocomplete to update shipping fee
    window.updateShippingFee = function(cost) {
        $('#shipping-fee').text(cost);
        $('#shipping-cost').val(cost);
        $('#delivery-info').html('');
        updateTotalAmount();
        $('#checkout-btn').prop('disabled', subtotal <= 0);
        // Save shipping fee and selected place to session
        const selectedPlace = $('#selected-place').val();
        $.ajax({
            url: 'update_session.php',
            type: 'POST',
            data: {
                shipping_type: 'delivery',
                shipping_fee: cost,
                selected_place: selectedPlace
            },
            success: function(response) {
                console.log('Session updated with shipping fee and place');
            },
            error: function(xhr, status, error) {
                console.error('Failed to update session with shipping fee:', error);
            }
        });
    };
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

function updateFormAction() {
    var paymentMethod = document.getElementById('paymentMethod').value;
    var form = document.getElementById('paymentForm');
    if (paymentMethod === 'flutterwave') {
        form.action = 'https://checkout.flutterwave.com/v3/hosted/pay';
    } else if (paymentMethod === 'banktransfer') {
        form.action = 'banktransfer.php';
    }
}
</script>

<?php include "footer.php"; ?>