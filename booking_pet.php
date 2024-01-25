<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pet_id = $_POST['pet_id'];

  $stmt = mysqli_prepare($conn, "
      SELECT pet_id, name
      FROM pets
      WHERE pet_id = ?
  ");
  mysqli_stmt_bind_param($stmt, "i", $pet_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) != 1) {
    echo '<script>alert("Pet not found.");</script>';
    mysqli_close($conn);
    exit();
  }

  $pet_row = mysqli_fetch_assoc($result);

  $username = $_SESSION['username'];
  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = mysqli_query($conn, $sql);
  $user_row = mysqli_fetch_assoc($result);

  $booking_query = "INSERT INTO booking (username, phone, pet_id, name, booking_date) VALUES ('$username', '{$user_row['phone']}', '{$pet_row['pet_id']}', '{$pet_row['name']}', NOW())";
  $booking_result = mysqli_query($conn, $booking_query);

  if ($booking_result) {
    $delete_query = "DELETE FROM pets WHERE pet_id='$pet_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    echo '<script>alert("Booking successful!");</script>';
    echo '<script>window.location.href = "pet_adoption.php";</script>';
    exit();
  } else {
    echo '<script>alert("Booking failed.");</script>';
  }
}

mysqli_close($conn);
?>