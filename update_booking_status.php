<?php

session_start();
include'db.php';

// Get the booking ID and new status from the form
$booking_id = $_POST['booking_id'];
$status = $_POST['status'];

// Update the booking status in the database
$query = "UPDATE booking SET status='$status' WHERE booking_id='$booking_id'";
mysqli_query($conn, $query);

// Redirect back to the original page
header('Location: admin_view_booking.php');
?>
