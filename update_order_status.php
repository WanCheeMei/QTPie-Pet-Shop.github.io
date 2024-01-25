<?php
session_start();
include 'db.php';

// Get the order ID and new status from the form
$order_id = $_POST['order_id'];
$order_status = $_POST['order_status'];

// Update the order status in the database
$query = "UPDATE orders SET order_status='$order_status' WHERE order_id='$order_id'";
mysqli_query($conn, $query);

// Redirect back to the original page
header('Location: admin_view_order.php');
?>

