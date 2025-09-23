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
        margin-top: 10px;
        float: right;
        border: none;
        outline: none;
        text-decoration: none;
    }

    .con:hover {
        background-color: #e02e8a;
    }

    div[style*="width:80%"] {
        width: 100% !important;
        margin: 0;
        background: none;
        display: block;
        justify-content: normal;
        height: auto;
    }

    p[style*="text-align:center"] {
        text-align: center;
        margin: 5px 0;
    }

    img[style*="margin-top:13px"] {
        max-width: 50mm;
        height: auto;
        margin: 5px auto;
        display: block;
    }

    center {
        font-size: 10px;
        margin: 8px 0;
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

    tr[bgcolor="#CCCCCC"] {
        background: #eee;
    }

    tr[bgcolor="#fff"] {
        background: #fff;
    }

    th[style*="width:40px"], td[style*="width:40px"] {
        width: 50%;
        text-align: left;
    }

    th:nth-child(2), td:nth-child(2), th:nth-child(3), td:nth-child(3) {
        width: 25%;
        text-align: right !important;
    }

    .table-condensed th, .table-condensed td,
    .table-hover th, .table-hover td,
    .table-striped th, .table-striped td {
        padding: 2px 4px !important;
        text-align: inherit !important;
    }

    div[style*="width:300px"] {
        width: 100%;
        text-align: center;
        margin-bottom: 8px;
    }

    p[style*="font-weight:900"] {
        font-weight: bold;
        margin: 2px 0;
    }

    p[style*=""] {
        font-style: italic;
        font-size: 9px;
        margin: 2px 0;
    }

    /* Grid for header and customer details */
    .header-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 5px;
        margin: 5px 0;
        font-size: 10px;
    }

    .header-grid span {
        display: block;
        text-align: left;
    }

    .header-grid span:nth-child(2), .header-grid span:nth-child(4) {
        text-align: right;
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
            <i class="icon-download"></i> Print Reciept</a></p>
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

    <div
        style="width:80%; height:50vh; margin:auto; display: flex; flex-direction: column; justify-content: center;">
        <p style='text-align:center;'>
            <img src='favicon.png' width='100px' height='80px' style='margin-top:13px;' /><br>
            <span>CHB Luxury Empire</span>
        </p>
        <center>
            <div class="header-grid">
                <span>Booking ID: <?php echo $order; ?></span>
                <span>Date: <?php echo $date; ?></span>
                <span>Name: <?php echo $customername; ?></span>
                <span>Phone: <?php echo $customerphone; ?></span>
            </div>
        </center>

        <div class="overflow-auto">
            <table class='table table-condensed table-hover table-striped' width='300px' border="0" cellspacing='2'
                data-toggle='bootgrid'>
                <thead>
                    <tr bgcolor="#CCCCCC">
                        <th data-column-id='employee_name' style="width:40px;">Item</th>
                        <th data-column-id='employee_name' style="width:20px;">Quantity</th>
                        <th data-column-id='employee_name' style="width:20px;">Price</th>
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
<td width='40px'>" . $row['item'] . "</td>
<td width='20px'>" . $row['quantity'] . "</td>
<td width='20px'>&#8358;" . $row['totalprice'] . "</td>
<tr>";
                    }
                }
                ?>
            </table>
        </div>
        </p>
        <div style="width:300px;">
            <center>
                <p style="font-weight:900;">Grand Total: &#8358;<?php echo $total; ?> </p>
                <p style=""><i>Thank you for your patronage</i></p>
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