<?php include "header.php"; ?>

<?php
$error = '';
$name = $type = $price = $quantity = $file = '';
$min = 0;
$status = $_SESSION['status'] ?? ''; // Adjust based on your session setup

if (isset($_GET['category'])) {
  $category = mysqli_real_escape_string($con, $_GET['category']);
  $sql = "SELECT * FROM food_menu WHERE s = ?";
  $stmt = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($stmt, "s", $category);
  mysqli_stmt_execute($stmt);
  $sql2 = mysqli_stmt_get_result($stmt);
  if ($row = mysqli_fetch_assoc($sql2)) {
    $id = $row["s"];
    $name = $row["item"];
    $type = $row['type'];
    $price = $row['price'];
    $quantity = $row['quantity'];
    $file = $row['file_name'];
    $special_item = $row['special_item'];
    // Set min quantity for superadmin
    $min = ($status == "superadmin") ? 0 : 1; // Minimum 1 for non-superadmins to prevent negative input
  } else {
    $error = "Item not found.";
  }
  mysqli_stmt_close($stmt);
} else {
  $error = "No item specified.";
}
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Edit <?php echo htmlspecialchars($name ?: 'Item'); ?></h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Orishirishi</li>
  </ol>
</div>

<!-- Row -->
<div class="row">
  <div align="" class="col-lg-12">
    <?php if ($error): ?>
      <p class="text-danger"><?php echo htmlspecialchars($error); ?></p>
    <?php else: ?>
      <?php include "updatefood.php"; ?>
      <div class="arizona">
        <?php
        if ($special_item == 'true') {
        ?>
          <div class="badge bg-danger">special item</div>
          <form action="" method="post">
            <button class="btn btn-primary m-3" type="submit" name="make-as-unspecial-item">Make item ordinary</button>
          </form>
        <?php
        } else {
        ?>
          <form action="" method="post">
            <button class="btn btn-primary m-3" type="submit" name="make-item-special">Make item special</button>
          </form>
        <?php
        }
        ?>
        <form enctype="multipart/form-data" method="post" style="width:100%; margin:auto; text-align:center;">
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>" />
          <input type="hidden" name="action" id="action" value="add" />
          <input type="text" class="form-control" value="<?php echo htmlspecialchars($name); ?>" name="name"
            placeholder="*Name" required /><br />
          <input type="number" class="form-control" value="<?php echo htmlspecialchars($price); ?>" name="price"
            placeholder="*Price" required /><br />

          <p>
            <select class="form-control" name="type" required>
              <option value="">- Select Category -</option>
              <?php
              $sql = "SELECT name FROM food_categories";
              $stmt = mysqli_prepare($con, $sql);
              mysqli_stmt_execute($stmt);
              $sql2 = mysqli_stmt_get_result($stmt);
              while ($row = mysqli_fetch_array($sql2)) {
                $optionValue = $row['name'];
                $selected = ($optionValue === $type) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($optionValue) . '" ' . $selected . '>' . htmlspecialchars($optionValue) . '</option>';
              }
              mysqli_stmt_close($stmt);
              ?>
            </select>
          </p>
          <p><input type="file" name="file" class="form-control" id="customFile" /></p>

          <div class="border border-1 p-3 mb-5">
            <p>
              <label>Current Quantity: <span style="color:teal; font-size:20px;">
                  <?php echo htmlspecialchars($quantity); ?></span> </label>
              <input type="number" class="form-control mb-0" name="quantity_change" min="<?php echo htmlspecialchars($min); ?>"
                placeholder="Quantity to Add/Subtract (optional)" />
            </p>

            <?php if ($isAdmin): ?>
              <p>
              <div class="mb-1 text-secondary">
                <small>choose action:</small>
              </div>
              <button type="button" id="addBtn" class="btn btn-primary active">Add</button>
              <button type="button" id="subtractBtn" class="btn btn-outline-secondary">Subtract</button>
              </p>
            <?php endif; ?>
          </div>

          <input type="submit" name="add" value="Update Item" class="btn btn-primary">
        </form>
      </div>
      <?php
      if ($special_item == 'true') {
      ?>
        <?php
        $ingredients = [];
        $sql = "SELECT * FROM special_items WHERE item_id = '$id'";
        $result = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
          $ingredients[] = $row;
        }
        ?>

        <p class="h3 text-gray-800">Ingredients / Item addition</p>
        <table class="table table-stripped table-bordered">
          <thead class="table-light">
            <tr>
              <th></th>
              <th>Name</th>
              <th>Quantity</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (!count($ingredients) > 0) {
            ?>
              <tr>
                <td colspan="6" style="text-align: center;">No ingredient found</td>
              </tr>
              <?php
            } else {
              foreach ($ingredients as $ingredient) {
                $ingId = $ingredient["ingredient_id"];
                $sql = "SELECT file_name FROM food_menu WHERE s = '$ingId'";
                $result = mysqli_query($con, $sql);
                $imageURL = "";
                while ($row = mysqli_fetch_assoc($result)) {
                  $imageURL = 'https://chbluxuryempire.com/orishirishi/' . $row["file_name"];
                }
              ?>
                <tr>
                  <td align="center"><img src="<?= $imageURL ?>" style="border-radius: 50%;" alt="" width="50" height="50"></td>
                  <td><?= $ingredient["ingredient_name"] ?></td>
                  <td><?= $ingredient["ingredient_quantity"] ?></td>
                  <td>
                    <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this ingredient?')">
                      <input type="hidden" name="ingredient-id" value="<?= $ingredient["ingredient_id"] ?>">
                      <button class="btn btn-danger" type="submit" name="delete-ingredient">Delete ingredient</button>
                    </form>
                  </td>
                </tr>
            <?php
              }
            }
            ?>
          </tbody>
        </table>
        <?php
        ?>
        <!-- INGREDIENTS ADDITION -->
        <p class="h3 text-gray-800">Add Ingredients / Item addition</p>
        <div align="center">
          <form action="" method="post">
            <select name="item_id" id="" class="form-control m-3">
              <option value="">---- SELECT ITEM ----</option>
              <?php
              $items = [];
              $itemSQL = "SELECT * FROM food_menu";
              $result = mysqli_query($con, $itemSQL);
              while ($row = mysqli_fetch_array($result)) {
                $items[] = $row;
              }
              foreach ($items as $item) {
              ?>
                <option value="<?= $item["s"] ?>"><?= $item["item"] ?></option>
              <?php
              }
              ?>
            </select>
            <input type="number" name="quantity" id="" placeholder="*Quantity" class="form-control m-3">
            <button type="submit" name="add-ingredient" class="btn btn-primary m-3">Add Ingredient</button>
          </form>
        </div>
      <?php
      }
      ?>
    <?php endif; ?>
  </div>
</div>

<script>
  // JavaScript to toggle Add/Subtract buttons and update hidden action field
  const addBtn = document.getElementById('addBtn');
  const subtractBtn = document.getElementById('subtractBtn');
  const actionField = document.getElementById('action');

  addBtn.addEventListener('click', () => {
    addBtn.classList.add('btn-primary', 'active');
    addBtn.classList.remove('btn-outline-primary');
    subtractBtn.classList.add('btn-outline-secondary');
    subtractBtn.classList.remove('btn-secondary', 'btn-primary', 'active');
    actionField.value = 'add';
  });

  subtractBtn.addEventListener('click', () => {
    subtractBtn.classList.add('btn-primary', 'active');
    subtractBtn.classList.remove('btn-outline-secondary');
    addBtn.classList.add('btn-outline-primary');
    addBtn.classList.remove('btn-secondary', 'btn-primary', 'active');
    actionField.value = 'subtract';
  });
</script>

<?php include "footer.php"; ?>