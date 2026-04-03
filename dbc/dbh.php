<?php

// dbh.php - Database connection file

// Database credentials
$servername = "localhost";  // or the appropriate host
$username = "root";         // your DB username
$password = "";             // your DB password
$dbname = "smart_diary";    // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "Connection sucess!";
}
?>
