<?php
// Retrieve product details from database based on ID
session_start();
include 'db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Handle form submission
if (isset($_POST['submit'])) {
    // Retrieve form data and escape special characters
    $productname = mysqli_real_escape_string($conn, $_POST['productname']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    // Check if a new image file has been uploaded
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['tmp_name'];
        $imageType = $_FILES['image']['type'];

        if ($imageType == 'image/jpeg') {
            $imageData = addslashes(file_get_contents($image));
            $sql = "UPDATE products SET productname='$productname', description='$description', price='$price', quantity='$quantity', category='$category', image='$imageData' WHERE id=$id";
        } else {
            echo "Only JPG files are allowed.";
            exit();
        }
    } else {
        // No new image file has been uploaded
        $sql = "UPDATE products SET productname='$productname', description='$description', price='$price', quantity='$quantity', category='$category' WHERE id=$id";
    }

    mysqli_query($conn, $sql);

    // Redirect back to product list page
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Edit Product</title>
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
    
    <div class="edit_product_container">
    <h1>Edit Product</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

        <label for="productname">Product Name</label>
        <input type="text" name="productname" value="<?php echo htmlspecialchars($row['productname']); ?>" required>

        <label for="description">Description</label>
        <textarea name="description" rows="5" required><?php echo htmlspecialchars($row['description']); ?></textarea>

        <label for="price">Price</label>
        <input type="number" name="price" min="0" step="0.01" value="<?php echo htmlspecialchars($row['price']); ?>" required>

        <label for="image">Image</label>
        <?php if ($row['image']): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']); ?>" width="100">
        <?php endif; ?>
        <input type="file" name="image">
        
        <label for="quantity">Quantity</label>
        <input type="number" name="quantity" min="0" value="<?php echo htmlspecialchars($row['quantity']); ?>" required>

        <label for="category">Category</label>
            <select name="category" required>
                <option value="">-- Select Category --</option>
                <option value="dogfood" <?php if ($row['category'] == 'dogfood') echo 'selected'; ?>>Dog Food</option>
                <option value="dogtreats" <?php if ($row['category'] == 'dogtreats') echo 'selected'; ?>>Dog Treats</option>
                <option value="dogtoys" <?php if ($row['category'] == 'dogtoys') echo 'selected'; ?>>Dog Toys</option>
                <option value="dogclothes" <?php if ($row['category'] == 'dogclothes') echo 'selected'; ?>>Dog Clothes</option>
                <option value="catfood" <?php if ($row['category'] == 'catfood') echo 'selected'; ?>>Cat Food</option>
                <option value="cattreats" <?php if ($row['category'] == 'cattreats') echo 'selected'; ?>>Cat Treats</option>
                <option value="cattoys" <?php if ($row['category'] == 'cattoys') echo 'selected'; ?>>Cat Toys</option>
                <option value="catclothes" <?php if ($row['category'] == 'catclothes') echo 'selected'; ?>>Cat Clothes</option>
            </select>

        <br><br>
        <input type="submit" name="submit" value="Update">
    </form>
    </div>
</body>
</html>