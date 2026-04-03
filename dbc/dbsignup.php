<?php
// dbcsignup.php (inside the dbc folder)

$host = 'localhost';  // or your host
$dbname = 'smart_diary';  // your database name
$username = 'root';  // your database username
$password = '';  // your database password (default is empty for localhost)

// Create a PDO instance and set options
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die("Connection failed: " . $e->getMessage());
}
?>
