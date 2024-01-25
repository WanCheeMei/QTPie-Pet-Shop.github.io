<!DOCTYPE html>
<html>
<head>
    <title>Admin View Feedback</title>
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

<div class="container">
    <br>
    <h1>Customer Feedback</h1>

    <?php
    session_start();
    include 'db.php';

    // Select all feedback from the database
    $select_feedback_sql = "SELECT * FROM feedback ORDER BY created_at DESC";
    $select_feedback_result = mysqli_query($conn, $select_feedback_sql);

    // Check if there is any feedback in the database
    if (mysqli_num_rows($select_feedback_result) > 0) {
        // Feedback exists, create a table to display the feedback
        echo '<table>';
        echo '<tr><th>Username</th><th>Email</th><th>Feedback</th><th>Created At</th></tr>';

        while ($feedback = mysqli_fetch_assoc($select_feedback_result)) {
            echo '<tr>';
            echo '<td>' . $feedback['username'] . '</td>';
            echo '<td>' . $feedback['email'] . '</td>';
            echo '<td>' . $feedback['feedback'] . '</td>';
            echo '<td>' . $feedback['created_at'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        // No feedback in the database
        echo 'No feedback found.';
    }

    // Close the database connection
    mysqli_close($conn);
    ?>
</div>

</body>
</html>