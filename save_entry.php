<?php
// Database connection
$host = 'localhost';
$dbname = 'smart_diary';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from POST request
$id = intval($_POST['id']);
$date = $_POST['date'];
$time = $_POST['time'];
$mood = $_POST['mood'];
$sentiment = $_POST['sentiment'];
$tags = $_POST['tags'];
$content = $_POST['content'];

// Update diary entry
$sql = "UPDATE diary_entries 
        SET entry_date = ?, entry_time = ?, mood = ?, sentiment = ?, tags = ?, content = ? 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $date, $time, $mood, $sentiment, $tags, $content, $id);
if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Failed to update entry"]);
    exit;
}

// Handle new images
if (isset($_FILES['new-image'])) {
    $uploadedFiles = $_FILES['new-image'];
    for ($i = 0; $i < count($uploadedFiles['name']); $i++) {
        $targetFile = "uploads/" . basename($uploadedFiles['name'][$i]);
        if (move_uploaded_file($uploadedFiles['tmp_name'][$i], $targetFile)) {
            $sqlImage = "INSERT INTO diary_images (diary_entry_id, image_path) VALUES (?, ?)";
            $stmtImage = $conn->prepare($sqlImage);
            $stmtImage->bind_param("is", $id, $targetFile);
            $stmtImage->execute();
        }
    }
}

echo json_encode(["success" => true]);
$conn->close();
?>
