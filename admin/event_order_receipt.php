<html>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Courier+Prime&display=swap');

    body {
        font-family: 'Courier Prime', monospace;
        width: 80mm;
        font-size: 10px;
        margin: 0;
        padding: 3mm;
        color: #000;
        background: #fff;
        line-height: 1.2;
    }

    .con {
        background-color: #FF339A;
        color: white;
        font-size: 12px;
        padding: 5px 10px;
        margin: 5px 0;
        border: none;
        outline: none;
        text-decoration: none;
        display: inline-block;
    }

    .con:hover {
        background-color: #e02e8a;
    }

    div[style*="width:80%"] {
        width: 100% !important;
        margin: 0;
        background: none;
        height: auto;
    }

    .center {
        display: block;
        text-align: center;
    }

    p[style*="text-align:left"] {
        text-align: center;
        margin: 5px 0;
    }

    img[style*="margin-top:13px"] {
        max-width: 50mm;
        height: auto;
        margin: 5px auto;
        display: block;
    }

    .grid2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 5px;
        margin: 5px 0;
        font-size: 10px;
    }

    .grid2 div:nth-child(1), .grid2 div:nth-child(3) {
        text-align: left;
    }

    .grid2 div:nth-child(2), .grid2 div:nth-child(4) {
        text-align: right;
    }

    .overflow-auto {
        width: 100%;
        margin-bottom: 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        margin-bottom: 5px;
    }

    th, td {
        padding: 2px 4px;
        border-bottom: 1px dashed #000;
        text-align: left;
    }

    th {
        background: #eee;
        font-weight: bold;
    }

    tr[bgcolor="#fff"] {
        background: #fff;
    }

    thead[style*="border-bottom"] {
        border-bottom: 1px dashed #000 !important;
    }

    td[style*="width:200px"]:nth-child(2), td[style*="width:20px"]:nth-child(2),
    td[style*="width:20px"]:nth-child(3) {
        text-align: right !important;
    }

    td[style*="width:200px"]:first-child, td[style*="width:40px"] {
        width: 50%;
    }

    td[style*="width:200px"]:nth-child(2), td[style*="width:20px"] {
        width: 25%;
    }

    .pre-order-div {
        display: inline;
        font-size: 9px;
        font-style: italic;
        color: #000;
    }

    .table-condensed th, .table-condensed td,
    .table-hover th, .table-hover td,
    .table-striped th, .table-striped td {
        padding: 2px 4px !important;
        text-align: inherit !important;
    }

    div[style*="font-weight:900"] {
        font-weight: bold;
        margin: 2px 0;
    }

    div[style*=""] {
        font-style: italic;
        font-size: 9px;
        margin: 2px 0;
    }

    @media print {
        .con {
            display: none;
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }
    }
</style>

<body>
    <p><a href="#" class="btn btn-primary con"
            style="background-color:#FF339A; color:white; font-size:14px; padding:5px 10px; margin-top:10px; float:right; border:none; outline:none;"
            onclick="window.print()">
            <i class="icon-download"></i> Print Receipt</a></p>
    <?php
    include "../connect.php";

    if (isset($_GET['order'])) {
        $order_ref = $_GET['order'];
        
        // Fetch order details from event_orders
        $sql = "SELECT customer_name, phone_number, email, total_amount, created_at 
                FROM event_orders 
                WHERE order_ref = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $order_ref);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $customer_name = $row['customer_name'];
            $phone_number = $row['phone_number'];
            $email = $row['email'];
            $total_amount = $row['total_amount'];
            $created_at = $row['created_at'];
        } else {
            header("location:dashboard.php");
            exit;
        }
        mysqli_stmt_close($stmt);
    } else {
        header("location:dashboard.php");
        exit;
    }
    ?>

    <div style="width:80%; height:auto; margin:auto;">
        <div class="center">
            <p style='text-align:left;'>
                <img src='favicon.png' width='100px' height='80px' style='margin-top:13px;' /><br>
            </p>
        </div>

        <div class="grid2">
            <div>Order Ref: <?= htmlspecialchars($order_ref, ENT_QUOTES, 'UTF-8') ?></div>
            <div>Date: <?= htmlspecialchars(date('Y-m-d', strtotime($created_at)), ENT_QUOTES, 'UTF-8') ?></div>
            <div>Name: <?= htmlspecialchars($customer_name, ENT_QUOTES, 'UTF-8') ?></div>
            <div>Phone: <?= htmlspecialchars($phone_number ?: '-', ENT_QUOTES, 'UTF-8') ?></div>
            <div>Email: <?= htmlspecialchars($email ?: '-', ENT_QUOTES, 'UTF-8') ?></div>
            <div></div>
        </div>

        <div class="overflow-auto">
            <table class='table table-condensed table-hover table-striped' width='300px' border="0" cellspacing='2'
                data-toggle='bootgrid'>
                <thead style="border-bottom: 1px solid black;">
                    <th data-column-id='item'>Item</th>
                    <th data-column-id='quantity'>Quantity</th>
                    <th data-column-id='price'>Price</th>
                </thead>
                <tbody>
                    <?php
                    // Fetch items from event_order_items
                    $sql = "SELECT item, quantity, totalprice 
                            FROM event_order_items 
                            WHERE orderid = (SELECT id FROM event_orders WHERE order_ref = ?)";
                    $stmt = mysqli_prepare($con, $sql);
                    mysqli_stmt_bind_param($stmt, "s", $order_ref);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr bgcolor='#fff'>
                                    <td width='40px'>" . htmlspecialchars($row['item'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td width='20px'>" . htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td width='20px'>&#8358;" . number_format($row['totalprice'], 2) . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr bgcolor='#fff'>
                                <td colspan='3' class='text-center'>No items found for this order.</td>
                              </tr>";
                    }
                    mysqli_stmt_close($stmt);
                    ?>
                </tbody>
            </table>
        </div>
        <div>
            <center>
                <div style="font-weight:900;">Grand Total: &#8358;<?= number_format($total_amount, 2) ?></div>
                <div style=""><i>Thank you for your patronage</i></div>
                <a href="dashboard.php" class="con">Done</a>
            </center>
        </div>
    </div>

    <script type="text/javascript">
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>