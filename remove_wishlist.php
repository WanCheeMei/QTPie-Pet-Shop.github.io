<?php
session_start();
include 'db.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  // Retrieve the productname value from the form
  $productname = $_POST['productname'];

  
  // Remove the item from the wishlist table
  $query = "DELETE FROM wishlist WHERE productname = '{$productname}'";
  $result = mysqli_query($conn, $query);
  
  // Check if the query was successful
  if ($result) {
    // Show an alert message and redirect the user back to the wishlist page
    $_SESSION['message'] = 'Item removed from wishlist.';
    header('Location: user_view_wishlist.php');
    exit;
  } else {
    echo 'Failed to remove item from wishlist: ' . mysqli_error($conn);
  }
  
  // Close the database connection
  mysqli_close($conn);
  
} else {
  // If the form has not been submitted, redirect the user back to the wishlist page
  header('Location: user_view_wishlist.php');
  exit;
}
?>





