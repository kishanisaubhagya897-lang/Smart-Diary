<?php
// get_entry.php
include('dbc/dbh.php');

$id = $_GET['id'];

// Fetch entry data from the database
$query = "SELECT * FROM diary_entries WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$entry = $result->fetch_assoc();

if ($entry) {
  // Fetch images related to this entry
  $imageQuery = "SELECT image_path FROM diary_images WHERE diary_entry_id = ?";
  $imageStmt = $conn->prepare($imageQuery);
  $imageStmt->bind_param("i", $id);
  $imageStmt->execute();
  $imageResult = $imageStmt->get_result();
  
  $images = [];
  while ($image = $imageResult->fetch_assoc()) {
    $images[] = $image['image_path'];
  }

  $entry['images'] = $images;
  echo json_encode($entry);
} else {
  echo json_encode(['error' => 'Entry not found']);
}
?>
