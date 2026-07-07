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
    $min = ($status == "superadmin") ? 0 : 1;
  } else {
    $error = "Item not found.";
  }
  mysqli_stmt_close($stmt);
} else {
  $error = "No item specified.";
}
?>

<style>
  /* Toggle Component Styling Tokens */
  .toggle-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    display: inline-flex;
    flex-direction: column;
    gap: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  .toggle-title {
    font-family: system-ui, -apple-system, sans-serif;
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
  }

  .toggle-layout-container {
    display: inline-flex;
    align-items: center;
    gap: 16px;
  }

  .toggle-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
  }

  .stunning-toggle {
    --switch-width: 42px;
    --switch-height: 24px;
    --circle-size: 18px;
    --transition-speed: 0.28s;
    --bg-off: #e0e0e0;
    --bg-on: linear-gradient(135deg, #ffcc00, #ff9900);
    --circle-color: #ffffff;
    --glow-color: rgba(255, 153, 0, 0.35);
    display: inline-block;
    width: var(--switch-width);
    height: var(--switch-height);
    position: relative;
    cursor: pointer;
  }

  .stunning-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
  }

  .stunning-toggle .slider {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--bg-off);
    border-radius: var(--switch-height);
    transition: background var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.08);
  }

  .stunning-toggle .slider::before {
    content: "";
    position: absolute;
    height: var(--circle-size);
    width: var(--circle-size);
    left: 3px;
    bottom: 3px;
    background-color: var(--circle-color);
    border-radius: 50%;
    transition: transform var(--transition-speed) cubic-bezier(0.25, 1, 0.5, 1), width var(--transition-speed) ease;
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.12);
  }

  .stunning-toggle input:checked+.slider {
    background: var(--bg-on);
    box-shadow: 0 6px 12px var(--glow-color), inset 0 1px 2px rgba(0, 0, 0, 0.05);
  }

  .stunning-toggle input:checked+.slider::before {
    transform: translateX(calc(var(--switch-width) - var(--circle-size) - 6px));
  }

  .stunning-toggle:active .slider::before {
    width: calc(var(--circle-size) + 4px);
  }

  .stunning-toggle input:checked:active+.slider::before {
    transform: translateX(calc(var(--switch-width) - var(--circle-size) - 10px));
  }

  .toggle-status-text {
    font-family: system-ui, -apple-system, sans-serif;
    font-size: 10px;
    font-weight: 700;
    color: #888888;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    margin: 0;
    user-select: none;
    transition: color 0.2s ease;
  }

  .toggle-status-text.text-active {
    color: #e68a00;
  }

  .status-icon-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }

  @keyframes popIn {
    0% {
      transform: scale(0);
      opacity: 0;
    }

    100% {
      transform: scale(1);
      opacity: 1;
    }
  }

  /* Core Layout Custom Adjustments */
  .ingredient-scroll-box {
    max-height: 380px;
    overflow-y: auto;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    padding: 10px;
    background: #f8f9fc;
  }
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Edit <?php echo htmlspecialchars($name ?: 'Item'); ?></h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Orishirishi</li>
  </ol>
</div>

