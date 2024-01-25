<?php
session_start();
include 'db.php';

$sql = "SELECT * FROM orders";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin View Order</title>
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
        document.querySelector('select[name="order_status"]').addEventListener('change', function() {
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
    .container-order {
  margin-top: 20px;
  margin-bottom: 20px;
}

.container-order h1 {
  margin-left: 3%;
  margin-bottom: 10px;
}

table {
  border-collapse: collapse;
  width: 93%;
  margin-left:2%;
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
th:nth-child(4), td:nth-child(4) {
  width: 20%;
}

.processed {
  background-color: blue;
}

.shipped {
  background-color: green;
}

.cancelled {
  background-color: red;
}
.order-date {
  font-size: 1.2rem;
  margin-left:2%;
  margin-top:2%;
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

<div class="container-order">
    <br>
    <h1 style="margin-left:3%;">Customer Order</h1>

    <?php
    $result = mysqli_query($conn, "SELECT * FROM orders ORDER BY created_at DESC");
    $prev_datetime = null;
    while ($row = mysqli_fetch_assoc($result)) {
        $curr_datetime = $row['created_at'];
        if ($curr_datetime !== $prev_datetime) {
            // Start a new container table for orders with a new date and time
            if ($prev_datetime !== null) {
                echo '</table>';
            }
            echo '<h2 class="order-date">' . $curr_datetime . '</h2>';
            echo '<table>
                  <tr>
                      <th>Order ID</th>
                      <th>Username</th>
                      <th>Address</th>
                      <th>Product Name</th>
                      <th>Order Quantity</th>
                      <th>Total Price</th>
                      <th>Payment Method</th>
                      <th>Transaction Receipt</th>
                      <th>Order Status</th>
                      <th>Date and Time</th>
                  </tr>';
        }
        // Add the order to the current container table
        echo '<tr>
                <td>' . $row['order_id'] . '</td>
                <td>' . $row['username'] . '</td>
                <td>' . $row['address'] . '</td>
                <td>' . $row['productname'] . '</td>
                <td>' . $row['order_quantity'] . '</td>
                <td>' . $row['total_price'] . '</td>
                <td>' . $row['payment_method'] . '</td>
                <td>';
        $order_id = $row['order_id'];
        $receipt_data = base64_encode($row['transaction_receipt']);
        echo '<a href="#" onclick="window.open(\'view_receipt.php?order_id='.$order_id.'\', \'receiptWindow\', \'width=800,height=600\')">
              View Receipt
              </a>';
        echo '</td>
              <td class="' . strtolower($row['order_status']) . '">
              <form method="post" action="update_order_status.php">
                  <input type="hidden" name="order_id" value="' . $row['order_id'] . '">
                  <select name="order_status" onchange="this.form.submit()">
                      <option value="processed" ' . ($row['order_status'] == 'processed' ? 'selected' : '') . '>Processed</option>
                      <option value="shipped" ' . ($row['order_status'] == 'shipped' ? 'selected' : '') . '>Shipped</option>
                      <option value="cancelled" ' . ($row['order_status'] == 'cancelled' ? 'selected' : '') . '>Cancelled</option>
                  </select>
              </form>
              </td>
              <td>' . $row['created_at'] . '</td>
              </tr>';
        $prev_datetime = $curr_datetime;
    }
    // Close the last container table
    if ($prev_datetime !== null) {
        echo '</table>';
    }
    ?>

</div>

</body>
</html>


