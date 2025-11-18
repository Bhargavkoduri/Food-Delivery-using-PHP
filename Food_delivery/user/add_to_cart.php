<?php
// File: add_to_cart.php
session_start();
include('../config/dbconnect.php'); // optional if you need DB access

// Ensure cart exists and is an array
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Expect POST with item_id, item_name, item_price, item_image (image optional)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $item_id = (string)$_POST['item_id'];             // string to avoid type mismatch
    $item_name = trim($_POST['item_name'] ?? 'Item');
    $item_price = (float)($_POST['item_price'] ?? 0.0);
    $item_image = trim($_POST['item_image'] ?? '../assets/img/menu/default.jpg');

    // Find if item exists in cart (match by id)
    $foundIndex = null;
    foreach ($_SESSION['cart'] as $idx => $itm) {
        if (isset($itm['id']) && (string)$itm['id'] === $item_id) {
            $foundIndex = $idx;
            break;
        }
    }

    if ($foundIndex !== null) {
        // increment quantity
        $_SESSION['cart'][$foundIndex]['quantity'] = intval($_SESSION['cart'][$foundIndex]['quantity']) + 1;
    } else {
        // add new item
        $_SESSION['cart'][] = [
            'id'       => $item_id,
            'name'     => $item_name,
            'price'    => $item_price,
            'image'    => $item_image,
            'quantity' => 1
        ];
    }

    // Optional: store derived values (not required)
    // $_SESSION['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));

    // Redirect back to the page the user came from (or to cart.php if none)
    $redirect = $_SERVER['HTTP_REFERER'] ?? 'cart.php';
    header("Location: $redirect");
    exit;
}

// fallback redirect
header('Location: cart.php');
exit;
?>
