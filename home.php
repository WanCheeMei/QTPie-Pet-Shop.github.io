<?php
session_start();
include'db.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>QTPie Pet Shop Home Page</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--To link and get the icons in font-awesome-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!--To link css file-->
	<link rel="stylesheet" href="css/mystyle.css">
        <script src="script.js"></script>
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
    // Set the autoplay key to true when the page is loaded
    localStorage.setItem("autoplay", "true");
    
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
        
        <script>
        function showProductDetails(button) {
            var productId = button.getAttribute('data-productid');
            var productName = document.querySelector('#details-' + productId + ' h4').textContent;
            var productDescription = document.querySelector('#details-' + productId + ' p').textContent;
            var productPrice = document.querySelector('#details-' + productId + ' .price').textContent;
            var productImage = document.querySelector('#details-' + productId + ' img').getAttribute('src');

        document.querySelector('#productDetailsModalLabel').textContent = productName;
        document.querySelector('#productDetails').innerHTML = `
        <img src="${productImage}" />
        <p>${productDescription}</p>
        <div class="price">${productPrice}</div>`;
        $('#productDetailsModal').modal('show');
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
            <a href="admin_login.php"><img src="image/logo.png" alt="QTPie logo" class="logo-img"></a>
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
	
	<section class="hero">
            
	</section>
	
        <br>
        
        <section class="shop" id="shop">

    <h1 align="center" style="color:orange;">Our Product</h1>

    <div class="box-container">
  <?php
    include 'db.php';
    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);
    $count = 0;
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        if ($count == 6) {
          break; // exit the loop once you have displayed the first 6 products
        }
        ?>
        <div class="box">
          <div class="icons">
            <form method="post" action="add_to_cart.php">
    <?php
        $productname = $row['productname'];
        $price = $row['price'];
    ?>
    <input type="hidden" name="productname" value="<?php echo $productname; ?>">
    <input type="hidden" name="price" value="<?php echo $price; ?>">
    <input type="hidden" name="order_quantity" value="1" min="1">
    <button type="submit" class="btn btn-secondary"><i class="fas fa-shopping-cart"></i>️</button>
</form>

            <form method="post" action="add_to_wishlist.php">
                <?php
                $productname = $row['productname'];
                $price = $row['price'];
                ?>
                <input type="hidden" name="productname" value="<?php echo $productname; ?>">
                <input type="hidden" name="price" value="<?php echo $price; ?>">
                <input type="hidden" name="order_quantity" value="1" min="1">
                <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-heart"></i>
                </button>
            </form>
  
            <button type="button" class="btn btn-outline-secondary view-details" data-productid="<?php echo $row['id']; ?>" onclick="showProductDetails(this)">
              <i class="fas fa-eye"></i>
            </button>
          </div>
          <div class="image">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']); ?>" />
          </div>
            
          <div class="content">
            <h3><?php echo $row['productname']; ?></h3>
            <?php if ($row['quantity'] > 0) { ?>
          <div class="quantity">Quantity: <?php echo $row['quantity']; ?></div>
            <?php } else { ?>
          <div class="out-of-stock">Out of stock</div>
            <?php } ?>
          <div class="amount">RM<?php echo $row['price']; ?></div>
          </div>
            
          <div class="details" id="details-<?php echo $row['id']; ?>" style="display: none;">
            <h4><?php echo $row['productname']; ?></h4>
            <p><?php echo $row['description']; ?></p>
            <div class="price">RM<?php echo $row['price']; ?></div>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']); ?>" />
          </div>
        </div>
        <?php
        $count++;
      }
    } else {
      echo "No products found.";
    }
    
  ?>
        

        
        
  <div class="modal" id="productDetailsModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productDetailsModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="productDetails"></div>
        </div>
      </div>
    </div>
  </div>
</div>
    
    
	
	<footer class="home_footer">
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
	
    </section>
</body>
</html>