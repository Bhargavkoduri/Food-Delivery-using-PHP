<?php
include('../config/dbconnect.php'); 
include('../includes/header.php');

$searchQuery = "";
$searchResults = [];

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
  $searchQuery = trim($_GET['search']);
  $sql = "SELECT * FROM menu_item WHERE item_name LIKE ? OR category LIKE ? OR description LIKE ?";
  $stmt = $conn->prepare($sql);
  $term = "%{$searchQuery}%";
  $stmt->bind_param("sss", $term, $term, $term);
  $stmt->execute();
  $searchResults = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Food Delivery | Home</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
   /* ==================== GLOBAL STYLES ==================== */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Poppins', sans-serif;
  background: #fff9f6;
  color: #333;
  overflow-x: hidden;
  scroll-behavior: smooth;
}

/* ==================== HEADER FIX ==================== */
header {
  background: linear-gradient(90deg, #ff512f, #dd2476);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* ==================== HERO SECTION ==================== */
.hero {
  position: relative;
  height: 90vh;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  overflow: hidden;
  background: linear-gradient(120deg, #ff512f, #dd2476);
  color: #fff;
}

.hero::before {
  content: "";
  position: absolute;
  inset: 0;
  background: url('../assets/images/hero.jpg') center/cover no-repeat;
  filter: brightness(0.55);
  transform: scale(1.1);
  animation: moveHero 18s ease-in-out infinite alternate;
  z-index: 0;
}

@keyframes moveHero {
  0% { transform: scale(1.1) translate(0, 0); }
  100% { transform: scale(1.2) translate(-2%, -2%); }
}

.hero::after {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  z-index: 1;
}

.hero-content {
  position: relative;
  z-index: 2;
  max-width: 650px;
  padding: 20px;
  backdrop-filter: blur(3px);
  text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.hero-content h1 {
  font-size: 3.8rem;
  font-weight: 700;
  margin-bottom: 15px;
}

.hero-content p {
  font-size: 1.2rem;
  margin-bottom: 30px;
  color: #fdfdfd;
}

.search-bar {
  display: flex;
  justify-content: center;
  gap: 10px;
}

.search-bar input {
  width: 70%;
  padding: 14px 22px;
  border-radius: 40px;
  border: none;
  outline: none;
  font-size: 1rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.search-bar button {
  background: linear-gradient(45deg, #ff512f, #dd2476);
  border: none;
  border-radius: 40px;
  padding: 14px 30px;
  color: white;
  font-weight: 600;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  transition: 0.3s ease;
}

.search-bar button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
}

/* ==================== SEARCH RESULTS ==================== */
.search-results {
  max-width: 1200px;
  margin: 80px auto;
  padding: 0 20px;
  text-align: center;
}

.search-results h2 {
  font-size: 2rem;
  color: #ff512f;
  margin-bottom: 40px;
  position: relative;
}

.search-results h2::after {
  content: "";
  display: block;
  width: 100px;
  height: 4px;
  background: linear-gradient(45deg, #ff512f, #dd2476);
  margin: 10px auto 0;
  border-radius: 2px;
}

.menu-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 30px;
}

.menu-card {
  background: white;
  border-radius: 20px;
  box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  transition: all 0.3s ease;
  padding-bottom: 25px;
}

.menu-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.menu-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-bottom: 3px solid #ff512f;
}

.menu-card h3 {
  margin-top: 15px;
  font-size: 1.3rem;
  color: #222;
}

.menu-card p {
  font-size: 0.95rem;
  color: #777;
  margin: 10px 15px 5px;
  min-height: 45px;
}

.menu-card span {
  display: block;
  font-weight: bold;
  color: #dd2476;
  font-size: 1.1rem;
  margin-top: 8px;
}

.qty-control {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 12px;
  margin-top: 15px;
}

.qty-btn {
  background: #ff512f;
  border: none;
  color: white;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  font-size: 20px;
  cursor: pointer;
  transition: 0.3s;
}

.qty-btn:hover {
  background: #dd2476;
}

.qty-val {
  font-weight: bold;
  font-size: 1.1rem;
}

.order-btn {
  background: linear-gradient(45deg, #00b074, #00c686);
  border: none;
  color: white;
  padding: 10px 25px;
  border-radius: 30px;
  font-size: 16px;
  cursor: pointer;
  margin-top: 15px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.order-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 15px rgba(0, 176, 116, 0.4);
}

.no-result {
  color: #666;
  font-size: 1.1rem;
  text-align: center;
  margin-top: 30px;
}

/* ==================== CUISINES SECTION ==================== */
.cuisines {
  background: #fff;
  padding: 80px 20px;
}

.cuisines h2 {
  font-size: 2rem;
  color: #ff512f;
  margin-bottom: 50px;
  text-align: center;
}

.cuisine-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 30px;
  justify-items: center;
}

.cuisine-card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  transition: 0.3s ease;
  text-decoration: none;
  color: #222;
  width: 180px;
}

.cuisine-card:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.cuisine-card img {
  width: 100%;
  height: 130px;
  object-fit: cover;
}

.cuisine-card p {
  text-align: center;
  padding: 12px 0;
  font-weight: 600;
  font-size: 1rem;
  background: linear-gradient(90deg, #ff512f, #dd2476);
  color: #fff;
}

/* ==================== TOP RESTAURANTS ==================== */
.restaurants {
  background: #fff9f6;
  padding: 80px 20px;
}

.restaurants h2 {
  font-size: 2rem;
  color: #ff512f;
  margin-bottom: 50px;
  text-align: center;
}

.restaurants .cuisine-card {
  width: 240px;
  border-radius: 18px;
  overflow: hidden;
  text-align: center;
}

.restaurants .cuisine-card img {
  height: 160px;
  border-bottom: 3px solid #ff512f;
}

.restaurants .cuisine-card p {
  padding: 15px;
  font-size: 1.05rem;
  font-weight: 600;
  color: #333;
}

/* ==================== FOOTER ==================== */
footer {
  background: linear-gradient(90deg, #ff512f, #dd2476);
  color: white;
  text-align: center;
  padding: 18px 0;
  font-size: 0.9rem;
  letter-spacing: 0.5px;
  margin-top: 60px;
}

  </style>
</head>
<body>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-content">
      <h1>Hungry?</h1>
      <p>Order food from your favourite restaurants near you</p>
      <form class="search-bar" method="GET" action="">
        <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search for restaurant or dish...">
        <button type="submit">Search</button>
      </form>
    </div>
  </section>

  <!-- SEARCH RESULTS (Only show if searched) -->
  <?php if (!empty($searchQuery)): ?>
  <section class="search-results">
    <h2>Search Results for "<?= htmlspecialchars($searchQuery) ?>"</h2>
    <?php if ($searchResults->num_rows > 0): ?>
      <div class="menu-grid">
        <?php while ($row = $searchResults->fetch_assoc()):
          $item_id = $row['id'];
          $current_qty = $_SESSION['cart'][$item_id]['quantity'] ?? 0;
        ?>
          <div class="menu-card">
            <img src="../assets/img/menu/<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['item_name']) ?>">
            <h3><?= htmlspecialchars($row['item_name']) ?></h3>
            <p><?= htmlspecialchars($row['description']) ?></p>
            <span>₹<?= $row['price'] ?></span>

            <div class="qty-control" data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['item_name']) ?>" data-price="<?= $row['price'] ?>">
              <button class="qty-btn minus">−</button>
              <span class="qty-val"><?= $current_qty ?></span>
              <button class="qty-btn plus">+</button>
            </div>

            <form action="order_now.php" method="POST">
              <input type="hidden" name="item_id" value="<?= $row['id'] ?>">
              <input type="hidden" name="item_name" value="<?= htmlspecialchars($row['item_name']) ?>">
              <input type="hidden" name="price" value="<?= $row['price'] ?>">
              <input type="hidden" name="image" value="<?= $row['image'] ?>">
              <button type="submit" class="order-btn">Order Now</button>
            </form>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p class="no-result">No items found matching your search.</p>
    <?php endif; ?>
  </section>
  <?php endif; ?>

  <!-- POPULAR CUISINES -->
  <section class="cuisines">
    <h2>What's on your mind?</h2>
    <div class="cuisine-grid">
      <a href="menu.php?category=Biryani" class="cuisine-card"><img src="../assets/images/biryani.jpg"><p>Biryani</p></a>
      <a href="menu.php?category=Pizza" class="cuisine-card"><img src="../assets/images/pizza.jpg"><p>Pizza</p></a>
      <a href="menu.php?category=Burger" class="cuisine-card"><img src="../assets/images/burger.jpg"><p>Burger</p></a>
      <a href="menu.php?category=Chinese" class="cuisine-card"><img src="../assets/images/chinese.jpg"><p>Chinese</p></a>
      <a href="menu.php?category=South Indian" class="cuisine-card"><img src="../assets/images/south_indian.jpg"><p>South Indian</p></a>
      <a href="menu.php?category=North Indian" class="cuisine-card"><img src="../assets/images/north_indian.jpg"><p>North Indian</p></a>
    </div>
  </section>

  <!-- TOP RESTAURANTS -->
  <section class="restaurants">
    <h2>Top Restaurants</h2>
    <div class="cuisine-grid">
      <a href="menu.php?restaurant=Mehfil" class="cuisine-card"><img src="../assets/images/mehfil.jpg"><p>Mehfil</p></a>
      <a href="menu.php?restaurant=Paradise" class="cuisine-card"><img src="../assets/images/paradise.jpg"><p>Paradise Biryani</p></a>
      <a href="menu.php?restaurant=BBQ_Nation" class="cuisine-card"><img src="../assets/images/bbq_nation.jpg"><p>Barbeque Nation</p></a>
      <a href="menu.php?restaurant=Dominos" class="cuisine-card"><img src="../assets/images/dominos.jpg"><p>Domino’s Pizza</p></a>
      <a href="menu.php?restaurant=KFC" class="cuisine-card"><img src="../assets/images/kfc.jpg"><p>KFC</p></a>
      <a href="menu.php?restaurant=McDonalds" class="cuisine-card"><img src="../assets/images/mcd.jpg"><p>McDonald’s</p></a>
    </div>
  </section>

  <?php include('../includes/footer.php'); ?>

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

</body>
</html>
