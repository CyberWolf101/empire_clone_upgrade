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
            style="background-color:#FF339A;  color:white; font-size:14px; padding:5px 10px; margin-top:10px;  float:right; border:none;  outline:none;"
            onclick="window.print()">
            <i class="icon-download"></i> Print Receipt</a></p>
    <?php
    include "../connect.php";

    if (isset($_GET['order'])) {
        $order = $_GET['order'];
        $sql = "SELECT * from saloon_orders where id ='$order'";
        $sql2 = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_array($sql2)) {
            $type = $row["bookingtype"];
            $kit = $row["saloonkit"];
            $customername = $row["name"];
            $customerphone = $row["phone"];
            $date = $row["date"];
            $total = $row["total_amount"];
            $location = $row["type"];
        }
    } else {
        header("location:dashboard.php");
    }
    ?>

    <div style="width:80%; height:50vh; margin:auto;">
        <div class="center">
            <p style='text-align:left;'>
                <img src='favicon.png' width='100px' height='80px' style='margin-top:13px;' /><br>
            </p>
        </div>

        <div class="grid2">
            <div>Booking ID: <?php echo $order; ?></div>
            <div>Date: <?php echo $date; ?></div>
            <div>Name: <?php echo $customername; ?></div>
            <div>Phone: <?php echo $customerphone; ?></div>
        </div>

        <div class="overflow-auto">
            <table class='table table-condensed table-hover table-striped' width='300px' border="0" cellspacing='2'
                data-toggle='bootgrid'>
                <thead>
                    <!-- <tr bgcolor='#fff'>
                        <th>Service</th>
                        <th>Price</th>
                    </tr> -->
                </thead>
                <?php
                $sql = "SELECT * FROM appointments where id='$order'";
                $sql2 = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_array($sql2)) {
                    echo "<tr bgcolor='#fff'>
<td width='200px'>" . $row['servicename'] . "</td>
<td width='200px'>&#8358;" . $row['price'] . "</td>
<tr>";

                    if ($kit > 0) {
                        echo "<tr bgcolor='#fff'>
<td width='200px'>Pedicure Spa Kit</td>
<td width='200px'>&#8358; $kit </td>
<tr>";
                    }
                }
                ?>
            </table>

            <table class='table table-condensed table-hover table-striped' width='300px' border="0" cellspacing='2'
                data-toggle='bootgrid'>
                <thead style="border-bottom: 1px solid black;">
                    <th data-column-id='employee_name'>Item</th>
                    <th data-column-id='employee_name'>Quantity</th>
                    <th data-column-id='employee_name'>Price</th>
                </thead>
                <?php
                $sql = "SELECT * from refreshments where orderid='$order'";
                $sql2 = mysqli_query($con, $sql);

                if (mysqli_num_rows($sql2) > 0) {
                    $name = [];
                    $surname = [];
                    $address = [];

                    while ($row = mysqli_fetch_array($sql2)) {
                        echo "<tr bgcolor='#fff'>
<td width='40px'>" . $row['item'] . " " . ($row['preorder'] ? '<div class="pre-order-div">(Pre-order)</div>' : '') . "</td>
<td width='20px'>" . $row['quantity'] . "</td>
<td width='20px'>&#8358;" . $row['totalprice'] . "</td>
<tr>";
                    }
                }
                ?>
            </table>
        </div>
        </p>
        <div>
            <center>
                <div style="font-weight:900;">Grand Total: &#8358;<?php echo $total; ?> </div>
                <div style=""><i>Thank you for your patronage</i></div>
                <a href="dashboard.php" class="con">Done</a>
            </center>
        </div>
    </div>

    <script type="text/javascript">
        window.onload = function () {
            window.print();
        };
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>