<?php
session_start();
ob_start();
include('../includes/header.php'); // ✅ correct path


// Ensure the cart exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['id'])) {
        $id = $_POST['id'];

        // If the item doesn't exist, skip
        if (!isset($_SESSION['cart'][$id])) {
            header("Location: cart.php");
            exit;
        }

        switch ($_POST['action']) {
            case 'increase':
                $_SESSION['cart'][$id]['quantity']++;
                break;

            case 'decrease':
                $_SESSION['cart'][$id]['quantity']--;
                if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                    unset($_SESSION['cart'][$id]);
                }
                break;

            case 'remove':
                unset($_SESSION['cart'][$id]);
                break;
        }
    }
}

// Prepare cart items
$cart_items = $_SESSION['cart'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Cart | Food Delivery</title>
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #fff8f5;
}
.container {
  max-width: 900px;
  margin: 50px auto;
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
h2 {
  color: #ff6347;
  text-align: center;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}
th, td {
  padding: 10px;
  text-align: center;
  border-bottom: 1px solid #eee;
}
th {
  background: #ff6347;
  color: white;
}
button {
  background: #ff6347;
  border: none;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}
button:hover {
  background: #e5533d;
}
.remove-btn {
  background: red;
}
.proceed-btn {
  margin-top: 20px;
  background: #28a745;
  padding: 12px 25px;
  font-size: 16px;
  border-radius: 8px;
}
.proceed-btn:hover {
  background: #218838;
}
</style>
</head>
<body>
<div class="container">
  <h2>Your Cart 🛒</h2>

  <?php if (empty($cart_items)): ?>
    <p style="text-align:center;margin-top:40px;">Your cart is empty 😔</p>
  <?php else: ?>
    <table>
      <tr>
        <th>Item</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Action</th>
      </tr>

      <?php
      $grand_total = 0;
      foreach ($cart_items as $key => $item):
          $name = htmlspecialchars($item['name']);
          $price = (float)$item['price'];
          $qty = (int)($item['quantity'] ?? 1);
          $total = $price * $qty;
          $grand_total += $total;
      ?>
      <tr>
        <td><?= $name ?></td>
        <td>₹<?= number_format($price, 2) ?></td>
        <td>
          <form method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?= $key ?>">
            <button type="submit" name="action" value="decrease">−</button>
          </form>
          <?= $qty ?>
          <form method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?= $key ?>">
            <button type="submit" name="action" value="increase">+</button>
          </form>
        </td>
        <td>₹<?= number_format($total, 2) ?></td>
        <td>
          <form method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?= $key ?>">
            <button type="submit" name="action" value="remove" class="remove-btn">Remove</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>

      <tr style="font-weight:bold;">
        <td colspan="3" align="right">Grand Total:</td>
        <td colspan="2">₹<?= number_format($grand_total, 2) ?></td>
      </tr>
    </table>

    <!-- Proceed to Order -->
    <form method="POST" action="orders.php" style="text-align:center;">
      <button type="submit" name="order_from_cart" class="proceed-btn">Proceed to Order ✅</button>
    </form>

  <?php endif; ?>
</div>
</body>
</html>
