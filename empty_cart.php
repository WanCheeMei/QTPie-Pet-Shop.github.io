<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit;
}

// Connect to database
include 'db.php';

// Get the user's cart items
$username = $_SESSION['username'];
$select_query = "SELECT * FROM cart WHERE username = '$username'";
$cart_result = mysqli_query($conn, $select_query);
$cart_items = mysqli_fetch_all($cart_result, MYSQLI_ASSOC);

// Function to update the quantity of a product in the products table
function update_product_quantity($productname, $quantity) {
  global $conn;

  $update_query = "UPDATE products SET quantity = quantity + $quantity WHERE productname = '$productname'";
  mysqli_query($conn, $update_query);
}

// Loop through each item in the cart and update the product quantity
foreach ($cart_items as $cart_item) {
  update_product_quantity($cart_item['productname'], $cart_item['order_quantity']);
}

// Delete all items from the user's cart in the cart table
$delete_query = "DELETE FROM cart WHERE username = '$username'";
mysqli_query($conn, $delete_query);

// Close database connection
mysqli_close($conn);

// Redirect back to user's cart page
header('Location: user_view_cart.php');
exit;
?>
