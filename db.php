<?php
//Define database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname= "qtpieshop";
        
// Create a new database connection
$conn= new mysqli($servername, $username, $password, $dbname);

//Check for errors
if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

//Connect successful
    echo " ";
