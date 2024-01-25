<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit;
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $productname = $_POST['productname'];
  $action = $_POST['action'];

  if ($action == 'decrease') {
    // Retrieve the user's cart item from the database
    $cart_query = "SELECT * FROM cart WHERE username='$username' AND productname='$productname'";
    $cart_result = mysqli_query($conn, $cart_query);
    $cart_item = mysqli_fetch_assoc($cart_result);

    if ($cart_item['order_quantity'] > 1) {
      // Decrease the order quantity by 1
      $new_quantity = $cart_item['order_quantity'] - 1;

      // Update the cart in the database
      $update_query = "UPDATE cart SET order_quantity=$new_quantity WHERE username='$username' AND productname='$productname'";
      mysqli_query($conn, $update_query);

      // Increase the product quantity in the database
      $increase_query = "UPDATE products SET quantity=quantity+1 WHERE productname='$productname'";
      mysqli_query($conn, $increase_query);
    } else {
      // Remove the item from the cart
      $remove_query = "DELETE FROM cart WHERE username='$username' AND productname='$productname'";
      mysqli_query($conn, $remove_query);

      // Increase the product quantity in the database
      $increase_query = "UPDATE products SET quantity=quantity+1 WHERE productname='$productname'";
      mysqli_query($conn, $increase_query);
    }
  } else if ($action == 'increase') {
    // Retrieve the user's cart item from the database
    $cart_query = "SELECT * FROM cart WHERE username='$username' AND productname='$productname'";
    $cart_result = mysqli_query($conn, $cart_query);
    $cart_item = mysqli_fetch_assoc($cart_result);

    // Increase the order quantity by 1
    $new_quantity = $cart_item['order_quantity'] + 1;

    // Update the cart in the database
    $update_query = "UPDATE cart SET order_quantity=$new_quantity WHERE username='$username' AND productname='$productname'";
    mysqli_query($conn, $update_query);

    // Decrease the product quantity in the database
    $decrease_query = "UPDATE products SET quantity=quantity-1 WHERE productname='$productname'";
    mysqli_query($conn, $decrease_query);
  }
}

header('Location: user_view_cart.php');
exit;
?>
