<?php
// Retrieve pet details from database based on pet_id
session_start();
include 'db.php';

$pet_id = $_GET['pet_id'];
$sql = "SELECT * FROM pets WHERE pet_id = $pet_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Handle form submission
if (isset($_POST['submit'])) {
    // Retrieve form data and escape special characters
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $species = mysqli_real_escape_string($conn, $_POST['species']);
    $breed = mysqli_real_escape_string($conn, $_POST['breed']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $background = mysqli_real_escape_string($conn, $_POST['background']);
    $current_situation = mysqli_real_escape_string($conn, $_POST['current_situation']);
    $personality = mysqli_real_escape_string($conn, $_POST['personality']);

    // Check if a new pet_image file has been uploaded
    if ($_FILES['pet_image']['name']) {
        $pet_image = $_FILES['pet_image']['tmp_name'];
        $pet_image_type = $_FILES['pet_image']['type'];

        if ($pet_image_type == 'image/jpeg') {
            $pet_image_data = addslashes(file_get_contents($pet_image));
            $sql = "UPDATE pets SET name='$name', species='$species', breed='$breed', age='$age', gender='$gender', background='$background', current_situation='$current_situation', personality='$personality', pet_image='$pet_image_data' WHERE pet_id=$pet_id";
        } else {
            echo "Only JPG files are allowed.";
            exit();
        }
    } else {
        // No new pet_image file has been uploaded
        $sql = "UPDATE pets SET name='$name', species='$species', breed='$breed', age='$age', gender='$gender', background='$background', current_situation='$current_situation', personality='$personality' WHERE pet_id=$pet_id";
    }

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_msg'] = "Pet updated successfully!";
        header("Location: admin_pet_adoption.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Edit Pet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
   <style>
.edit_pet_container {
width: 80%;
max-width: 800px;
margin: 0 auto;
padding: 20px;
background-color: #f0f0f0;
border-radius: 10px;
margin-top: 5%;
}

.edit_pet_container h1 {
margin-top: 0;
}

.edit_pet_container form {
display: flex;
flex-direction: column;
}

.edit_pet_container label {
font-weight: bold;
margin-bottom: 5px;
}

.edit_pet_container input,
.edit_pet_container textarea,
.edit_pet_container select {
padding: 10px;
margin-bottom: 15px;
border: none;
border-radius: 5px;
font-size: 16px;
}

.edit_pet_container input[type="submit"] {
background-color: #4CAF50;
color: white;
padding: 10px 20px;
border: none;
border-radius: 5px;
cursor: pointer;
font-size: 16px;
}

.edit_pet_container input[type="submit"]:hover {
background-color: #3e8e41;
}

.edit_pet_container input[type="file"] {
margin-bottom: 0;
}

.edit_pet_container textarea {
height: 150px;
}

.edit_pet_container label.error {
color: red;
margin-bottom: 10px;
}

</style>

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
    
    <div class="edit_pet_container">
        <h1>Edit Pet</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>"><br>
        <label for="species">Species:</label><br>
        <input type="text" id="species" name="species" value="<?php echo htmlspecialchars($row['species']); ?>"><br>
        <label for="breed">Breed:</label><br>
        <input type="text" id="breed" name="breed" value="<?php echo htmlspecialchars($row['breed']); ?>"><br>
        <label for="age">Age:</label><br>
        <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($row['age']); ?>"><br>
        <label for="gender">Gender:</label><br>
        <select id="gender" name="gender">
            <option value="male" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'male') echo 'selected'; ?>>Male</option>
            <option value="female" <?php if (isset($_POST['gender']) && $_POST['gender'] == 'female') echo 'selected'; ?>>Female</option>
        </select><br>
        <label for="background">Background:</label><br>
        <textarea id="background" name="background"><?php echo htmlspecialchars($row['background']); ?></textarea><br>
        <label for="current_situation">Current Situation:</label><br>
        <textarea id="current_situation" name="current_situation"><?php echo htmlspecialchars($row['current_situation']); ?></textarea><br>
        <label for="personality">Personality:</label><br>
        <textarea id="personality" name="personality"><?php echo htmlspecialchars($row['personality']); ?></textarea><br>
        <label for="pet_image">Image:</label><br>
        <?php if ($row['pet_image']): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['pet_image']); ?>" width="100">
        <?php endif; ?>
        <input type="file" id="pet_image" name="pet_image"><br>
        <input type="submit" name="submit" value="Save Changes">
    </form>
    </div>
</body>
</html>