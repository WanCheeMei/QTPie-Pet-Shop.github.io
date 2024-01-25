<?php
  // Connect to the database
  session_start();
  include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Checkout</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--To link and get the icons in font-awesome-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!--To link css file-->
	<link rel="stylesheet" href="css/mystyle.css">

        <!--  To link jquery -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<!-- To link bootstrap js file -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<!-- To link bootstrap cdn -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">

        <audio id="audio-player" src="image/relax.mp3" autoplay></audio>

<script>
  var audioPlayer = document.getElementById("audio-player");

  function togglePlay() {
    if (audioPlayer.paused) {
      audioPlayer.play();
    } else {
      audioPlayer.pause();
    }
  }

  window.addEventListener("load", function() {
    var autoplay = localStorage.getItem("autoplay");
    if (autoplay === "true") {
      audioPlayer.currentTime = localStorage.getItem("currentTime") || 0;
      audioPlayer.play();
    } else {
      audioPlayer.pause();
    }
  });

  window.addEventListener("beforeunload", function() {
    localStorage.setItem("autoplay", audioPlayer.paused ? "false" : "true");
    localStorage.setItem("currentTime", audioPlayer.currentTime);
  });
  audioPlayer.addEventListener('ended', function() {
  audioPlayer.currentTime = 0;
  audioPlayer.play();
});
</script>

        
<style>
.address-form {
  max-width: 500px;
  margin: 0 auto;
  padding: 20px;
  background-color: #f5f5f5;
  border-radius: 5px;
  box-shadow: 0px 0px 5px #ccc;
}

.address-form label {
  display: block;
  margin-bottom: 10px;
  font-weight: bold;
}

.address-form input[type="text"] {
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border-radius: 5px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.address-form input[type="submit"] {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.address-form input[type="submit"]:hover {
  background-color: #45a049;
}

.payment-form {
  max-width: 500px;
  margin: 0 auto;
  margin-top:1%;
  padding: 20px;
  background-color: #f5f5f5;
  border-radius: 5px;
  box-shadow: 0px 0px 5px #ccc;
}

</style>
        
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
    
    <!--For navigation bars-->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar">

	<!-- For SmartPhone User Interface or small window User Interface -->
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#link-bar" aria-controls="link-bar" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars"></i>
         </button>

	<!-- For Insert Company Logo -->
        <div class="logo">
            <a href="home.php"><img src="image/logo.png" alt="QTPie logo" class="logo-img"></a>
        </div>
    
	<!-- For PC and laptop User Interface -->
	<div class="collapse navbar-collapse" id="link-bar">
    	<ul class="navbar-nav mx-auto">
    		<li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>
		<li class="nav-item">
                    <a class="nav-link" href="product.php">Product</a>
                </li>
		<li class="nav-item">
                    <a class="nav-link" href="pet_adoption.php">Pet Adoption</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>

        </ul>

	<div class="Other_Function">
            <!-- Search button -->
            <form class="search" action="search_products.php" method="GET">
                <input type="text" placeholder="Search.." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>

            <!-- Cart Icon and User Profile Icon -->
            <div class="icons">
                <a href="user_booking.php" class="fas fa-paw"></a>
                <a href="user_view_wishlist.php" class="fas fa-heart"></a>
        	<?php
                // Check if the user is logged in
                if (isset($_SESSION['username'])) {
                // Retrieve the number of items in the cart for the current user
                $cart_query = "SELECT SUM(order_quantity) AS total_items FROM cart WHERE username='{$_SESSION['username']}'";
                $cart_result = mysqli_query($conn, $cart_query);
                $cart_row = mysqli_fetch_assoc($cart_result);
                $total_items = $cart_row['total_items'];
                } else {
                // User is not logged in, set total_items to 0
                $total_items = 0;
                }
                ?>

                <a href="user_view_cart.php">
                <i class="fas fa-shopping-cart"></i>
                <?php if ($total_items > 0): ?>
                <span class="badge badge-pill badge-success"><?php echo $total_items; ?></span>
                <?php endif; ?>
                </a>
                
                <a href="user_view_order.php" class="fas fa-shopping-bag"></a>
                
        	<a href="account_information.php" class="fas fa-user"></a>
                <a class="music-btn" href="#" onclick="togglePlay()"><i class="fas fa-music"></i></a>
            </div>
	</div>
            
        </nav>
    
  <h1 align="center" style="color:orange; margin-top: 12%;">Checkout</h1>

<?php
  
  // Retrieve user information
  $username = $_SESSION['username'];
  $sql = "SELECT cart.username, cart.phone, users.address FROM cart JOIN users ON cart.username = users.username WHERE cart.username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $phone = $row['phone'];
    $address = $row['address'];
  }

  // Update user information
  if (isset($_POST['update_address'])) {
    $address = $_POST['address'];

    $sql = "UPDATE users SET address = '$address' WHERE username = '$username'";
    if ($conn->query($sql) === TRUE) {
      $message = "Address updated successfully!";
      echo "<p style='text-align:center; color:green;'>Address updated successfully!</p>";
    } else {
      echo "Error updating record: " . $conn->error;
    }
  }

  // Display user information
  ?>
  <form method="POST" class="address-form">
    <label>Username:</label> <?php echo $username ?><br>
    <label>Phone:</label> <?php echo $phone ?><br>
    <label>Address:</label> <input type="text" name="address" value="<?php echo $address ?>"><br>
    <input type="submit" name="update_address" value="Update Address">
  </form>
  
  <?php
  // Retrieve product information
  $sql = "SELECT * FROM cart WHERE cart.username = '$username'";
  $result = $conn->query($sql);

  // Calculate total price
  $total_price = 0;

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $product_name = $row['productname'];
      $product_price = $row['price'] * $row['order_quantity'];
      $total_price += $product_price;
    }
  }

  // Display total price
  ?>
  
  
  <!-- Choose payment method -->
  <form method="POST" enctype="multipart/form-data" class="payment-form">
  <label>Total price:</label> RM <?php echo $total_price ?><br>
  <label>Choose payment method:</label><br>
  <input type="radio" name="payment_method" value="touch_n_go" onclick="showQRCode('image/tng.jpg')"> Touch n go<br>
  <input type="radio" name="payment_method" value="duitnow" onclick="showQRCode('image/duitnow.jpg')"> Duitnow<br>

  <div id="qrCode"></div>
  <script>
  function showQRCode(imageSrc) {
      var qrCodeDiv = document.getElementById('qrCode');
      qrCodeDiv.innerHTML = '<img src="' + imageSrc + '" width="300">';
  }
  </script>

  <label>Upload transaction receipt:</label><br>
  <input type="file" name="transaction_receipt"><br>

  <input type="submit" name="place_order" value="Place Order">
