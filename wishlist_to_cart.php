<?php
  // Start the session and get the user's information
  session_start();
  include 'db.php';
  
  $productname = mysqli_real_escape_string($conn, $_POST['productname']);

  // Get the wishlist item for the current user
  $username = $_SESSION['username'];
  $wishlist_query = "SELECT * FROM wishlist WHERE username='$username' AND productname='$productname'";
  $wishlist_result = mysqli_query($conn, $wishlist_query);
  $wishlist_item = mysqli_fetch_assoc($wishlist_result);

  if (!$wishlist_item) {
    // handle error - wishlist item not found
    echo "<script>alert('Wishlist item not found.');</script>";
    exit();
  }

  $phone = $wishlist_item['phone'];
  $image = mysqli_real_escape_string($conn, $wishlist_item['image']);
  $price = mysqli_real_escape_string($conn, $wishlist_item['price']);

  // Check if the product's quantity is greater than 0
  $product_query = "SELECT quantity FROM products WHERE productname='$productname'";
  $product_result = mysqli_query($conn, $product_query);
  $product = mysqli_fetch_assoc($product_result);
  $quantity = $product['quantity'];

  if ($quantity > 0) {
    // Insert the product into the cart table with the user's information
    $cart_query = "INSERT INTO cart (username, phone, image, productname, price, order_quantity, created_at) VALUES ('$username', '$phone', '$image', '$productname', '$price', '1', NOW())";
    mysqli_query($conn, $cart_query);

    // Decrease the quantity of the product in the products table
    $update_query = "UPDATE products SET quantity = quantity - 1 WHERE productname='$productname'";
    mysqli_query($conn, $update_query);

    // Redirect the user to the cart page
    header('Location: user_view_wishlist.php');
    exit();
  } else {
    // handle error - product out of stock
    echo "<script>alert('Product out of stock.');window.location.href='user_view_wishlist.php';</script>";
    exit();
  }
?> 