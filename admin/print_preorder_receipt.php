<?php
include "../connect.php";

if (!isset($_GET['itemid']) || empty($_GET['itemid'])) {
    echo "<script>alert('Invalid item ID.'); window.location.href='viewpreorders.php';</script>";
    exit;
}

$item_id = $_GET['itemid'];
$sql = "SELECT r.item, r.quantity, r.totalprice, r.pre_order_complete, so.name, so.id AS orderid 
        FROM refreshments r 
        JOIN saloon_orders so ON r.orderid = so.id 
        WHERE r.s = ? AND r.preorder = 1";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $item_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo "<script>alert('No pre-order item found for the provided ID.'); window.location.href='viewpreorders.php';</script>";
    mysqli_stmt_close($stmt);
    exit;
}

$row = mysqli_fetch_assoc($result);
$item = htmlspecialchars($row['item'], ENT_QUOTES, 'UTF-8');
$quantity = htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8');
$totalprice = htmlspecialchars($row['totalprice'], ENT_QUOTES, 'UTF-8');
$pre_order_complete = $row['pre_order_complete'];
$customername = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
$orderid = htmlspecialchars($row['orderid'], ENT_QUOTES, 'UTF-8');
$completion_status = $pre_order_complete ? 'Completed' : 'Not Completed';
mysqli_stmt_close($stmt);
?>

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

    .grid2 div:nth-child(1) {
        text-align: left;
    }

    .grid2 div:nth-child(2) {
        text-align: right;
    }

    .completion-status {
        grid-column: 1 / -1;
        text-align: center;
        font-weight: bold;
    }

    .overflow-auto {
        width: 100%;
        margin-bottom: 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
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

    td:nth-child(2), td:nth-child(3) {
        text-align: right !important;
    }

    td:first-child {
        width: 50%;
    }

    td:nth-child(2), td:nth-child(3) {
        width: 25%;
    }

    .pre-order-label {
        display: inline;
        font-size: 9px;
        font-style: italic;
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
    <div style="width:80%; height:50vh; margin:auto;">
        <div class="center">
            <p style='text-align:left;'>
                <img src='favicon.png' width='100px' height='80px' style='margin-top:13px;' /><br>
                <span>CHB Luxury Empire</span>
            </p>
        </div>

        <div class="grid2">
            <div>Booking ID: <?php echo $orderid; ?></div>
            <div>Name: <?php echo $customername; ?></div>
            <div class="completion-status">Completion Status: <?php echo $completion_status; ?></div>
        </div>

        <div class="overflow-auto">
            <table class='table table-condensed table-hover table-striped' width='300px' border="0" cellspacing='2'>
                <thead>
                    <tr bgcolor='#fff'>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tr bgcolor='#fff'>
                    <td><?php echo $item . " <span class='pre-order-label'>(Pre-order)</span>"; ?></td>
                    <td><?php echo $quantity; ?></td>
                    <td>&#8358;<?php echo $totalprice; ?></td>
                </tr>
            </table>
        </div>
        </p>
        <div>
            <center>
                <div style="font-weight:900;">Grand Total: &#8358;<?php echo $totalprice; ?></div>
                <div style=""><i>Thank you for your patronage</i></div>
                <a href="viewpreorders.php" class="con">Done</a>
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