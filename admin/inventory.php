<?php include "header.php"; ?>

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

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h5 mb-0 text-gray-800">Inventory Items</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Inventory</li>
    </ol>
</div>

<!-- Row -->
<div class="row">
    <div align="center" class="col-lg-12">
        <?php include "addinventory.php"; ?>
        <p><button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                data-bs-target="#addInventoryModal">Add New Inventory Item</button></p>
        <div class="modal fade" id="addInventoryModal" tabindex="-1" aria-labelledby="addInventoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header" style="background:#000; color:#fff;">
                        <h5 class="modal-title" id="addInventoryModalLabel">Add New Inventory Item</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form enctype="multipart/form-data" method="post"
                            style="width:100%; margin:auto; text-align:left;">
                            <input type="text" class="form-control" name="name" placeholder="*Name" required /><br />
                            <input type="text" class="form-control" name="product-value" id="productName"
                                placeholder="*Enter product value (eg bags,carton,boxes)" required /><br />
                            <label id="perPackLabel">How many per pack?</label><input type="number" class="form-control"
                                name="pack-quantity" required /><br />
                            <label id="packsLabel">How many packs are available?</label><input type="number"
                                class="form-control" name="packs" required /><br />
                            <input type="number" class="form-control" name="pieces"
                                placeholder="Any Extra Pieces?" /><br />
                            <p><select class="form-control" name="department" required>
                                    <option value="" selected>- Select Department -</option>
                                    <?php
                                    $sql = "SELECT s, name FROM chb_inventory_department";
                                    $sql2 = mysqli_query($con, $sql);
                                    while ($row = mysqli_fetch_array($sql2)) {
                                        echo '<option value="' . $row['s'] . '">' . $row['name'] . '</option>';
                                    } ?>
                                </select></p>
                            <input type="submit" name="register" value="Upload Item" class="btn btn-primary w-100">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#productName').on('input', function () {
                var productValue = $(this).val();
                $('#perPackLabel').text('How many per ' + productValue + '?');
                $('#packsLabel').text('How many ' + productValue + ' are available?');
            });
        });
    </script>

    <!-- Datatables -->
    <div class="col-lg-12" style="margin-top:2%;">
        <div class=" mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Inventory Items</h6>
            </div>
            <div class="p-3" style="overflow:scroll">
                <table class="table align-items-center table-flush text-primary" id="dataTable">
                    <thead class="thead-light">
                        <tr>
                            <th>Item</th>
                            <th>Department</th>
                            <th>Total Inventory</th>
                            <th>Inventory Deducted</th>
                            <th>Inventory Available</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT ci.*, cd.name,
                               SUM(CASE WHEN cih.action = 'added' THEN cih.quantity ELSE 0 END) AS total_inventory,
                               SUM(CASE WHEN cih.action = 'deducted' THEN cih.quantity ELSE 0 END) AS total_deducted
                        FROM chb_inventory ci
                        LEFT JOIN chb_inventory_department cd ON ci.department = cd.s
                        LEFT JOIN chb_inventory_history cih ON ci.product = cih.product
                        GROUP BY ci.s
                        ORDER BY ci.productname";

                        $sql2 = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_array($sql2)) {
                            $department = $row['department'];
                            $disabled = $isAdmin ? '' : 'disabled';
                            // superadmin can reduce values, others cannot
                        
                            $minPackQty = $isSuperAdmin ? 0 : $row['pack_quantity'];
                            $minPacks = $isSuperAdmin ? 0 : $row['packs'];
                            $minPieces = $isSuperAdmin ? 0 : $row['pieces'];

                            echo "
                                <tr>
                                    <td>" . htmlspecialchars($row['productname']) . "</td>
                                    <td>" . htmlspecialchars($row['name']) . "</td>	
                                    <td>" . $row['total_inventory'] . "</td>
                                    <td class='text-danger'>" . $row['total_deducted'] . "</td>
                                    <td class='text-success'>" . $row['inventory'] . "</td>
                                    <td>
                                        <button type='button' onclick=\"openCustomModal('customModal" . $row['s'] . "')\" class='btn btn-sm btn-primary'>Update</button>
                                    </td>
                                    <td>
                                        <form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this item (" . htmlspecialchars($row['name']) . ")?\");'>
                                            <input type='text' name='categoryid' value='" . $row['s'] . "' required hidden>  
                                            <input type='submit' name='delete' value='Delete' class='btn btn-sm btn-danger' $disabled>
                                        </form>
                                    </td>
                                </tr>";
                            echo '
    <div class="custom-modal" id="customModal' . $row['s'] . '">
        <div class="custom-modal-dialog">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <h6>Edit Item</h6>
                    <button type="button" class="custom-modal-close" onclick="closeCustomModal(\'customModal' . $row['s'] . '\')">&times;</button>
                </div>
                <div class="custom-modal-body">
                    <form id="form' . $row['s'] . '" name="form' . $row['s'] . '" action="" method="post" enctype="multipart/form-data"> 
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <p>
                                    <input type="text" name="name" class="form-control" 
                                        value="' . htmlspecialchars($row['productname']) . '" 
                                        placeholder="Name" required>
                                </p>

                                <p>
                                    <label id="perPackLabel' . $row['s'] . '">
                                        How many per ' . htmlspecialchars($row['product_value']) . '?
                                    </label>
                                    <input type="number" class="form-control" 
                                        value="' . $row['pack_quantity'] . '" 
                                        min="' . $minPackQty . '" name="pack-quantity" required>
                                </p>

                                <!-- Packs -->
                                <div class="mb-3 border border-2 p-3 rounded">
                                    <p><strong>Currently Available:</strong> <span class="bold-num">' . $row['packs'] . '</span> ' . htmlspecialchars($row['product_value']) . '</p>
                                    <label id="packsLabel' . $row['s'] . '">Enter new value</label>
                                    <input type="number" min="0" 
                                        class="form-control" name="packs" placeholder="Enter value">
                                         <input type="hidden" name="packs_mode" value="add"> <!-- 👈 hidden mode -->

                                          ' . ($isAdmin ? '
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-primary btn-pack-add">Add</button>
                                        <button type="button" class="btn btn-outline-primary btn-pack-subtract">Subtract</button>
                                    </div>
                                     ' : '') . '
                                </div>

                                <!-- Pieces -->
                                <div class="mb-3 border border-2 p-3 rounded">
                                    <p><strong>Currently Available:</strong> <span class="bold-num" > ' . $row['pieces'] . ' </span> pieces</p>
                                    <label>Enter new pieces</label>
                                    <input type="number" class="form-control" 
                                        min="0" name="pieces" placeholder="Enter pieces">
                                         <input type="hidden" name="pieces_mode" value="add"> <!-- 👈 hidden mode -->

                                            ' . ($isAdmin ? '
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-primary btn-pieces-add">Add</button>
                                        <button type="button" class="btn btn-outline-primary btn-pieces-subtract">Subtract</button>
                                    </div>
                                      ' : '') . '
                                </div>

                                <p>
                                    <select class="form-control" name="department" required>
                                        <option value="" selected>- Select Department -</option>';
                            $sqb = "SELECT s, name FROM chb_inventory_department";
                            $sqb2 = mysqli_query($con, $sqb);
                            while ($rows = mysqli_fetch_array($sqb2)) {
                                $selected = ($department == $rows['s']) ? "selected" : "";
                                echo "<option value=\"" . $rows['s'] . "\" $selected>" . htmlspecialchars($rows['name']) . "</option>";
                            }
                            echo '</select>
                                </p>

                                <p>
                                    <input type="hidden" name="id" class="form-control" value="' . $row['product'] . '" required>
                                </p>
                                <p>
                                    <input id="submit' . $row['s'] . '" name="update_store" 
                                        class="btn btn-sm btn-primary shadow-sm w-100" 
                                        type="submit" value="Update Details">
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>';

                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    function openCustomModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
            console.log('Opened modal: ' + modalId);
        } else {
            console.error('Modal not found: ' + modalId);
        }
    }
    function closeCustomModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            console.log('Closed modal: ' + modalId);
        } else {
            console.error('Modal not found: ' + modalId);
        }
    }
    document.addEventListener('click', function (event) {
        const modal = event.target.closest('.custom-modal');
        if (modal && modal.style.display === 'block' && event.target.classList.contains('custom-modal')) {
            closeCustomModal(modal.id);
        }
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        // function setupToggle(buttonAdd, buttonSubtract, inputField) {
        //     let mode = "add"; // default

        //     // Toggle active state
        //     buttonAdd.addEventListener('click', function () {
        //         mode = "add";
        //         buttonAdd.classList.add('btn-primary');
        //         buttonAdd.classList.remove('btn-outline-primary');
        //         buttonSubtract.classList.remove('btn-primary');
        //         buttonSubtract.classList.add('btn-outline-primary');
        //         inputField.dataset.mode = "add";
        //         hiddenField.value = "add";  // 👈 update hidden input
        //     });

        //     buttonSubtract.addEventListener('click', function () {
        //         mode = "subtract";
        //         buttonSubtract.classList.add('btn-primary');
        //         buttonSubtract.classList.remove('btn-outline-primary');
        //         buttonAdd.classList.remove('btn-primary');
        //         buttonAdd.classList.add('btn-outline-primary');
        //         inputField.dataset.mode = "subtract";
        //         hiddenField.value = "subtract";  // 👈 update hidden input
        //     });
        // }

        function setupToggle(buttonAdd, buttonSubtract, inputField, hiddenField) {
            let mode = "add"; // default

            // Add mode
            buttonAdd.addEventListener('click', function () {
                mode = "add";
                buttonAdd.classList.add('btn-primary');
                buttonAdd.classList.remove('btn-outline-primary');
                buttonSubtract.classList.remove('btn-primary');
                buttonSubtract.classList.add('btn-outline-primary');
                inputField.dataset.mode = "add";
                hiddenField.value = "add";  // 👈 update hidden input
            });

            // Subtract mode
            buttonSubtract.addEventListener('click', function () {
                mode = "subtract";
                buttonSubtract.classList.add('btn-primary');
                buttonSubtract.classList.remove('btn-outline-primary');
                buttonAdd.classList.remove('btn-primary');
                buttonAdd.classList.add('btn-outline-primary');
                inputField.dataset.mode = "subtract";
                hiddenField.value = "subtract";  // 👈 update hidden input
            });
        }


        // Attach to every modal dynamically
        document.querySelectorAll('.custom-modal').forEach(modal => {
            const btnPackAdd = modal.querySelector('.btn-pack-add');
            const btnPackSubtract = modal.querySelector('.btn-pack-subtract');
            const inputPacks = modal.querySelector('input[name="packs"]');
            const hiddenPacksMode = modal.querySelector('input[name="packs_mode"]');

            const btnPiecesAdd = modal.querySelector('.btn-pieces-add');
            const btnPiecesSubtract = modal.querySelector('.btn-pieces-subtract');
            const inputPieces = modal.querySelector('input[name="pieces"]');
            const hiddenPiecesMode = modal.querySelector('input[name="pieces_mode"]');

            if (btnPackAdd && btnPackSubtract && inputPacks && hiddenPacksMode) {
                setupToggle(btnPackAdd, btnPackSubtract, inputPacks, hiddenPacksMode);
            }
            if (btnPiecesAdd && btnPiecesSubtract && inputPieces && hiddenPiecesMode) {
                setupToggle(btnPiecesAdd, btnPiecesSubtract, inputPieces, hiddenPiecesMode);
            }
        });

    });
</script>


<?php include "footer.php"; ?>