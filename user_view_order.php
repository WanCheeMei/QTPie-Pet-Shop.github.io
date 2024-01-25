<?php
session_start();
include'db.php';

if(!isset($_SESSION['username'])){
    header("Location:login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>My Orders</title>
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
        
<style>
  /* style the output */
  .order-group {
    background-color: #f2f2f2;
    border-radius: 5px;
    box-shadow: 0 2px 2px rgba(0, 0, 0, 0.3);
    margin: 10px;
    margin-left: 5%;
    margin-bottom:1%;
    padding: 10px;
    width: 100%;
  }

  .order-group h3 {
    color: #007bff;
    margin-bottom: 10px;
  }

  .order {
    display: flex;
    flex-direction: column;
    margin-bottom: 1%;
  }

  .order p {
    margin: 0;
  }

  .order p:first-child {
    font-weight: bold;
    margin-bottom: 5px;
  }

  .order p:last-child {
    margin-bottom: 5px;
  }

  .order:nth-child(even) {
    background-color: #f2f2f2;
  }

  .order:hover {
    background-color: #d9d9d9;
    cursor: pointer;
  }

  .productname {
    width: 100%;
    margin-bottom: 5px;
  }

  .total {
    width: 100%;
    margin-bottom: 5px;
  }

  .quantity {
    width: 100%;
    margin-bottom: 5px;
  }

  .order_id {
    width: 100%;
    margin-bottom: 5px;
  }

  .od_status {
    width: 100%;
    margin-bottom: 5px;
  }

  .no-orders-message {
    text-align: center;
    margin-top: 5%;
    margin-bottom: 20%;
    font-size: 24px;
    font-weight: bold;
  }

 @media only screen and (min-width: 600px) {
    .order-group {
      margin-left: 12%;
      width: 1250px;
    }
    .order {
      flex-direction: row;
      justify-content: space-between;
    }
    .productname {
      width: 550px;
      margin-bottom: 0;
    }
    .total {
      width: 150px;
      margin-bottom: 0;
    }
    .quantity {
      width: 100px;
      margin-bottom: 0;
    }
    .order_id {
      width: 120px;
      margin-bottom: 0;
    }
    .od_status {
      width: 200px;
      margin-bottom: 0;
    }
  }
</style>

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
    <br><br><br><br><br><br><br>
        <h1 align="center" style="color:orange;">My Orders</h1>

<?php

$username = $_SESSION['username'];

$sql = "SELECT * FROM orders WHERE username='$username' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$current_date = "";
$current_time = "";

if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        $order_id = $row['order_id'];
        $productname = $row['productname'];
        $order_quantity = $row['order_quantity'];
        $total_price = $row['total_price'];
        $order_status = $row['order_status'];
        $order_timestamp = $row['created_at'];
        $order_date = date("Y-m-d", strtotime($order_timestamp));
        $order_time = date("H:i", strtotime($order_timestamp));

        // check if this is a new order group
        if ($current_date != $order_date || $current_time != $order_time) {
            // close the previous group
            if ($current_date != "") {
                echo "</div>";
            }
            // start a new group
            $current_date = $order_date;
            $current_time = $order_time;
            echo '<div class="order-group">';
            echo '<h3>' . $current_date . ' ' . $current_time . '</h3>';
        }

        // output the order details
        echo '<div class="order">';
        echo '<p class="order_id">Order ID: ' . $order_id . '</p>';
        echo '<p class="productname">Product Name: ' . $productname . '</p>';
        echo '<p class="quantity">Quantity: ' . $order_quantity . '</p>';
        echo '<p class="total">Total: RM' . $total_price . '</p>';
        echo '<p class="od_status">Order Status: ' . $order_status . '</p>';
        
        
        echo '</div>';
    }
    // close the last group
    echo '</div>';
} else {
    echo '<div class="no-orders-message">My orders is empty.</div>';
}
?>



    <footer class="user_booking_footer">
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

