<?php
session_start();
include 'db.php';

$sql = "SELECT * FROM booking";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin View Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    
   <script>
  document.querySelector('select[name="status"]').addEventListener('change', function() {
    this.form.submit();
  });
   </script>
   
   <style>
  
  header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: #ffcd91;
  color: #fff;
  padding: 10px 20px;
}

.logo {
  margin-top: 1%;
}

.logo img {
  height: 100px;
  width: auto;
}

nav {
  flex-grow: 1;
  text-align: center;
  margin-left: auto;
}

nav ul {
  margin: 0;
  padding: 0;
  list-style: none;
  display: inline-block;
  font-size: 20px;
  color: #000;
}

nav ul li {
  display: inline-block;
  position: relative;
  margin-right: 25px;
}

nav ul li:last-child {
  margin-right: 0; /* remove margin from last element */
}

nav ul ul {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  background-color: #ffcd91;
}

nav ul ul li {
  display: block;
  width: 200px;
}

nav ul ul a {
  display: block;
  padding: 10px;
  color: #fff;
  text-decoration: none;
}

nav ul ul a:hover {
  background-color: #ffcd91;
}

nav > ul > li:hover > ul {
  display: block;
}

.admin_logout {
  margin-top: 1%;
}

.admin_logout a {
  display: inline-block;
  padding: 10px;
  background-color: #fff;
  color: #333;
  text-decoration: none;
  border-radius: 5px;
}

.admin_logout a:hover {
  background-color: #FF8C00;
}
  select {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #fff;
  color: #555;
  font-size: 16px;
  font-family: Arial, sans-serif;
  cursor: pointer;
}

select option {
  background-color: #fff;
  color: #555;
  font-size: 16px;
  font-family: Arial, sans-serif;
}
table {
  border-collapse: collapse;
  width: 93%;
}

th, td {
  text-align: left;
  padding: 8px;
  border: 1px solid #ddd;
}

th {
  background-color: orange;
  color: white;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}

tr:hover {
  background-color: #ddd;
}
td.reserve {
  background-color: green;
}

td.rejected {
  background-color: red;
}

td.pending {
  background-color: blue;
}
   </style>

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
    <div class="container">
    <br>
   <h1>Booking Information</h1>
<table>
    <tr>
        <th>Booking ID</th>
        <th>Username</th>
        <th>Phone</th>
        <th>Pet Name</th>
        <th>Booking Date</th>
        <th>Status</th>
    </tr>
    <?php 
    $result = mysqli_query($conn, "SELECT * FROM booking ORDER BY booking_id DESC");
    while ($row = mysqli_fetch_assoc($result)) { 
    ?>
        <tr>
            <td><?php echo $row['booking_id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['booking_date']; ?></td>
            <td class="<?php echo strtolower($row['status']); ?>">
    <form method="post" action="update_booking_status.php">
        <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
        <select name="status" onchange="this.form.submit()">
            <option value="reserve" <?php if ($row['status'] == 'reserve') echo 'selected'; ?>>Reserve</option>
            <option value="rejected" <?php if ($row['status'] == 'rejected') echo 'selected'; ?>>Rejected</option>
            <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
        </select>
    </form>
</td>
        </tr>
    <?php } ?>
</table>
</div>

<?php mysqli_close($conn); ?>

    
    


