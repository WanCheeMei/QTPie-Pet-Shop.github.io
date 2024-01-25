<?php
session_start();

// Redirect to login page if session variable is not set
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "DELETE FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        // Redirect to the admin dashboard page
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error deleting product: " . mysqli_error($conn);
    }
} else {
    echo "Product not found.";
}
?>
