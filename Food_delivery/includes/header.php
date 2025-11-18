<?php
// 🚫 No spaces or newlines before session_start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start(); // Start output buffering AFTER session_start

$page = basename($_SERVER['PHP_SELF'], ".php");

// -----------------------------
// 🛒 CART ITEM COUNT ONLY
// -----------------------------
$cart = $_SESSION['cart'] ?? [];
$cart_count = 0;

if (is_array($cart)) {
    foreach ($cart as $c) {
        $cart_count += intval($c['quantity'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Food Delivery - <?= ucfirst($page) ?></title>

  <!-- Common styles -->
  <link rel="stylesheet" href="../assets/css/common.css">

  <!-- Page-specific styles -->
  <?php
  $cssPath = "../assets/css/" . $page . ".css";
  if (file_exists($cssPath)) {
      echo '<link rel="stylesheet" href="' . $cssPath . '">';
  }
  ?>

  <style>
    header {
      background: #ff6347;
      color: white;
      padding: 15px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }

    .navbar {
      max-width: 1200px;
      margin: auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 25px;
    }

    .logo h1 {
      font-size: 22px;
      margin: 0;
      letter-spacing: 1px;
    }

    nav {
      display: flex;
      align-items: center;
      gap: 25px;
    }

    nav a {
      text-decoration: none;
      color: white;
      font-weight: 500;
      transition: 0.3s;
      position: relative;
    }

    nav a:hover {
      color: #fffacd;
      text-decoration: underline;
    }

    /* 🛒 CART COUNT BADGE */
    .cart-info {
      background: #fff;
      color: #ff6347;
      padding: 4px 10px;
      border-radius: 15px;
      font-size: 14px;
      font-weight: 600;
      display: inline-block;
      margin-left: 5px;
      min-width: 28px;
      text-align: center;
    }

    .auth-buttons {
      display: flex;
      gap: 10px;
    }

    .auth-buttons a {
      background: white;
      color: #ff6347;
      padding: 6px 14px;
      border-radius: 20px;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: 0.3s ease;
    }

    .auth-buttons a:hover {
      background: #ffe4e1;
    }

    .menu-toggle {
      display: none;
      font-size: 24px;
      cursor: pointer;
      color: white;
    }

    @media (max-width: 768px) {
      nav {
        display: none;
        flex-direction: column;
        background: #ff6347;
        position: absolute;
        top: 65px;
        right: 0;
        width: 100%;
        text-align: center;
        padding: 15px 0;
        gap: 15px;
      }

      nav.active {
        display: flex;
      }

      .menu-toggle {
        display: block;
      }

      .cart-info {
        display: inline-block;
        margin-top: 6px;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="navbar">
      <div class="logo">
        <h1>🍴 Food Delivery</h1>
      </div>

      <div class="menu-toggle" onclick="toggleMenu()">☰</div>

      <nav id="navMenu">
        <a href="home.php" <?= ($page == "home") ? 'class="active"' : '' ?>>Home</a>
        <a href="menu.php" <?= ($page == "menu") ? 'class="active"' : '' ?>>Menu</a>

        <!-- 🛒 CART LINK WITH ONLY COUNT -->
        <a href="cart.php" <?= ($page == "cart") ? 'class="active"' : '' ?>>
          Cart
          <span class="cart-info"><?= $cart_count ?></span>
        </a>

        <a href="orders.php" <?= ($page == "orders") ? 'class="active"' : '' ?>>Orders</a>

        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="../user/logout.php">Logout</a>
        <?php else: ?>
          <div class="auth-buttons">
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
          </div>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <script>
    function toggleMenu() {
      document.getElementById("navMenu").classList.toggle("active");
    }
  </script>
</body>
</html>
