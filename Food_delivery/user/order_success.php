<?php
session_start();
include('../config/dbconnect.php');
include('../includes/header.php');

if (isset($_POST['confirm_order'])) {
    $total = $_POST['total'];
    $items = $_POST['items'];

    // Optional: assume logged-in user
    $user_id = $_SESSION['user_id'] ?? NULL;

    // Insert main order
    $order_query = "INSERT INTO orders (user_id, total) VALUES (?, ?)";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert each order item
    $item_query = "INSERT INTO order_items (order_id, item_name, price, quantity, image) VALUES (?, ?, ?, ?, ?)";
    $item_stmt = $conn->prepare($item_query);

    foreach ($items as $item) {
        $name = $item['name'];
        $price = $item['price'];
        $quantity = $item['quantity'];
        $image = $item['image'];
        $item_stmt->bind_param("isdis", $order_id, $name, $price, $quantity, $image);
        $item_stmt->execute();
    }

    $stmt->close();
    $item_stmt->close();
    $conn->close();

    // Clear cart after placing order
    unset($_SESSION['cart']);
} else {
    echo "<p style='text-align:center;margin-top:50px;'>No order data received!</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Success | Food Delivery</title>
<style>
body { font-family: 'Poppins', sans-serif; background: #fff8f5; text-align:center; }
.container {
  max-width: 600px; margin: 80px auto; background: white;
  border-radius: 15px; padding: 40px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
h1 { color: #28a745; }
p { font-size: 18px; }
a {
  display: inline-block; margin-top: 20px;
  padding: 12px 25px; border-radius: 8px;
  background: #ff6347; color: white; text-decoration: none;
}
a:hover { background: #e5533d; }
</style>
</head>
<body>
<div class="container">
  <h1>✅ Order Placed Successfully!</h1>
  <p>Thank you for ordering with <strong>Food Delivery</strong>.</p>
  <p>Your Order ID: <b>#<?php echo $order_id; ?></b></p>
  <p>Total Amount: <b>₹<?php echo $total; ?></b></p>
  <a href="menu.php">Continue Shopping 🍴</a>
</div>
</body>
</html>
