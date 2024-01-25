<!DOCTYPE html>
<html>
<head>
	<title>User Information</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<link rel="stylesheet" href="css/mystyle.css">

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
<body class="show_information">
        
    <h1 align="center" style="color:orange;">User Information</h1><br>
        
    <div class="user-info">
    <?php
    // Check if the user is logged in. If not, redirect to the login page
    session_start();
    include'db.php';

    if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
    }

    // Retrieve the user's information from the database
    $username = "";
    if(isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    }
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);


    if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Display the user's information
  
    echo "<p><strong>Username:</strong> " . $row["username"] . "</p>";
    echo "<p><strong>Email:</strong> " . $row["email"] . "</p>";
    echo "<p><strong>Phone number:</strong> " . $row["phone"] . "</p>";
    echo "<p><strong>Address:</strong> " . $row["address"] . "</p>";
    
    } else {
        echo "User not found";
    }

?>
            
            <form action="edit.php" method="post">
                <input type="submit" name="edit" value="Edit Information">
            </form>
            
            <form action="home.php" method="post">
                <input type="submit" name="back-to-home" value="Back to Home page">
            </form>
            
            <form action="logout.php" method="post">
                <input type="submit" name="logout" value="Logout">
            </form>

    </div>
          
</body>
</html>
