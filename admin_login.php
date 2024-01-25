<?php
session_start();
include 'db.php';

// Check if the form was submitted
if (isset($_POST['submit'])) {
    // Get the form data
    $adminusername = $_POST['adminusername'];
    $adminpassword = $_POST['adminpassword'];

    // Prepare the SQL query
    $select_admin_sql = "SELECT * FROM admins WHERE BINARY adminusername = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $select_admin_sql);

    // Bind parameters to statement
    mysqli_stmt_bind_param($stmt, "s", $adminusername);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result set
    $select_admin_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($select_admin_result) > 0) {
        // Admin username exists in the database
        $admin = mysqli_fetch_assoc($select_admin_result);

        // Check if the password is correct
        if (password_verify($adminpassword, $admin['adminpassword'])) {
            // Set the admin session variables
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $admin['adminusername'];

            // Redirect to the dashboard
            header('Location: admin_dashboard.php');
            exit;
        } else {
            $error = 'Incorrect password.';
        }
    } else {
        $error = 'Admin account not found.';
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
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

    <header>
        <div class="logo">
            <a href="home.php"><img src="image/logo.png" alt="QTPie logo" class="logo-img"></a>
        </div>
    </header>

    <main>
        <form class="login-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h2>Admin Login</h2>
            <?php if (isset($error)) { ?>
                <p class="error"><?php echo $error; ?></p>
            <?php } ?>
            <div class="form-group">
                <label for="adminusername">Admin Username</label>
                <input type="text" id="adminusername" name="adminusername" required>
            </div>
            <div class="form-group">
                <label for="adminpassword">Admin Password</label>
                <input type="password" id="adminpassword" name="adminpassword" required>
            </div>
            <button type="submit" name="submit">Login</button>
        </form>
        
       
        
    </main>

</body>
</html>


