<?php
include "header.php";
$unique_id                      = isset($_GET["customer_unique_id"]) ? $_GET["customer_unique_id"] : "";
$_SESSION["customer_unique_id"] = $unique_id;
setcookie("customer_unique_id", $unique_id, time() + 600, "/");
function getCustomer($con, $id)
{
    $id = mysqli_real_escape_string($con, $id);

    $sql  = "SELECT * FROM customers WHERE unique_id = '$id'";
    $stmt = mysqli_query($con, $sql);

    $customer = mysqli_fetch_assoc($stmt);

    if ($customer) {
        return [
            "status" => true,
            "data"   => $customer,
        ];
    }

    return [
        "status" => false,
        "data"   => null,
    ];
}

$result = getCustomer($con, $_GET["customer_unique_id"]);

$customer  = [];
$discounts = [];
if ($result["status"]) {
    $customer = $result["data"];
}
$discountSQL    = "SELECT * FROM customers_discounts WHERE customer_unique_id = '$unique_id'";
$discountResult = mysqli_query($con, $discountSQL);
while ($row = mysqli_fetch_assoc($discountResult)) {
    $discounts[] = $row;
}
if (isset($_GET["customer_unique_id"]) && getCustomer($con, $_GET["customer_unique_id"])["status"] == true) {
?>
    <?php
    if (isset($_POST["add_discount"])) {
        $productCategory    = $_POST["product_category"];
        $discountPercentage = $_POST["discount_percentage"];
        $discountStatus     = $_POST["discount_status"];
        function getCategory($con, $category, $unique_id)
        {
            $category = mysqli_real_escape_string($con, $category);

            $sql = "SELECT * FROM customers_discounts WHERE product_category = '$category' AND customer_unique_id = '$unique_id'";

            $result = mysqli_query($con, $sql);

            $row = mysqli_fetch_assoc($result);

            if ($row) {
                return true;
            } else {
                return false;
            }
        }

        if (! getCategory($con, $productCategory, $unique_id)) {
            $uniqueID          = $_SESSION["customer_unique_id"];
            $addDiscountSQL    = "INSERT INTO customers_discounts(product_category, discount_percentage, discount_status, customer_unique_id) VALUES ('$productCategory','$discountPercentage','$discountStatus','$unique_id')";
            $addDiscountResult = mysqli_query($con, $addDiscountSQL);
            if ($addDiscountResult) {
                $_SESSION["success"] = "✅ Discount added successfully";
                unset($_SESSION["success"]);
            } else {
                $_SESSION["error"] = "❌ Failed to add discount";
                unset($_SESSION["error"]);
            }
        }
    }

    if (isset($_POST["edit_discount"])) {
        $productCategory    = $_POST["product_category"];
        $discountPercentage = $_POST["discount_percentage"];
        $discountStatus     = $_POST["discount_status"];
        function getCategory($con, $category, $unique_id)
        {
            $category = mysqli_real_escape_string($con, $category);
            $sql      = "SELECT * FROM customers_discounts WHERE product_category = '$category' AND customer_unique_id = '$unique_id'";
            $result   = mysqli_query($con, $sql);
            $row      = mysqli_fetch_assoc($result);
            if ($row) {
                return true;
            } else {
                return false;
            }
        }

        if (getCategory($con, $productCategory, $unique_id)) {
            $uniqueID           = $_SESSION["customer_unique_id"];
            $editDiscountSQL    = "UPDATE customers_discounts SET product_category = '$productCategory', discount_percentage = '$discountPercentage', discount_status = '$discountStatus' WHERE customer_unique_id = '$unique_id'";
            $editDiscountResult = mysqli_query($con, $editDiscountSQL);
            if ($editDiscountResult) {
                $_SESSION["success"] = "✅ Discount edited successfully";
                unset($_SESSION["success"]);
            } else {
                $_SESSION["error"] = "❌ Failed to edit discount";
                unset($_SESSION["error"]);
            }
        }
    }

    if (isset($_POST["delete_discount"])) {
        $id     = $_POST["id"];
        $sql    = "DELETE FROM customers_discounts WHERE id = '$id'";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $_SESSION["success"] = "✅ Discount deleted successfully";
            unset($_SESSION["success"]);
        } else {
            $_SESSION["error"] = "❌ Failed to delete discount";
            unset($_SESSION["error"]);
        }
    }
    if (isset($_POST["edit_customer"])) {
        $name            = $_POST["name"];
        $email           = $_POST["email"];
        $phone           = $_POST["phone"];
        $editCustomerSQL = "UPDATE customers SET name = '$name', email='$email',phone='$phone' WHERE unique_id = '$unique_id'";
        $editSuccess     = mysqli_query($con, $editCustomerSQL);
        if ($editSuccess) {
            $_SESSION["success"] = "✅ Customer edited successfully";
        } else {
            $_SESSION["error"] = "❌ Failed to edit customer";
        }
    }
    if (isset($_POST["customer_id"])) {

        $customerID = mysqli_real_escape_string($con, $_POST["customer_id"]);

        $newStatus = isset($_POST["credit_sales_eligibility"])
            ? "true"
            : "false";

        $sql = "UPDATE customers 
            SET credit_sales_eligibility = '$newStatus'
            WHERE unique_id = '$customerID'";

        mysqli_query($con, $sql);
    }
    ?>
    <style>
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #ccc;
            transition: 0.4s;
            border-radius: 20px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 2px;
            bottom: 2px;
            background: #fff;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background: #FEBF01;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }
    </style>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h5 mb-0 text-gray-800">Edit Customer</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit customer</li>
        </ol>
    </div>
    <div class="col-xl-12 col-lg-12 mb-4">
        <center>
            <div style="text-align: left;">
                <p>Edit customer details, give discounts to customer on food categories and update credit sales eligibility.</p>
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-warning">Customer Details</h6>
                    </div>
                    <?php
                    if (isset($_SESSION["success"])) {
                        echo $_SESSION["success"];
                        unset($_SESSION["success"]);
                    } elseif (isset($_SESSION["error"])) {
                        echo $_SESSION["error"];
                        unset($_SESSION["error"]);
                    }
                    ?>
                    <div class="p-3">
                        <form action="" onsubmit="return confirm('Are you sure you want to edit this customer?')" method="post">
                            <label for="name">Fullname</label>
                            <input type="text" name="name" id="name" value="<?php echo $customer["name"] ?>" class="form-control" <?php if (! $isAdmin) { ?> disabled <?php } ?>>
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" value="<?php echo $customer["email"] ?>" class="form-control" <?php if (! $isAdmin) { ?> disabled <?php } ?>>
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" value="<?php echo $customer["phone"] ?>" class="form-control" <?php if (! $isAdmin) { ?> disabled <?php } ?>>
                            <?php
                            if ($isAdmin) {
                            ?>
                                <button type="submit" name="edit_customer" class="btn btn-primary m-3">Save changes</button>
                            <?php
                            }
                            ?>
                        </form>
                        <!-- 
                        CREDIT SALES ELIGIBILITY
                        -->
                        <!-- <div class="credit-sales-eligibility">
                            <p class="form-text">Credit sales eligibility</p>
                            <form action="" id="credit-sales-eligibility-form" method="post">

                                <input type="hidden"
                                    name="customer_id"
                                    value="<?= $customer["unique_id"] ?>">

                                <label class="toggle-switch">
                                    <input type="checkbox"
                                        name="credit_sales_eligibility"
                                        id="credit-submit"
                                        <?php if ($customer["credit_sales_eligibility"] == "true") { ?> checked <?php } ?>>
                                    <span class="slider"></span>
                                </label>

                            </form>

                            <script>
                                $("#credit-submit").change(function() {
                                    setTimeout(() => {
                                        $("#credit-sales-eligibility-form").submit();
                                    }, 200);
                                });
                            </script>
                        </div> -->
                    </div>
                    <!-- ADD DISCOUNT MODAL -->
                    <div class="modal fade" id="add-discount-modal" tabindex="-1" aria-labelledby="addDiscountModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addDiscountLabel">Add Discount</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <form method="post" id="add-discount-form" action="">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <input type="hidden" name="unique_id" value="<?php echo $unique_id ?>">
                                            <label for="product-category">Product Category</label>
                                            <!-- <input type="text" id="product-category" name="product_category" class="form-control" required> -->
                                            <select name="product_category" id="product-category" class="form-control">
                                                <option value="">---- SELECT CATEGORY ----</option>
                                                <?php
                                                $categories       = [];
                                                $categoriesSQL    = "SELECT * FROM food_categories";
                                                $categoriesResult = mysqli_query($con, $categoriesSQL);
                                                while ($row = mysqli_fetch_assoc($categoriesResult)) {
                                                    $categories[] = $row;
                                                }
                                                foreach ($categories as $cat) {
                                                ?>
                                                    <option value="<?php echo strtolower($cat["name"]) ?>"><?php echo $cat["name"] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="discount_percentage">Discount Percentage(%)</label>
                                            <input type="number" id="discount_percentage" name="discount_percentage" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="discount_status">Discount Status</label>

                                            <select name="discount_status" id="discount_status" class="form-control">
                                                <option value="Active" selected>Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" id="addDiscountButton" name="add_discount" class="btn btn-primary">Add Discount</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- MODAL ENDS -->
                    <!-- EDIT DISCOUNT MODAL -->
                    <div class="modal fade" id="edit-discount-modal" tabindex="-1" aria-labelledby="addDiscountModal" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addDiscountLabel">Edit Discount</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <form method="post" id="edit-discount-form" action="">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <input type="hidden" name="unique_id" value="<?php echo $unique_id ?>">
                                            <label for="product-category">Product Category</label>
                                            <!-- <input type="text" id="product-category" name="product_category" class="form-control" required> -->
                                            <select name="product_category" id="product-category" class="form-control">
                                                <option value="">---- SELECT CATEGORY ----</option>
                                                <?php
                                                $categories       = [];
                                                $categoriesSQL    = "SELECT * FROM food_categories";
                                                $categoriesResult = mysqli_query($con, $categoriesSQL);
                                                while ($row = mysqli_fetch_assoc($categoriesResult)) {
                                                    $categories[] = $row;
                                                }
                                                foreach ($categories as $cat) {
                                                    foreach ($discounts as $dis) {
                                                ?>
                                                        <option value="<?php echo $cat["name"] ?>" <?php if ($cat["name"] == $dis["product_category"]): ?> selected <?php endif; ?>><?php echo $cat["name"] ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="discount_percentage">Discount Percentage(%)</label>
                                            <input type="number" id="discount_percentage" name="discount_percentage" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="discount_status">Discount Status</label>

                                            <select name="discount_status" id="discount_status" class="form-control">
                                                <option value="Active" selected>Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" id="addDiscountButton" name="edit_discount" class="btn btn-primary">Edit Discount</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- MODAL ENDS -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-warning">Customer Discount Details</h6>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-discount-modal">Add Discount</button>
                    </div>
                    <div class="table-responsive p-3">
                        <table class="table table-bordered align-items-center text-primary">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product Category</th>
                                    <th>Discount(%)</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($discounts) > 0) {
                                    foreach ($discounts as $discount) {
                                ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($discount["id"]) ?></td>
                                            <td><?php echo htmlspecialchars($discount["product_category"]) ?></td>
                                            <td><?php echo htmlspecialchars($discount["discount_percentage"]) ?></td>
                                            <td><?php echo htmlspecialchars($discount["discount_status"]) ?></td>
                                            <td class="d-flex">
                                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edit-discount-modal"><i class=' fas fa-edit'></i></button>
                                                <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this discount?')">
                                                    <input type="hidden" name="id" value="<?php echo $discount["id"] ?>">
                                                    <button class="btn btn-outline-danger btn-sm" type="submit" name="delete_discount"><i class='fas fa-trash'></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center;">No discount found for this customer</td>
                                    </tr>
                                <?php
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </center>
    </div>
<?php
} else {
?>
    An error occured
<?php
}
?>