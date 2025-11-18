<?php
session_start();
include('../config/dbconnect.php');
include('../includes/header.php');

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    echo "<p style='text-align:center;margin-top:50px;'>No items to order!</p>";
    exit;
}

$cart_items = $_SESSION['cart'];
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Handle confirmation and save to DB
if (isset($_POST['confirm_order'])) {
    $user_id = $_SESSION['user_id'] ?? NULL; // add user login integration if needed
    $order_query = "INSERT INTO orders (user_id, total) VALUES (?, ?)";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    $item_query = "INSERT INTO order_items (order_id, item_name, price, quantity) VALUES (?, ?, ?, ?)";
    $item_stmt = $conn->prepare($item_query);

    foreach ($cart_items as $item) {
        $name = $item['name'];
        $price = $item['price'];
        $qty = $item['quantity'];
        $item_stmt->bind_param("isdi", $order_id, $name, $price, $qty);
        $item_stmt->execute();
    }

    $stmt->close();
    $item_stmt->close();

    // store order id in session for payment
    $_SESSION['order_id'] = $order_id;

    // Clear cart after saving order
    unset($_SESSION['cart']);

    // Redirect to payment page
    header("Location: payment.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Confirm Order | Food Delivery</title>
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #fff8f5;
}
.container {
  max-width: 700px;
  margin: 60px auto;
  background: white;
  border-radius: 15px;
  padding: 30px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
h1 {
  text-align: center;
  color: #ff6347;
}
.order-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
  border-bottom: 1px solid #eee;
  padding-bottom: 10px;
}
.total {
  text-align: right;
  font-weight: bold;
  font-size: 18px;
}
button {
  background: #28a745;
  color: white;
  border: none;
  padding: 12px 25px;
  border-radius: 8px;
  font-size: 16px;
  cursor: pointer;
}
button:hover {
  background: #218838;
}
</style>
</head>
<body>
<div class="container">
<h1>Confirm Your Order</h1>

<?php foreach ($cart_items as $item): ?>
  <div class="order-item">
    <span><?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>)</span>
    <span>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
  </div>
<?php endforeach; ?>

<p class="total">Total: ₹<?= number_format($total, 2) ?></p>

<form method="POST" style="text-align:center;">
  <button type="submit" name="confirm_order">Proceed to Payment 💳</button>
</form>
</div>
</body>
</html>
