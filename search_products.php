<?php	
session_start();
include'db.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>QTPie Pet Shop Search Page</title>
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
        
<style>
    .no-products {
        color: red;
        font-size: 24px;
        text-align: center;
        margin-top: 8%;
        margin-bottom:15%;
        font-weight: bold;
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
        
<br><br><br><br><br><br><br><br>
<h1 align="center" style="color:orange">Search Product</h1>
    
        
<?php	

// Check if search query is set in GET parameter
if (isset($_GET['search'])) {
    // Retrieve search query from GET parameter
    $search_query = $_GET['search'];

    // Define categories to search for
    $categories = array("Dog Food", "Cat Food", "Dog Treats", "Cat Treats", "Dog Toys", "Cat Toys", "Dog Clothes", "Cat Clothes");

    // Check if search query matches a category name
    $category_match = false;
    foreach ($categories as $category) {
        if (strtolower(str_replace(" ", "", $search_query)) === strtolower(str_replace(" ", "", $category))) {
            $category_match = true;
            $search_query = str_replace(" ", "", $category);
            break;
        }
    }

    // Construct SQL query
    if ($category_match) {
        $sql = "SELECT * FROM products WHERE category = '$search_query'";
    } else {
        $sql = "SELECT * FROM products WHERE productname LIKE '%$search_query%' OR category LIKE '%$search_query%'";
    }

    // Execute query and retrieve results
    $result = mysqli_query($conn, $sql);

    // Display results
if (mysqli_num_rows($result) > 0) {
    echo '<section class="sale">';
    echo '<div class="sale-container">';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="box ' . $row['category'] . '">';
        echo '<div class="icons">';
        echo '<form method="post" action="add_to_cart.php">';
        $productname = $row['productname'];
        $price = $row['price'];
        echo '<input type="hidden" name="productname" value="' . $productname . '">';
        echo '<input type="hidden" name="price" value="' . $price . '">';
        echo '<input type="hidden" name="order_quantity" value="1" min="1">';
        echo '<button type="submit" class="btn btn-secondary"><i class="fas fa-shopping-cart"></i>️</button>';
        echo '</form>';
        echo '<form method="post" action="add_to_wishlist.php">';
        $productname = $row['productname'];
        $price = $row['price'];
        echo '<input type="hidden" name="productname" value="' . $productname . '">';
        echo '<input type="hidden" name="price" value="' . $price . '">';
        echo '<input type="hidden" name="order_quantity" value="1" min="1">';
        echo '<button type="submit" class="btn btn-secondary"><i class="fas fa-heart"></i></button>';
        echo '</form>';
        echo '<button type="button" class="btn btn-outline-secondary view-details" data-productid="' . $row['id'] . '" onclick="showProductDetails(this)"><i class="fas fa-eye"></i></button>';
        echo '</div>';
        echo '<div class="image">';
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" />';
        echo '</div>';
        echo '<div class="content">';
        echo '<h3>' . $row['productname'] . '</h3>';
        if ($row['quantity'] > 0) {
            echo '<div class="quantity">Quantity: ' . $row['quantity'] . '</div>';
        } else {
            echo '<div class="out-of-stock">Out of stock</div>';
        }
        echo '<div class="amount">RM' . $row['price'] . '</div>';
        echo '</div>';
        echo '<div class="details" id="details-' . $row['id'] . '" style="display: none;">';
        echo '<h4>' . $row['productname'] . '</h4>';
        echo '<p>' . $row['description'] . '</p>';
        echo '<div class="price">RM' . $row['price'] . '</div>';
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" />';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p class="no-products">No products found.</p>';
}
}
?>

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
</section>
        
        <footer class="product_footer">
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
