<?php

session_start();
include 'db.php';

// Check if the form was submitted
if (isset($_POST['submit'])) {
    // Get the form data
    $adminusername = $_POST['adminusername'];
    $adminpassword = $_POST['adminpassword'];

    // Hash the password before storing it in the database
    $hashed_password = password_hash($adminpassword, PASSWORD_DEFAULT);

    // Check if the admin username already exists in the database
    $check_username_sql = "SELECT * FROM admins WHERE adminusername='$adminusername'";
    $check_username_result = mysqli_query($conn, $check_username_sql);

    if (mysqli_num_rows($check_username_result) > 0) {
        // The admin username already exists in the database
        echo "<script>alert('Register unsuccessful. Admin username already exists.');</script>";
    } else {
        // The admin username does not exist in the database
        // Prepare the SQL query
        $insert_admin_sql = "INSERT INTO admins (adminusername, adminpassword) VALUES ('$adminusername', '$hashed_password')";

        if (mysqli_query($conn, $insert_admin_sql)) {
                echo "<script>alert('Admin account created successfully!');</script>";
                header("Location:admin_login.php");
                exit();
            } else {
                echo "<script>alert('Error creating admin account: " . mysqli_error($conn) . "');</script>";
            }
        }
}

// Close the database connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Register</title>
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
            <a href="admin_dashboard.php"><img src="image/logo.png" alt="QTPie logo" class="logo-img"></a>
        </div>
    </header>

    <main>
        <form class="register-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h2>Register Admin</h2>
            <div class="form-group">
                <label for="adminusername">Admin Username</label>
                <input type="text" id="adminusername" name="adminusername" required>
            </div>
            <div class="form-group">
                <label for="adminpassword">Admin Password</label>
                <input type="password" id="adminpassword" name="adminpassword" required>
            </div>
            <button type="submit" name="submit">Register</button>
        </form>
    </main>

</body>

</html>
