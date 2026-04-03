<?php
include 'dbc/dbh.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $entry_date = $_POST['entry_date'];
    $entry_time = $_POST['entry_time'];
    $mood = $_POST['mood'];
    $content = $_POST['content'];

    // Update query
    $query = "UPDATE diary_entries SET entry_date = ?, entry_time = ?, mood = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $entry_date, $entry_time, $mood, $content, $id);

    if ($stmt->execute()) {
        echo "Entry updated successfully!";
    } else {
        echo "Error updating entry: " . $conn->error;
    }
    $stmt->close();
}
?>
