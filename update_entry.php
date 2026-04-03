<?php
// Database connection
$host = 'localhost';
$dbname = 'smart_diary';
$username = 'root'; // Adjust to your DB username
$password = ''; // Adjust to your DB password

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve entry ID from request
$entryId = intval($_GET['id']);

// Retrieve updated data from POST request
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid data"]);
    exit;
}

// Update diary entry in the database
$sql = "UPDATE diary_entries 
        SET entry_date = ?, entry_time = ?, mood = ?, sentiment = ?, tags = ?, content = ? 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssi",
    $data['entry_date'],
    $data['entry_time'],
    $data['mood'],
    $data['sentiment'],
    $data['tags'],
    $data['content'],
    $entryId
);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error updating entry"]);
}

$conn->close();
?>
