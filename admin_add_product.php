<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productname = $_POST['productname'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];

    // Escape special characters in the product description
    $description = mysqli_real_escape_string($conn, $description);

    // Check if the file was uploaded without errors
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $image = $_FILES['image']['tmp_name'];
        $imgContent = addslashes(file_get_contents($image));

        // Insert product information and image data into the database
        $insert_product_sql = "INSERT INTO products (productname, description, price, image, quantity, category) VALUES ('$productname', '$description', '$price', '$imgContent', '$quantity', '$category')";
        mysqli_query($conn, $insert_product_sql) or die(mysqli_error($conn));
    
        // Redirect to the admin_dashboad page
        header('Location: admin_dashboard.php');
        exit();
        
    } else {
        // Image upload failed
        echo "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Add Product</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">

    <script type="text/javascript">
            function preventBack() {
                window.history.forward();
            }
            setTimeout("preventBack()", 0);

            window.onunload = function () {
                null;
            };

            // Regenerate session ID on each page load
            window.onload = function () {
                var xhttp = new XMLHttpRequest();
                xhttp.open("GET", "refresh_session.php", true);
                xhttp.send();
            };
            // Disable refresh
            document.onkeydown = function (e) {
                if (e.keyCode == 116) {
                    return false;
                }
            };

            document.onmousedown = function (e) {
                if (e.button == 4) {
                    return false;
                }
            };
        </script>
</head>
<body>

<header>
  <div class="logo">
    <a href="admin_dashboard.php"><img src="image/logo.png" alt="QTPie logo" class="logo-img"></a>
  </div>
  <nav>
    <ul>
      <li><a href="admin_dashboard.php">Products</a>
        <ul>
          <li><a href="admin_add_product.php">Add Product</a></li>
          <li><a href="admin_dashboard.php">Edit Product</a></li>
          <li><a href="admin_dashboard.php">Delete Product</a></li>
        </ul>
      </li>
      <li><a href="admin_pet_adoption.php">Pet Adoption</a>
        <ul>
          <li><a href="admin_add_pet.php">Add Pet</a></li>
          <li><a href="admin_pet_adoption.php">Edit Pet</a></li>
          <li><a href="admin_pet_adoption.php">Delete Pet</a></li>
        </ul>
      </li>
      <li><a href="admin_view_order.php">Customer Orders</a></li>
      <li><a href="admin_view_booking.php">Customer Booking</a></li>
      <li><a href="admin_view_feedback.php">Customer Feedback</a></li>
    </ul>
  </nav>
  <div class="admin_logout">
    <a href="admin_logout.php">Logout</a>
  </div>
</header>

<div class="add_product_container">
    <h2>Add New Product</h2>

    <!-- HTML form to add a new product -->
    <form method="post" enctype="multipart/form-data">
        <label for="productname">Product Name:</label>
        <input type="text" id="productname" name="productname" required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="">Select a category</option>
            <option value="dogfood">Dog Food</option>
            <option value="dogtreats">Dog Treats</option>
            <option value="dogtoys">Dog Toys</option>
            <option value="dogclothes">Dog Clothes</option>
            <option value="catfood">Cat Food</option>
            <option value="cattreats">Cat Treats</option>
            <option value="cattoys">Cat Toys</option>
            <option value="catclothes">Cat Clothes</option>
        </select><br><br>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" min="0" step="0.01" required><br><br>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" required><br><br>

        <input type="submit" value="Add Product">
    </form>
</div>
</body>
</html>
