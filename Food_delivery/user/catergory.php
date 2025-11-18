<?php include('../includes/header.php'); ?>

<?php
$category = isset($_GET['name']) ? $_GET['name'] : "Unknown";

// 🍲 Menu list for each category
$menu = [
  "Biryani" => [
    ["name" => "Hyderabadi Chicken Biryani", "price" => 250, "img" => "../assets/images/biryani.jpg"],
    ["name" => "Veg Biryani", "price" => 200, "img" => "../assets/images/biryani.jpg"],
    ["name" => "Mutton Biryani", "price" => 280, "img" => "../assets/images/biryani.jpg"]
  ],
  "Pizza" => [
    ["name" => "Cheese Burst Pizza", "price" => 300, "img" => "../assets/images/pizza.jpg"],
    ["name" => "Paneer Pizza", "price" => 280, "img" => "../assets/images/pizza.jpg"]
  ],
  "Burger" => [
    ["name" => "Chicken Burger", "price" => 150, "img" => "../assets/images/burger.jpg"],
    ["name" => "Veggie Burger", "price" => 120, "img" => "../assets/images/burger.jpg"]
  ],
  "Chinese" => [
    ["name" => "Hakka Noodles", "price" => 200, "img" => "../assets/images/chinese.jpg"],
    ["name" => "Fried Rice", "price" => 180, "img" => "../assets/images/chinese.jpg"]
  ],
  "South Indian" => [
    ["name" => "Masala Dosa", "price" => 120, "img" => "../assets/images/south_indian.jpg"],
    ["name" => "Idli Sambar", "price" => 100, "img" => "../assets/images/south_indian.jpg"]
  ],
  "North Indian" => [
    ["name" => "Butter Chicken", "price" => 250, "img" => "../assets/images/north_indian.jpg"],
    ["name" => "Paneer Butter Masala", "price" => 230, "img" => "../assets/images/north_indian.jpg"]
  ]
];


$items = $menu[$category] ?? [];
?>

<section class="menu-section">
  <h2><?= htmlspecialchars($category) ?> Varieties</h2>

  <div class="menu-container">
    <?php if (count($items) > 0): ?>
      <?php foreach ($items as $item): ?>
        <div class="menu-card">
          <img src="<?= $item['img'] ?>" alt="<?= $item['name'] ?>">
          <h3><?= $item['name'] ?></h3>
          <p>₹<?= $item['price'] ?></p>
          <button onclick="addToCart('<?= $item['name'] ?>', <?= $item['price'] ?>)">Add to Cart</button>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No items available for this category.</p>
    <?php endif; ?>
  </div>
</section>

<script>
function addToCart(item, price) {
  alert(item + " added to cart! (₹" + price + ")");
  // later this will actually save to session/cart
}
</script>

<?php include('../includes/footer.php'); ?>

<style>
.menu-section {
  text-align: center;
  padding: 30px;
}

.menu-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 25px;
}

.menu-card {
  background: white;
  border-radius: 12px;
  width: 200px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  padding-bottom: 15px;
  transition: 0.3s;
}

.menu-card img {
  width: 100%;
  height: 140px;
  object-fit: cover;
  border-top-left-radius: 12px;
  border-top-right-radius: 12px;
}

.menu-card h3 {
  margin: 10px 0 5px;
}

.menu-card p {
  font-weight: bold;
  color: #555;
}

.menu-card button {
  background: #ff6347;
  color: white;
  border: none;
  padding: 8px 15px;
  border-radius: 20px;
  cursor: pointer;
}

.menu-card button:hover {
  background: #e5533c;
}
</style>
