<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
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
<body class="register-page">
    <h1 align="center" style="color:orange;">Register Form</h1><br>
    <form action="register.php" method="post" class="register">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Please enter your username..."><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Please enter at least 8 character of password...">
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
            
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Please enter your email..."><br><br>
         <label for="phone">Phone number:</label>
        <input type="tel" id="phone" name="phone" placeholder="Please enter your phone number..."><br><br>
        <label for="address">Address:</label>
        <textarea id="address" name="address" placeholder="Please enter your address include street address, city, state, postal code, and country..."></textarea><br><br>
        <div class="submit-container1" style="text-align: center;">
            <input type="submit" value="Register">
        </div>
        <a href="login.php" class="back-to-login">Back to Login</a>
    </form>

<?php

session_start();
include'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Retrieve the values submitted by the user
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  
    // Validate the input fields
  if (empty($username)) {
    echo '<script>alert("Username is required.")</script>';
    exit;
  }

  if (empty($password)) {
    echo '<script>alert("Password is required.")</script>';
    exit;
  }

  if (empty($email)) {
    echo '<script>alert("Email is required.")</script>';
    exit;
  }

  if (empty($phone)) {
    echo '<script>alert("Phone number is required.")</script>';
    exit;
  }

  if (empty($address)) {
    echo '<script>alert("Address is required.")</script>';
    exit;
  }
  
  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo '<script>alert("Invalid email format. Please enter a valid email address.")</script>';
    exit;
  }

  // Validate phone number format
  if (!preg_match("/^[0-9]{10,11}$/", $phone)) {
    echo '<script>alert("Invalid phone number format. Please enter a 10 or 11-digit phone number without spaces or dashes.")</script>';
    exit;
  }

  if (strlen($password) < 8) {
  echo '<script>alert("Password must be at least 8 characters long.")</script>';
  exit;
}

  // Hash the user's password for security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  
  // Check if the username or email already exists in the database
  $sql = "SELECT * FROM users WHERE BINARY username='$username' OR BINARY email='$email'";
  $result = $conn->query($sql);


  if ($result->num_rows > 0) {
    // The username or email already exists, so display an error message
    echo '<script>alert("Username or email already exists. Please choose a different one.")</script>';
  } else {
    // Insert the user into the database
    $sql = "INSERT INTO users (username, password, email, phone, address) VALUES ('$username', '$hashed_password', '$email', '$phone', '$address')";
    $result = $conn->query($sql);

if ($result) {
  // User inserted successfully and bring the users to the login.php page
  echo '<script>alert("Register successfully!"); window.location = "login.php";</script>';
} else {
  // An error occurred while inserting the user
  echo '<script>alert("Registration failed. Please try again.")</script>';
}
  }
}?>
</body>
</html>
