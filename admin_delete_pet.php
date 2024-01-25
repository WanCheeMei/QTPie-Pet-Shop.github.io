<?php
session_start();

// Redirect to login page if session variable is not set
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Check if the pet ID is set
if (isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];
    
    // Delete the pet from the pets table
    $sql = "DELETE FROM pets WHERE pet_id = '$pet_id'";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        // Redirect back to the pet list page
        header("Location: admin_pet_adoption.php");
        exit();
    } else {
        // Error deleting pet
        echo "Error deleting pet: " . mysqli_error($conn);
    }
} else {
    // No pet ID specified
    echo "No pet ID specified.";
}
?>

