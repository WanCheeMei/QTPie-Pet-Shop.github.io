<!DOCTYPE html>
<html>
<head>
	<title>Admin Pet Adoption</title>
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
    tr:hover {
        background-color: #f5f5f5;
    }
    img {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
    td.background,
    td.current_situation,
    td.personality {
    text-align: justify;
}
    </style>

<?php
// Connect to database
session_start();
include 'db.php';

// Retrieve data from the pets table
$sql = "SELECT * FROM pets ORDER BY pet_id DESC";
$result = mysqli_query($conn, $sql);

// Check if any pets were found
if (mysqli_num_rows($result) > 0) {
    // Display pets in a table
    echo "<table>";
    echo "<tr><th>Pet ID</th><th>Name</th><th>Species</th><th>Breed</th><th>Age</th><th>Gender</th><th>Background</th><th>Current Situation</th><th>Personality</th><th>Image</th><th>Edit</th><th>Delete</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['pet_id'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['species'] . "</td>";
        echo "<td>" . $row['breed'] . "</td>";
        echo "<td>" . $row['age'] . "</td>";
        echo "<td>" . $row['gender'] . "</td>";
        echo "<td class='background'>" . $row['background'] . "</td>";
        echo "<td class='current_situation'>" . $row['current_situation'] . "</td>";
        echo "<td class='personality'>" . $row['personality'] . "</td>";
        echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['pet_image']) . "' /></td>";
        echo "<td><a href='admin_edit_pet.php?pet_id=" . $row['pet_id'] . "'>Edit</a></td>";
        echo "<td><a href='admin_delete_pet.php?pet_id=" . $row['pet_id'] . "' onclick=\"return confirm('Are you sure you want to delete this pet?');\">Delete</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    // No pets found
    echo "No pets found.";
}
?>

