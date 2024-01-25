<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $productname = $_POST['productname'];

  $stmt = mysqli_prepare($conn, "
      SELECT productname, price, image, quantity
      FROM products
      WHERE productname = ?
  ");
  mysqli_stmt_bind_param($stmt, "s", $productname);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) != 1) {
    echo '<script>alert("Product not found.");</script>';
    mysqli_close($conn);
    exit();
  }

  $product_row = mysqli_fetch_assoc($result);
  
   // Check if the product is out of stock
  if ($product_row['quantity'] == 0) {
  echo '<script>alert("Product Out of Stock."); window.location.href="product.php?id='.$product_row['product_id'].'";</script>';
  exit();
}
  
  // encode the image to base64
  $image_base64 = base64_encode($product_row['image']);
  
  $username = $_SESSION['username'];
  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = mysqli_query($conn, $sql);
  $user_row = mysqli_fetch_assoc($result);
  
  // Check if item already exists in cart for this user
  $existing_cart_query = "SELECT * FROM cart WHERE username='$username' AND productname='{$product_row['productname']}'";
  $existing_cart_result = mysqli_query($conn, $existing_cart_query);

  if ($existing_cart_result->num_rows > 0) {
    // Item already exists in cart, update the quantity
    $existing_cart_row = $existing_cart_result->fetch_assoc();
    $new_quantity = $existing_cart_row['order_quantity'] + 1;

    $update_cart_query = "UPDATE cart SET order_quantity=$new_quantity WHERE cart_id={$existing_cart_row['cart_id']}";
    $update_cart_result = mysqli_query($conn, $update_cart_query);

    if ($update_cart_result) {
      // Decrease the quantity of the product by the difference between the new and old order quantity
      $productname = mysqli_real_escape_string($conn, $product_row['productname']);
      $old_order_quantity = mysqli_real_escape_string($conn, $existing_cart_row['order_quantity']);
      $new_order_quantity = mysqli_real_escape_string($conn, $new_quantity);
      $quantity_difference = $new_order_quantity - $old_order_quantity;
      $update_query = "UPDATE products SET quantity = quantity - $quantity_difference WHERE productname = '$productname'";
      $update_result = mysqli_query($conn, $update_query);

      if ($update_result) {
        // Redirect the user back to the product page
        header("Location: product.php?id={$product_row['product_id']}");
        exit;
      } else {
        // Display an error message to the user
        echo '<script>alert("Error updating product quantity: ' . mysqli_error($conn) . '");</script>';
      }
    } else {
      // Display an error message to the user
      echo '<script>alert("Error updating item quantity in cart: ' . mysqli_error($conn) . '");</script>';
    }
  } else {
    // Item doesn't exist in cart, insert a new row
$cart_query = "INSERT INTO cart (username, phone, image, productname, price, order_quantity, created_at) VALUES ('$username', '{$user_row['phone']}','$image_base64','{$product_row['productname']}', '{$product_row['price']}', 1, NOW())";
$cart_result = mysqli_query($conn, $cart_query);

if ($cart_result) {
  // Decrease the quantity of the product by the order quantity
  $productname = mysqli_real_escape_string($conn, $product_row['productname']);
  $order_quantity = mysqli_real_escape_string($conn, $_POST['order_quantity']);
  $update_query = "UPDATE products SET quantity = quantity - $order_quantity WHERE productname = '$productname'";
  $update_result = mysqli_query($conn, $update_query);

  if ($update_result) {
    // Redirect the user back to the product page
        header("Location: product.php?id={$product_row['product_id']}");
        exit;
  } else {
    // Display an error message to the user
    echo '<script>alert("Error updating product quantity: ' . mysqli_error($conn) . '");</script>';
  }
} else {
  // Display an error message to the user
  echo '<script>alert("Error adding item to cart: ' . mysqli_error($conn) . '");</script>';
}
  }
}
?>
