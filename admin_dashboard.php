<?php
session_start();

// Redirect to login page if session variable is not set
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit();
}
include'db.php';

?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--To link and get the icons in font-awesome-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!--To link css file-->
	<link rel="stylesheet" href="css/admin.css">
        <!-- js file link  -->
        <script src="js/javascript.js"></script>
        <!--  To link jquery -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<!-- To link bootstrap js file -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<!-- To link bootstrap cdn -->
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
    <a href="home.php"><img src="image/logo.png" alt="QTPie logo" class="logo-img"></a>
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
    
    <?php
    include 'db.php';
    ?>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    td.description {
        text-align: justify;
    }
    img {
        max-width: 100px;
        height: auto;
    }
</style>

<table>
    <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Description</th>
        <th>Price(RM)</th>
        <th>Image</th>
        <th>Quantity</th>
        <th>Category</th>
        <th>Action</th>
    </tr>
    <?php
        // Fetch products from the database and loop through them to display in the table
        $select_products_sql = "SELECT * FROM products ORDER BY id DESC";
        $result = mysqli_query($conn, $select_products_sql) or die(mysqli_error($conn));
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['productname'] . "</td>";
            echo "<td class='description'>" . $row['description'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['image']) . "'></td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "<td>";
            echo "<a href='admin_edit_product.php?id=" . $row['id'] . "'>Edit</a>";
            echo " | ";
            echo "<a href='admin_delete_product.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this product?')\">Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
    ?>
</table>

    
</body>
</html>