<?php
// Calculate total cart item count
$cartItemCount = 0;
if (!empty($cartItems)) {
    foreach ($cartItems as $item) {
        $cartItemCount += (int) $item['quantity'];
    }
}
?>
<button onclick="openCartModal()" id="cartFloatingBtn" class="btn-buya"
    style="position:fixed; bottom:20px; right:20px; border-radius:50%; width:60px; height:60px; font-size:20px; z-index:999; display: flex; align-items: center; justify-content: center;">
    🛒
    <?php if ($cartItemCount > 0): ?>
        <span class="badge bg-danger" style="position:absolute; top:-5px; right:-10px; font-size:10px; border-radius:50%; padding:4xpx 8px;">
            <?php echo $cartItemCount; ?>
        </span>
    <?php endif; ?>
</button>

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background:#000; color:#fff;">
        <h5 class="modal-title">Your Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <?php
      if (!empty($cartItems)) {
        echo "<form method='post' action='foodpage.php'>";
        foreach ($cartItems as $id => $item) {
          $qty = $item['quantity'];
          $preorder = $item['preorder'];
          $itemRes = mysqli_query($con, "SELECT item, price FROM food_menu WHERE s='$id'");
          if ($rowItem = mysqli_fetch_assoc($itemRes)) {
            echo "<div class='d-flex justify-content-between align-items-center mb-2 px-2'>
                    <span>{$rowItem['item']} (₦{$rowItem['price']})";
            if ($preorder) {
              echo " <span class='badge bg-warning text-dark' style='font-size:11px;'>Preorder</span>";
            }
            echo "</span>
                    <div class='d-flex align-items-center'>
                        <input type='number' class='form-control me-2' style='width:70px;' name='qty[$id]' value='$qty' min='1' />
                        <button type='submit' name='deleteItem[$id]' value='1' class='btn btn-sm btn-danger'>x</button>
                    </div>
                  </div>";
          }
        }
        echo "
              <a href='cart.php' class='btn-buya mt-2'>Proceed to payment</a>
              <button type='submit' name='updateCart' class='btn-buya mt-2'>Update Cart</button>
            </form>";
      } else {
        echo "<p>Your cart is empty.</p>";
      }
      ?>
    </div>
  </div>
</div>

<!-- Preorder Modal -->
<div class="modal fade" id="preorderModal" tabindex="-1" aria-labelledby="preorderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background:#000; color:#fff;">
        <h5 class="modal-title" id="preorderModalLabel">Preorder Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="post">
          <p id="preorderItemName"></p>
          <p>This item is out of stock. You can preorder it now!</p>
          <input type="hidden" name="food" id="preorderItemId" value="">
          <input type="hidden" id="preorderItemPrice" value="">
          <label>Quantity</label>
          <input type="number" name="value" id="preorderQty" class="form-control" value="1" min="1" oninput="updateTotalPrice()" />
          <p>Unit Price: ₦<span id="preorderUnitPrice">0</span></p>
          <p>Total Price: ₦<span id="preorderTotalPrice">0</span></p>
          <button type="submit" name="preorder" class="btn-buya mt-2">Confirm Preorder</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function openCartModal() {
    var myModal = new bootstrap.Modal(document.getElementById('cartModal'));
    myModal.show();
  }

  function openPreorderModal(itemName, itemId, itemPrice) {
    console.log("Opening preorder modal: itemId=" + itemId + ", price=" + itemPrice);
    document.getElementById('preorderItemName').innerText = itemName;
    document.getElementById('preorderItemId').value = itemId;
    document.getElementById('preorderItemPrice').value = itemPrice;
    document.getElementById('preorderUnitPrice').innerText = itemPrice;
    document.getElementById('preorderQty').value = 1;
    document.getElementById('preorderTotalPrice').innerText = itemPrice;
    var myModal = new bootstrap.Modal(document.getElementById('preorderModal'));
    myModal.show();
  }

  function updateTotalPrice() {
    const qty = document.getElementById('preorderQty').value;
    const price = document.getElementById('preorderItemPrice').value;
    document.getElementById('preorderTotalPrice').innerText = (qty * price).toFixed(2);
  }
</script>