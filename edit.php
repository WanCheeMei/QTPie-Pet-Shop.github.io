<?php
// Check if the user is logged in. If not, redirect to the login page
session_start();
include 'db.php';

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

if (mysqli_num_rows($result) == 1) {
  $row = mysqli_fetch_assoc($result);

  // If the form has been submitted, update the user's information in the database
  if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Check if the new email is already in use
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error_message = "Invalid email format. Please enter a valid email address.";
    } else {
      $sql = "SELECT * FROM users WHERE email='$email' AND username<>'$username'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
        // Display an error message
        $error_message = "Email address is already in use. Please choose a different email.";
      } else {
        // Check if phone number is valid
        if (!preg_match("/^[0-9]{10,11}$/", $phone)) {
          $error_message = "Invalid phone number. Please enter a phone number with 10 to 11 digits.";
        } else {
          // Check if fields are not null
          if (empty($email) || empty($phone) || empty($address)) {
            $error_message = "All fields are required. Please fill in all the fields.";
          } else {
            // Update the user's information in the database
            $sql = "UPDATE users SET email='$email', phone='$phone', address='$address' WHERE username='$username'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
              // Redirect the user back to their profile page with a success message
              header('Location: account_information.php?success=1');
              exit;
            } else {
              // Display an error message
              $error_message = "Error updating user information: " . mysqli_error($conn);
            }
          }
        }
      }
    }
  }
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Edit Information</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<body class="edit_information">
        
    <h1 align="center" style="color:orange;">Edit User Information</h1><br>
        
        
          <?php
          if (isset($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
          }
          ?>
        
        <div class="edit-form">
          <form method="post" action="">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $row['email']; ?>">
            
            <label for="phone">Phone:</label>
            <input type="text" name="phone" value="<?php echo $row['phone']; ?>">
            
            <label for="address">Address:</label>
            <textarea name="address" id="address"><?php echo $row['address']; ?></textarea>
            
            <div style="text-align: center;">
                <input type="submit" name="submit" value="Update Information">
            </div>
          </form>
          
          <a href="account_information.php" class="cancel">Cancel</a>
        </div>
          
</body>
</html>
