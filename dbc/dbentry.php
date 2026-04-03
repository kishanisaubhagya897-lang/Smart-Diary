<?php
// Database connection settings
$servername = "localhost";  // Database server
$username = "root";         // Database username
$password = "";             // Database password (leave empty for default XAMPP)
$dbname = "smart_diary";        // Database name (adjust to match your actual database name)

try {
    // Create a new PDO connection to MySQL
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optionally, set the character set to UTF-8
    $conn->exec("SET NAMES utf8");
    
    // Connection successful
} catch (PDOException $e) {
    // If connection fails, show the error message
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>