</form>
  
  <?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['payment_method']) && isset($_FILES['transaction_receipt'])) {
    $payment_method = $_POST['payment_method'];
    $transaction_receipt = '';
    
    // Check if a file was uploaded
    if ($_FILES['transaction_receipt']['error'] !== UPLOAD_ERR_OK) {
      $message = "Address updated successful! Please upload a transaction receipt.";
      echo "<script>alert('$message'); window.location.href='user_view_cart.php';</script>";
      exit;
    }
    
    // Check file type
    $file_type = $_FILES['transaction_receipt']['type'];
    if ($file_type != 'image/jpeg' && $file_type != 'image/png') {
      $message = "Please upload a JPEG or PNG file for the transaction receipt.";
      echo "<script>alert('$message'); window.location.href='user_view_cart.php';</script>";
      exit;
    }
    
    // Get file contents
    $transaction_receipt = file_get_contents($_FILES['transaction_receipt']['tmp_name']);
    $username = $_SESSION['username'];
  
    // Retrieve user address
    $sql = "SELECT address FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $address = $row['address'];
  
    // Get current timestamp
    $timestamp = date("Y-m-d H:i:s");

    // Insert order items into orders table
    $sql = "INSERT INTO orders (username, address, productname, order_quantity, payment_method, total_price, transaction_receipt, created_at) SELECT cart.username, users.address, cart.productname, cart.order_quantity, '$payment_method', cart.price * cart.order_quantity, ?, '$timestamp' FROM cart INNER JOIN users ON cart.username = users.username WHERE cart.username = '$username'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $transaction_receipt); // 's' indicates string data
    $stmt->execute();

    // Get the order ID of the most recent order
    $order_id = $conn->insert_id;

    // Clear cart for the user
    $sql = "DELETE FROM cart WHERE username = '$username'";
    $conn->query($sql);

    $message = "Order has been placed successfully! Your order ID is $order_id.";
    echo "<script>alert('$message'); window.location.href='user_view_cart.php';</script>";
  } else {
    $message = "Address updated successful! Please upload a transaction receipt.";
    echo "<script>alert('$message'); window.location.href='user_view_cart.php';</script>";
  }
}
$conn->close();
?>

  <footer class="pet_adoption_footer">
            <div class="social-media">
                <a href="https://www.facebook.com/"><i class="fab fa-facebook"></i></a>
                <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                <a href="https://twitter.com/"><i class="fab fa-twitter"></i></a>
                <a href="https://www.youtube.com/"><i class="fab fa-youtube"></i></a>
            </div>
            <div class="copyright">
                &copy; 2023 QTPie Pet Shop. All rights reserved.
            </div>
        </footer>
  
</body>
</html>
