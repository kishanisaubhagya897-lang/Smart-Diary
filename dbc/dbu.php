<?php
// Database configuration
$host = "localhost"; // Replace with your host (e.g., 127.0.0.1)
$dbname = "smart_diary"; // Database name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password

try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection errors
    die("Database connection failed: " . $e->getMessage());
}
?>
