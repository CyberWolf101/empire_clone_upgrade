<?php
include "header.php"; // Ensure $con and $isAdmin are available

// Mark all unread inventory_log entries as read for admins
if ($isAdmin) {
    $update_sql = "UPDATE inventory_log SET read_status = 1 WHERE read_status = 0";
    if (!mysqli_query($con, $update_sql)) {
        echo "<script>alert('Error marking logs as read: " . mysqli_error($con) . "');</script>";
    }
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h5 mb-0 text-gray-800">Inventory Log Details</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item"><a href="inventory.php">Inventory</a></li>
        <li class="breadcrumb-item active" aria-current="page">Log Details</li>
    </ol>
</div>

<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Inventory Change Log</h6>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-items-center table-flush text-primary" id="dataTable">
                    <thead class="thead-light">
                        <tr>
                            <th>Change Date</th>
                            <th>Changed By</th>
                            <th>Action</th>
                            <th>Product Name</th>
                            <th>Old Product Name</th>
                            <th>New Product Name</th>
                            <th>Old Pack Qty</th>
                            <th>New Pack Qty</th>
                            <th>Old Packs</th>
                            <th>New Packs</th>
                            <th>Old Pieces</th>
                            <th>New Pieces</th>
                            <th>Old Inventory</th>
                            <th>New Inventory</th>
                            <th>Old Department</th>
                            <th>New Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT il.*, 
                               COALESCE(od.name, il.old_department, '-') AS old_dept_name,
                               COALESCE(nd.name, il.new_department, '-') AS new_dept_name
                        FROM inventory_log il
                        LEFT JOIN chb_inventory_department od ON il.old_department = od.s
                        LEFT JOIN chb_inventory_department nd ON il.new_department = nd.s
                        ORDER BY il.change_date DESC";
                        $result = mysqli_query($con, $sql);
                        if (!$result) {
                            echo "<tr><td colspan='16' class='text-danger'>Error fetching logs: " . mysqli_error($con) . "</td></tr>";
                        } else {
                            while ($row = mysqli_fetch_array($result)) {
                                $change_date = date("Y-m-d H:i:s", strtotime($row['change_date']));
                                echo "
                                    <tr>
                                        <td>" . htmlspecialchars($change_date) . "</td>
                                        <td>" . htmlspecialchars($row['changed_by']) . "</td>
                                        <td>" . htmlspecialchars(ucfirst($row['action'])) . "</td>
                                        <td>" . htmlspecialchars($row['product_name']) . "</td>
                                        <td>" . ($row['old_productname'] ? htmlspecialchars($row['old_productname']) : '-') . "</td>
                                        <td>" . ($row['new_productname'] ? htmlspecialchars($row['new_productname']) : '-') . "</td>
                                        <td>" . ($row['old_pack_quantity'] !== null ? $row['old_pack_quantity'] : '-') . "</td>
                                        <td>" . ($row['new_pack_quantity'] !== null ? $row['new_pack_quantity'] : '-') . "</td>
                                        <td>" . ($row['old_packs'] !== null ? $row['old_packs'] : '-') . "</td>
                                        <td>" . ($row['new_packs'] !== null ? $row['new_packs'] : '-') . "</td>
                                        <td>" . ($row['old_pieces'] !== null ? $row['old_pieces'] : '-') . "</td>
                                        <td>" . ($row['new_pieces'] !== null ? $row['new_pieces'] : '-') . "</td>
                                        <td>" . ($row['old_inventory'] !== null ? $row['old_inventory'] : '-') . "</td>
                                        <td>" . ($row['new_inventory'] !== null ? $row['new_inventory'] : '-') . "</td>
                                        <td>" . htmlspecialchars($row['old_dept_name']) . "</td>
                                        <td>" . htmlspecialchars($row['new_dept_name']) . "</td>
                                    </tr>";
                            }
                            mysqli_free_result($result);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
table.dataTable th, table.dataTable td {
    font-size: 0.9rem;
    white-space: nowrap;
}
</style>

<script>
(function($) {
    // Prevent multiple initializations using a flag
    if (!window.dataTableInitialized) {
        $(document).ready(function() {
            console.log('Initializing DataTable for inventory_log_details');
            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().destroy();
            }
            $('#dataTable').DataTable({
                "order": [[0, "desc"]], // Sort by Change Date (column 0, descending)
                "pageLength": 10,
                "responsive": true,
                "scrollX": true,
                "paging": true
            });
            window.dataTableInitialized = true;
            console.log('DataTable initialized');
        });
    } else {
        console.log('DataTable already initialized, skipping');
    }
})(jQuery);
</script>

<?php include "footer.php"; ?>