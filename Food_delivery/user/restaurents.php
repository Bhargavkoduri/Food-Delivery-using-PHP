<?php include('../includes/header.php'); ?>
<?php include('../config/db_connect.php'); ?>

<div class="container">
  <h2>Available Restaurants</h2>
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
  <?php
  $sql = "SELECT * FROM restaurants";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          echo "<div class='card'>";
          echo "<img src='../assets/images/restaurant.jpg' alt='Restaurant Image'>";
          echo "<h3>" . $row['name'] . "</h3>";
          echo "<p>" . $row['address'] . "</p>";
          echo "<a href='menu.php?rid=" . $row['restaurant_id'] . "'><button>View Menu</button></a>";
          echo "</div>";
      }
  } else {
      echo "<p>No restaurants found!</p>";
  }
  ?>
  </div>
</div>

<?php include('../includes/footer.php'); ?>
