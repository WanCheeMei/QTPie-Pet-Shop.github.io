<?php
// Start the session
session_start();

// Include the database connection
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect to the login page if the user is not logged in
  header('Location: login.php');
  exit;
}

// Retrieve the products in the user's wishlist
$wishlist_query = "SELECT * FROM wishlist WHERE username='{$_SESSION['username']}'";
$wishlist_result = mysqli_query($conn, $wishlist_query);
$wishlist_items = mysqli_fetch_all($wishlist_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Wishlist</title>
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
table {
  border-collapse: collapse;
  width: 90%;
  margin: 0 auto;
  background-color: #fff;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
  font-size: 16px;
}

table th,
table td {
  padding: 12px 15px;
  text-align: left;
  border: 1px solid #ddd;
}

table th {
  background-color: #F6B352;
  color: #fff;
  font-weight: bold;
  text-transform: uppercase;
}

table tr:nth-child(even) td {
  background-color: #f2f2f2;
}

table tr:hover td {
  background-color: #ddd;
}

.empty-wishlist {
  text-align: center;
  margin-top: 5%;
  margin-bottom:20%;
  font-size: 24px;
  font-weight: bold;
  
}
  </style>
  
  <script>
function confirmDelete() {
  if (confirm("Are you sure you want to remove this item from your wishlist?")) {
    return true;
  } else {
    return false;
  }
}
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

        <script>
  function addToCart() {
  var confirmed = confirm("Do you want to add this item to your cart?");
  if (confirmed) {
    return true; // Allow the form submission to proceed
  } else {
    return false; // Prevent the form submission
  }
}
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
        <br><br><br><br><br><br><br>
  <h1 align="center" style="color:orange;">My Wishlist</h1>

<?php if (count($wishlist_items) > 0): ?>
  <table>
    <thead>
      <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($wishlist_items as $wishlist_item): ?>
        <?php
        // Retrieve the product information from the products table
        $product_query = "SELECT * FROM products WHERE productname='{$wishlist_item['productname']}'";
        $product_result = mysqli_query($conn, $product_query);
        $product_row = mysqli_fetch_assoc($product_result);
        $image_base64 = base64_encode($product_row['image']);
        ?>
        <tr>
          <td>
            <img src="data:image/jpeg;base64,<?php echo $image_base64; ?>" alt="Product Image" width="100">
            <?php echo $wishlist_item['productname']; ?>
          </td>
          <td>RM <?php echo $product_row['price']; ?></td>
          <td>
            <form action="remove_wishlist.php" method="post" onsubmit="return confirmDelete();">
              <input type="hidden" name="productname" value="<?php echo $wishlist_item['productname']; ?>">
              <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
            </form>
              <br>
            <form action="wishlist_to_cart.php" method="post" onsubmit="return addToCart();">
              <input type="hidden" name="productname" value="<?php echo $wishlist_item['productname']; ?>">
              <?php
                // Check if the product is already in the cart
                $cart_query = "SELECT * FROM cart WHERE productname='{$wishlist_item['productname']}' AND username='{$_SESSION['username']}'";
                $cart_result = mysqli_query($conn, $cart_query);
                if (mysqli_num_rows($cart_result) > 0) {
                    echo '<button type="button" class="btn btn-secondary" disabled>Item is in the cart</button>';
}               else {
                    echo '<button type="submit" class="btn btn-primary">Add to Cart</button>';
}
              ?>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <p class="empty-wishlist">My wishlist is empty.</p>
<?php endif; ?>




    
    <footer class="user_wishlist_footer">
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