<div class="row">
  <div class="col-lg-12">
    <?php if ($error): ?>
      <p class="text-danger"><?php echo htmlspecialchars($error); ?></p>
    <?php else: ?>
      <?php include "updatefood.php"; ?>

      <div class="row">

        <div class="col-xl-5 col-lg-6 mb-4">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex d-lg-none justify-content-between align-items-center bg-white border-bottom">
              <h6 class="m-0 fw-semibold text-dark text-uppercase tracking-wide" style="font-size: 0.85rem;">
                Item General Details
              </h6>
              <a href="#ingredientsPanel" class="btn btn-sm btn-outline-primary shadow-sm">
                <i class="bi bi-gear me-1"></i> Ingredient Management
              </a>
            </div>
            <div class="card-body">
              <div class="mb-4 text-start">
                <form action="toggle-special.php" method="post" class="special-item-form">
                  <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['category']) ?>">
                  <input type="hidden" name="toggle-special-status" value="1">

                  <div class="toggle-card">
                    <h3 class="toggle-title">Menu feature</h3>
                    <div class="toggle-layout-container">
                      <div class="toggle-wrapper">
                        <label class="stunning-toggle">
                          <input type="checkbox" name="is_special" value="true" <?php echo ($special_item === 'true') ? 'checked' : ''; ?> onChange="this.form.submit()" />
                          <span class="slider"></span>
                        </label>
                        <p class="toggle-status-text <?php echo ($special_item === 'true') ? 'text-active' : ''; ?>">
                          <?php echo ($special_item === 'true') ? "Item is comprised of other items" : "Item is a standard Item"; ?>
                        </p>
                      </div>
                      <?php if ($special_item === 'true'): ?>
                        <div class="status-icon-badge">
                          <i class="fa fa-star text-warning fs-2" title="Special Item"></i>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </form>
              </div>

              <form enctype="multipart/form-data" method="post" class="text-start">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>" />
                <input type="hidden" name="action" id="action" value="add" />

                <div class="form-group mb-3">
                  <label class="form-label font-weight-bold text-secondary small">* Item Name</label>
                  <input type="text" class="form-control" value="<?php echo htmlspecialchars($name); ?>" name="name" placeholder="Name" required />
                </div>

                <div class="form-group mb-3">
                  <label class="form-label font-weight-bold text-secondary small">* Price</label>
                  <input type="number" class="form-control" value="<?php echo htmlspecialchars($price); ?>" name="price" placeholder="Price" required />
                </div>

                <div class="form-group mb-3">
                  <label class="form-label font-weight-bold text-secondary small">* Category Selection</label>
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
                </div>

                <div class="form-group mb-4">
                  <label class="form-label font-weight-bold text-secondary small">Replace Menu Image</label>
                  <input type="file" name="file" class="form-control" id="customFile" />
                </div>

                <div class="card bg-light p-3 mb-4 border-left-info">
                  <div class="form-group mb-0">
                    <label class="d-block mb-2">Current Quantity: <span class="badge badge-teal style" style="color:teal; font-size:18px; font-weight:700;"><?php echo htmlspecialchars($quantity); ?></span></label>
                    <input type="number" class="form-control mb-2" name="quantity_change" min="<?php echo htmlspecialchars($min); ?>" placeholder="Quantity adjustment value" />
                  </div>

                  <?php if ($isAdmin): ?>
                    <div class="mt-2">
                      <div class="mb-1 text-secondary"><small>Choose action mode:</small></div>
                      <div class="btn-group w-100" role="group">
                        <button type="button" id="addBtn" class="btn btn-primary active w-50">Add</button>
                        <button type="button" id="subtractBtn" class="btn btn-outline-secondary w-50">Subtract</button>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
                <input type="submit" name="add" value="Update Item Properties" class="btn btn-primary btn-block btn-lg shadow-sm">
              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-7 col-lg-6 mb-4" id="ingredientsPanel">
          <?php if ($special_item == 'true'): ?>
            <?php
            $ingredients = [];
            $sql = "SELECT * FROM special_items WHERE item_id = '$id'";
            $result = mysqli_query($con, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
              $ingredients[] = $row;
            }
            ?>

            <div class="card shadow mb-4">
              <div class="card-header py-3 bg-gray-100">
                <h6 class="m-0 font-weight-bold text-dark">Ingredients Management Panel</h6>
              </div>
              <div class="card-body">

                <p class="h5 font-weight-bold text-gray-800 mb-3"><i class="fa fa-list-alt mr-2 text-primary"></i>Current Ingredients Attached</p>
                <div class="ingredient-scroll-box mb-4">
                  <table class="table table-striped table-bordered align-middle mb-0 bg-white">
                    <thead class="table-dark">
                      <tr>
                        <th width="70" class="text-center">Image</th>
                        <th>Ingredient Name</th>
                        <th width="100">Quantity</th>
                        <th width="110" class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!count($ingredients) > 0): ?>
                        <tr>
                          <td colspan="4" class="text-center text-muted py-4">No companion ingredients attached to this composite item yet.</td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($ingredients as $ingredient):
                          $ingId = $ingredient["ingredient_id"];
                          $sql = "SELECT file_name FROM food_menu WHERE s = '$ingId'";
                          $result = mysqli_query($con, $sql);
                          $imageURL = "";
                          while ($row = mysqli_fetch_assoc($result)) {
                            $imageURL = 'https://chbluxuryempire.com/orishirishi/' . $row["file_name"];
                          }
                        ?>
                          <tr>
                            <td class="text-center"><img src="<?= $imageURL ?>" class="img-profile rounded-circle" alt="" width="40" height="40" style="object-fit:cover;"></td>
                            <td class="font-weight-bold text-secondary"><?= htmlspecialchars($ingredient["ingredient_name"]) ?></td>
                            <td><span class="badge badge-dark p-2"><?= htmlspecialchars($ingredient["ingredient_quantity"]) ?></span></td>
                            <td class="text-center">
                              <form action="delete-ingredient.php" method="post" onsubmit="return confirm('Are you sure you want to detach this ingredient?')">
                                <input type="hidden" name="category" value="<?= $category ?>">
                                <input type="hidden" name="ingredient-id" value="<?= $ingredient["ingredient_id"] ?>">
                                <button class="btn btn-sm btn-danger shadow-sm" type="submit" name="delete-ingredient"><i class="fa fa-trash mr-1"></i></button>
                              </form>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>

                <hr class="my-4">

                <p class="h5 font-weight-bold text-gray-800 mb-3"><i class="fa fa-plus-circle mr-2 text-success"></i>Attach New Component</p>
                <div class="p-3 bg-light border rounded">
                  <form action="edit-food.php" method="post" class="row g-3 align-items-end">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['category']) ?>">

                    <div class="col-md-6 mb-2">
                      <label class="form-label small font-weight-bold text-muted">Select Target Item</label>
                      <select name="item_id" class="form-control" required>
                        <option value="">-- Choose Menu Item --</option>
                        <?php
                        $items = [];
                        $itemSQL = "SELECT * FROM food_menu";
                        $result = mysqli_query($con, $itemSQL);
                        while ($row = mysqli_fetch_array($result)) {
                          $items[] = $row;
                        }
                        foreach ($items as $item) {
                          echo '<option value="' . htmlspecialchars($item["s"]) . '">' . htmlspecialchars($item["item"]) . '</option>';
                        }
                        ?>
                      </select>
                    </div>

                    <div class="col-md-3 mb-2">
                      <label class="form-label small font-weight-bold text-muted">Quantity</label>
                      <input type="number" name="quantity" placeholder="Qty" class="form-control" required>
                    </div>

                    <div class="col-md-3 mb-2">
                      <button type="submit" name="add-ingredient" class="btn btn-success btn-block"><i class="fa fa-plus mr-1"></i>Add</button>
                    </div>
                  </form>
                </div>

              </div>
            </div>
          <?php else: ?>
            <div class="card bg-light border-left-warning text-dark p-4 shadow-sm">
              <div class="d-flex align-items-center">
                <i class="fa fa-info-circle fa-2x text-warning mr-3"></i>
                <div>
                  <h5 class="font-weight-bold mb-1">Standard Item Layout Mode</h5>
                  <p class="mb-0 small text-secondary">To unlock complex recipe compound controls, recipes lists, and ingredient tracking modules for this dish, please toggle the <strong>"Menu feature"</strong> switch active.</p>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>

      </div>

    <?php endif; ?>
  </div>
</div>

<script>
  const addBtn = document.getElementById('addBtn');
  const subtractBtn = document.getElementById('subtractBtn');
  const actionField = document.getElementById('action');

  if (addBtn && subtractBtn) {
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
  }
</script>

<?php include "footer.php"; ?>