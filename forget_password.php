<?php
// Start the session
session_start();

// Include the database connection file
include('db.php');

// Check if the user has submitted the form
if (isset($_POST['submit'])) {
    // Get the user's email address or username
    $user_id = $_POST['user_id'];

    // Check if the email address or username is valid
    $stmt = $conn->prepare("SELECT * FROM users WHERE BINARY(email) = BINARY(?) OR BINARY(username) = BINARY(?)");
    $stmt->bind_param("ss", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Generate a random verification code
        $verification_code = mt_rand(100000, 999999);

        // Save the verification code to the database
        $row = $result->fetch_assoc();
        $user_id = $row['id'];
        $stmt = $conn->prepare("INSERT INTO password_reset (user_id, verification_code) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $verification_code);
        $stmt->execute();

        // Send the verification code to the user's email address
        $to = $row['email'];
        $subject = "Password reset verification code";
        $message = "Your verification code is: " . $verification_code;
        mail($to, $subject, $message);

        // Set a session variable with the user's ID
        $_SESSION['user_id'] = $user_id;

        // Redirect the user to the password reset page
        header("Location: reset_password.php");
        exit();
    } else {
        // Show an error message if the email address or username is not found
        $error_message = "Invalid email address or username";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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

<body class="forget_password">
    <h1 align="center" style="color:orange;">Forgot Password</h1><br>
    <?php if (isset($error_message)) { ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php } ?>
    <form method="post" class="forget">
        <label for="user_id">Email address or username:</label>
        <input type="text" name="user_id" id="user_id" placeholder="Please enter your username or email...">
        <br>
        <div class="submit-container3" style="text-align: center;">
            <input type="submit" name="submit" value="Submit">
        </div>
        <a href="login.php" class="back-to-login">Back to Login</a>
    </form>
        
    <p style="background-image: url('2.jpg');">
</body>
</html>

