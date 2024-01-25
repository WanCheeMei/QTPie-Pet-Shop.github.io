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
      SELECT productname, price, image
      FROM products
      WHERE productname = ?
  ");
  mysqli_stmt_bind_param($stmt, "s", $productname);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) != 1) {
    echo '<script>alert("Product not found."); window.location.href = "home.php";</script>';
    mysqli_close($conn);
    exit();
  }

  $product_row = mysqli_fetch_assoc($result);
  
  // encode the image to base64
  $image_base64 = base64_encode($product_row['image']);
  
  $username = $_SESSION['username'];
  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = mysqli_query($conn, $sql);
  $user_row = mysqli_fetch_assoc($result);
  
  // Check if item already exists in wishlist for this user
  $existing_wishlist_query = "SELECT * FROM wishlist WHERE username='$username' AND productname='{$product_row['productname']}'";
  $existing_wishlist_result = mysqli_query($conn, $existing_wishlist_query);

  if ($existing_wishlist_result->num_rows > 0) {
    // Item already exists in wishlist, display a message to the user
    echo '<script>alert("Item already in wishlist."); window.location.href = "product.php";</script>';
    exit;
  } else {
    // Item doesn't exist in wishlist, insert a new row
    $wishlist_query = "INSERT INTO wishlist (username, phone, image, productname, price, created_at) VALUES ('$username', '{$user_row['phone']}','$image_base64','{$product_row['productname']}', '{$product_row['price']}', NOW())";
    $wishlist_result = mysqli_query($conn, $wishlist_query);

    if ($wishlist_result) {
      // Display a success message to the user
      echo '<script>alert("Item added to wishlist successfully."); window.location.href = "home.php";</script>';
      exit;
    } else {
      // Display an error message to the user
      echo '<script>alert("Error adding item to wishlist: ' . mysqli_error($conn) . '");</script>';
    }
  }
}
?>
