<?php
session_start();
include 'db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    if(isset($_SESSION['username'])) {
        // Retrieve the email of the logged-in user
        $stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Insert the feedback message and email into the feedback table
        $stmt = $conn->prepare("INSERT INTO feedback (username, email, feedback) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_SESSION['username'], $email, $_POST['message']);
        $stmt->execute();

        $success_msg = "Thank you for your feedback!";
        echo '<script>alert("' . $success_msg . '"); window.location.href="contact.php";</script>';
    } else {
        $error_msg = "You must login before submit a feedback.";
        echo '<script>alert("' . $error_msg . '");window.location.href="contact.php";</script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Contact Us</title>
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
        
        <div class="container-row">
            <!-- Location container -->
            <div class="contact-container1">
                <div class="contact-info">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Location</h3>
                    <p>No.1007,Taman 10, Jalan 07, 56000 Kuala Lumpur</p>
                </div>
            </div>
            <!-- Phone container -->
            <div class="contact-container2">
                <div class="contact-info">
                    <i class="fas fa-phone"></i>
                    <h3>Phone</h3>
                    <p>012-7832221</p>
                </div>
            </div>
            <!-- Fax container -->
            <div class="contact-container3">
                <div class="contact-info">
                    <i class="fas fa-fax"></i>
                    <h3>Fax</h3>
                    <p>0367832221</p>
                </div>
            </div>
            <!-- Email container -->
            <div class="contact-container4">
                <div class="contact-info">
                    <i class="fas fa-envelope"></i>
                    <h3>Email</h3>
                    <p>qtpie@petshop.com</p>
                </div>
            </div>
        </div>
        
        <br>
        
        <!-- Feedback form -->
        <form action="contact.php" method="post" class="contact">
            <br>
            <h1 class="feedback">Feedback:</h1>
            <textarea id="message" name="message" placeholder="Send your feedback here"></textarea>
            
            <div class="submit-container6">
                <input type="submit" name="feedback" value="Send">
            </div>
        </form>
        
        
        <!-- Footer of the page -->
        <footer class="contact_footer">
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
