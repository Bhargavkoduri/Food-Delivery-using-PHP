<?php
session_start();
include('../includes/header.php');
include('../config/dbconnect.php');

// Get selected category (if any)
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Fetch items
if ($category === '') {
  $query = "SELECT * FROM menu_item ORDER BY category, id ASC";
} else {
  $query = "SELECT * FROM menu_item WHERE category = '$category' ORDER BY id ASC";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $category ? htmlspecialchars($category) . ' Menu' : 'Full Menu'; ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #fff8f5;
      color: #333;
      margin: 0;
      padding: 0;
    }

    .menu-container {
      max-width: 1200px;
      margin: 60px auto;
      padding: 0 20px;
    }

    h1 {
      text-align: center;
      margin-bottom: 40px;
      color: #ff6347;
    }

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
    }

    .menu-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.3s, box-shadow 0.3s;
      text-align: center;
      padding-bottom: 20px;
    }

    .menu-card:hover {
      transform: scale(1.03);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .menu-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .menu-card h3 {
      margin: 15px 0 5px;
      font-size: 20px;
    }

    .menu-card p {
      color: #777;
      margin-bottom: 10px;
      min-height: 45px;
    }

    .menu-card span {
      font-weight: bold;
      color: #ff6347;
      font-size: 18px;
    }

    .category-heading {
      font-size: 28px;
      margin: 50px 0 20px;
      color: #222;
      border-left: 5px solid #ff6347;
      padding-left: 10px;
    }

    .qty-control {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      margin-top: 15px;
    }

    .qty-btn {
      background: #ff6347;
      border: none;
      color: white;
      width: 35px;
      height: 35px;
      border-radius: 50%;
      font-size: 20px;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
    }

    .qty-btn:hover { background: #e5533c; }

    .qty-val {
      font-weight: bold;
      font-size: 18px;
      width: 25px;
      text-align: center;
    }

    /* 🟢 Order Now button */
    .order-btn {
      background: #28a745;
      border: none;
      color: white;
      padding: 10px 20px;
      border-radius: 25px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 15px;
      transition: background 0.3s ease;
    }

    .order-btn:hover { background: #218838; }

    #cart-count {
      background: #ff6347;
      color: white;
      border-radius: 50%;
      padding: 5px 10px;
      font-size: 14px;
      margin-left: 5px;
    }
  </style>
</head>

<body>

<div class="menu-container">
  <h1>
    <?php echo $category ? htmlspecialchars($category) . ' Varieties' : 'Our Full Menu'; ?>
  </h1>

  <?php
  if ($result && $result->num_rows > 0) {

    if ($category === '') {
      $current_category = '';
      while ($row = $result->fetch_assoc()) {
        if ($current_category !== $row['category']) {
          if ($current_category !== '') echo "</div>";
          $current_category = $row['category'];
          echo "<h2 class='category-heading'>{$current_category}</h2><div class='menu-grid'>";
        }

        $item_id = $row['id'];
        $current_qty = isset($_SESSION['cart'][$item_id]) ? $_SESSION['cart'][$item_id]['quantity'] : 0;

        echo "
        <div class='menu-card'>
          <img src='../assets/img/menu/{$row['image']}' alt='{$row['item_name']}' loading='lazy'>
          <h3>{$row['item_name']}</h3>
          <p>{$row['description']}</p>
          <span>₹{$row['price']}</span>

          <div class='qty-control' data-id='{$row['id']}' data-name=\"{$row['item_name']}\" data-price='{$row['price']}'>
            <button class='qty-btn minus'>−</button>
            <span class='qty-val'>{$current_qty}</span>
            <button class='qty-btn plus'>+</button>
          </div>

          <form action='order_now.php' method='POST'>
            <input type='hidden' name='item_id' value='{$row['id']}'>
            <input type='hidden' name='item_name' value=\"{$row['item_name']}\">
            <input type='hidden' name='price' value='{$row['price']}'>
            <input type='hidden' name='image' value='{$row['image']}'>
            <button type='submit' class='order-btn'>Order Now</button>
          </form>
        </div>";
      }
      echo "</div>";
    } else {
      echo "<div class='menu-grid'>";
      while ($row = $result->fetch_assoc()) {
        $item_id = $row['id'];
        $current_qty = isset($_SESSION['cart'][$item_id]) ? $_SESSION['cart'][$item_id]['quantity'] : 0;

        echo "
        <div class='menu-card'>
          <img src='../assets/img/menu/{$row['image']}' alt='{$row['item_name']}' loading='lazy'>
          <h3>{$row['item_name']}</h3>
          <p>{$row['description']}</p>
          <span>₹{$row['price']}</span>

          <div class='qty-control' data-id='{$row['id']}' data-name=\"{$row['item_name']}\" data-price='{$row['price']}'>
            <button class='qty-btn minus'>−</button>
            <span class='qty-val'>{$current_qty}</span>
            <button class='qty-btn plus'>+</button>
          </div>

          <form action='order_now.php' method='POST'>
            <input type='hidden' name='item_id' value='{$row['id']}'>
            <input type='hidden' name='item_name' value=\"{$row['item_name']}\">
            <input type='hidden' name='price' value='{$row['price']}'>
            <input type='hidden' name='image' value='{$row['image']}'>
            <button type='submit' class='order-btn'>Order Now</button>
          </form>
        </div>";
      }
      echo "</div>";
    }

  } else {
    echo "<p style='text-align:center;'>No items found in this category.</p>";
  }
  ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll('.qty-control').forEach(ctrl => {
    const minus = ctrl.querySelector('.minus');
    const plus = ctrl.querySelector('.plus');
    const qtyVal = ctrl.querySelector('.qty-val');

    const id = ctrl.dataset.id;
    const name = ctrl.dataset.name;
    const price = ctrl.dataset.price;

    plus.addEventListener('click', () => updateCart('add', id, name, price, qtyVal));
    minus.addEventListener('click', () => updateCart('remove', id, name, price, qtyVal));
  });

  function updateCart(action, id, name, price, qtyVal) {
    fetch('update_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `action=${action}&id=${id}&name=${encodeURIComponent(name)}&price=${price}`
    })
    .then(res => res.json())
    .then(data => {
      qtyVal.textContent = data.itemQty;
      const cartCount = document.getElementById('cart-count');
      if (cartCount) cartCount.textContent = data.cartCount;
    });
  }
});
</script>

<?php include('../includes/footer.php'); ?>
</body>
</html>
