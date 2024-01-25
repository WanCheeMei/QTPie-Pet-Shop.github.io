<?php
session_start();
include 'db.php';

// Check if the form has been submitted
if (isset($_POST['submit'])) {

  // Get the form data
  $name = $_POST['name'];
  $species = $_POST['species'];
  $breed = $_POST['breed'];
  $age = $_POST['age'];
  $gender = $_POST['gender'];
  $background = $_POST['background'];
  $current_situation = $_POST['current_situation'];
  $personality = $_POST['personality'];
  $pet_image = addslashes(file_get_contents($_FILES['pet_image']['tmp_name']));

  // Prepare the SQL query
  $query = "INSERT INTO pets (name, species, breed, age, gender, background, current_situation, personality, pet_image) VALUES ('$name', '$species', '$breed', '$age', '$gender', '$background', '$current_situation', '$personality', '$pet_image')";

  // Execute the query
  if (mysqli_query($conn, $query)) {
    $_SESSION['success_msg'] = "Pet added successfully!";
    header("Location: admin_pet_adoption.php");
    exit();
  } else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
  }

  // Close the database connection
  mysqli_close($conn);

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Add Pet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">

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
  <nav>
    <ul>
      <li><a href="admin_dashboard.php">Products</a>
        <ul>
          <li><a href="admin_add_product.php">Add Product</a></li>
          <li><a href="admin_dashboard.php">Edit Product</a></li>
          <li><a href="admin_dashboard.php">Delete Product</a></li>
        </ul>
      </li>
      <li><a href="admin_pet_adoption.php">Pet Adoption</a>
        <ul>
          <li><a href="admin_add_pet.php">Add Pet</a></li>
          <li><a href="admin_pet_adoption.php">Edit Pet</a></li>
          <li><a href="admin_pet_adoption.php">Delete Pet</a></li>
        </ul>
      </li>
      <li><a href="admin_view_order.php">Customer Orders</a></li>
      <li><a href="admin_view_booking.php">Customer Booking</a></li>
      <li><a href="admin_view_feedback.php">Customer Feedback</a></li>
    </ul>
  </nav>
  <div class="admin_logout">
    <a href="admin_logout.php">Logout</a>
  </div>
</header>
    
    <div class="add_pet_container">
    <h1>Add a new pet</h1>
        <form action="admin_add_pet.php" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>

        <label for="species">Species:</label>
        <input type="text" name="species" required><br>

        <label for="breed">Breed:</label>
        <input type="text" name="breed"><br>

        <label for="age">Age:</label>
        <input type="number" name="age"><br>

        <label for="gender">Gender:</label>
        <select name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select><br>

        <label for="background">Background:</label>
        <textarea name="background"></textarea><br>

        <label for="current_situation">Current situation:</label>
        <textarea name="current_situation"></textarea><br>

        <label for="personality">Personality:</label>
        <textarea name="personality"></textarea><br>

        <label for="pet_image">Pet image:</label>
        <input type="file" name="pet_image" required><br>

        <input type="submit" name="submit" value="Add pet">
        </form>
    </div>
</body>
</html>
