<?php
session_start();

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$id = $_POST['id'];
$name = $_POST['name'];
$price = (float)$_POST['price'];
$action = $_POST['action'];

if (!isset($_SESSION['cart'][$id])) {
  $_SESSION['cart'][$id] = ['name' => $name, 'price' => $price, 'quantity' => 0];
}

if ($action === 'add') {
  $_SESSION['cart'][$id]['quantity']++;
} elseif ($action === 'remove') {
  $_SESSION['cart'][$id]['quantity']--;
  if ($_SESSION['cart'][$id]['quantity'] <= 0) {
    unset($_SESSION['cart'][$id]);
  }
}

$cartCount = 0;
foreach ($_SESSION['cart'] as $item) {
  $cartCount += $item['quantity'];
}

$itemQty = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id]['quantity'] : 0;

echo json_encode(['cartCount' => $cartCount, 'itemQty' => $itemQty]);
