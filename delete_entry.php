<?php
// delete_entry.php
include('dbc/dbh.php');

$id = $_GET['id'];

// Delete the entry and associated images
$query = "DELETE FROM diary_entries WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false]);
}
?>
