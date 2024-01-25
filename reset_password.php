<?php
// Start the session
session_start();

// Include the database connection file
include('db.php');

// Check if the user has submitted the form
if (isset($_POST['submit'])) {
    // Get the user's ID from the session variable
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        // handle the case where user_id is not set
    }

    // Get the new password and confirm password from the form
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the new password and confirm password
    if (empty($new_password) || empty($confirm_password)) {
        $error_message = "Please enter a new password and confirm password";
    } elseif ($new_password != $confirm_password) {
        $error_message = "New password and confirm password do not match";
    } elseif (strlen($new_password) < 8) {
        $error_message = "New password should be at least 8 characters long";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's password in the database
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        $stmt->execute();

        // Redirect the user to the login page
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
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
<body class="reset_password">
    <h1 align="center" style="color:orange;">Reset Password</h1><br>
    <link rel="stylesheet" href="css/mystyle.css">
    <?php if (isset($error_message)) { ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php } ?>
    <form method="post" class="reset">
        <label for="new_password">New password:</label>
        <input type="password" name="new_password" id="new_password" placeholder="Please enter at least 8 character of password...">
        <label>
            <input type="checkbox" id="show_new_password">
            <span style="color: #fd7800;">Show password</span>
        </label>
        <br>
        <label for="confirm_password">Confirm password:</label>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Please confirm your password...">
        <label>
            <input type="checkbox" id="show_confirm_password">
            <span style="color: #fd7800;">Show password</span>
        </label>
        <br>
        <div class="submit-container4" style="text-align: center;">
            <input type="submit" name="submit" value="Reset Password">
        </div>
        <a href="forget_password.php" class="back-to-password">Back to Forget Password</a>
    </form>
        
    <script>
        var showNewPassword = document.getElementById("show_new_password");
        var showConfirmPassword = document.getElementById("show_confirm_password");
        var newPasswordInput = document.getElementById("new_password");
        var confirmPasswordInput = document.getElementById("confirm_password");

        showNewPassword.addEventListener("change", function() {
            newPasswordInput.type = showNewPassword.checked ? "text" : "password";
        });

        showConfirmPassword.addEventListener("change", function() {
            confirmPasswordInput.type = showConfirmPassword.checked ? "text" : "password";
        });
    </script>
</body>
</html>
