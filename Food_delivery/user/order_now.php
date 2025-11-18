<?php
session_start();
include('../config/dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'] ?? 1; // Replace with actual user session if available
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    $quantity = 1;
    $total_amount = $price * $quantity;
    $status = 'Ordered';

    $query = "INSERT INTO orderss (user_id, item_name, price, quantity, total_amount, status, created_at)
              VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isdiis", $user_id, $item_name, $price, $quantity, $total_amount, $status);

    if ($stmt->execute()) {
        header("Location: orders.php?success=1");
        exit;
    } else {
        echo "<script>alert('Error placing order. Please try again.'); window.history.back();</script>";
    }
}
?>
