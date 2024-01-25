<!DOCTYPE html>
<html>
<head>
	<title>QTPie Pet Shop</title>
	<link rel="stylesheet" href="css/mystyle.css">
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
<body class="login">
        
        <h1 align="center" style="color:orange;">Login Form</h1><br>
        
        <form action="login.php" method="post" class="login">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username"><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <button type="button" id="show-password">Show Password</button>
            
            <script>
                const showPasswordButton = document.querySelector('#show-password');
                const passwordField = document.querySelector('#password');

                        showPasswordButton.addEventListener('click', function() {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordField.setAttribute('type', type);
                this.textContent = type === 'password' ? 'Show Password' : 'Hide Password';
                });
            </script>
        <div class="remember">
            <input type="checkbox" name="" id="remember_me">
            <label for="remember_me">Remember me</label>
        </div>
        <div class="submit-container">
            <input type="submit" value="Login">
        </div>
            <a href="forget_password.php" class="forget-password">Forget Password</a>
        </form>

        <p align="center" class="register-message">Don't have an account? <a href="register.php" class="register-here">Register here</a></p>
        
        <div class="back-to-home-container">
        <a href="home.php" class="back-to-home">Back to Home Page</a>
        </div>

        
</body>
</html>

  
<?php

// Start the session
session_start();
include'db.php';


// Verify form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the user data from the database using the entered username
  if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $sql = "SELECT * FROM users WHERE BINARY username='$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // Compare the entered password with the password stored in the database
    if (isset($_POST['password'])) {
      $password = $_POST['password'];
      if ($user && password_verify($password, $user['password'])) {
          // Authentication succeeded, set session variable and show welcome message
          $_SESSION['username'] = $user['username'];
          $welcome_message = "Welcome, " . $user['username'] . "!";
          echo "<script>alert('$welcome_message');window.location.href='home.php';</script>";
    
          
      } else {
          // Authentication failed, show an error message
          echo "<script>alert('Invalid username or password.');</script>";
      }
    } else {
      echo "<script>alert('Password is not set.');</script>";
    }
  } else {
    echo "<script>alert('Username is not set.');</script>";
  }
}


?>