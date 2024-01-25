<?php
// Start the session
session_start();
include 'db.php';

// Check if the user is authenticated
if (isset($_SESSION['username'])) {
  // Get the username of the currently authenticated user
  $username = $_SESSION['username'];

  // Check if the user has items in their cart
  $query = "SELECT COUNT(*) FROM cart WHERE username = '$username'";
  $result = mysqli_query($conn, $query);
  $count = mysqli_fetch_array($result)[0];

  if ($count > 0) {
    // Update the product quantity in the products table
    $query = "UPDATE products p, cart c SET p.quantity = p.quantity + c.order_quantity WHERE p.productname = c.productname AND c.username = '$username'";
    mysqli_query($conn, $query);

    // Clear the user's cart
    $query = "DELETE FROM cart WHERE username = '$username'";
    mysqli_query($conn, $query);
  }
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Show confirmation message to the user
echo '<script>alert("Your cart has been cleared. Thank you!"); window.location.href="login.php";</script>';


?>

<script>
window.addEventListener("beforeunload", function() {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "clear_cart.php", true);
  xhr.send();
});
</script>

