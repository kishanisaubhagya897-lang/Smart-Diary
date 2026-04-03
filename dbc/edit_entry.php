<?php
// edit_entry.php
include('dbc/dbh.php');

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  // Fetch the specific diary entry
  $query = "SELECT * FROM diary_entries WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $entry = $result->fetch_assoc();
      echo '<form id="edit-form">';
      echo '<label>Date:</label>';
      echo '<input type="date" name="entry_date" value="' . $entry['entry_date'] . '" required>';
      echo '<label>Time:</label>';
      echo '<input type="time" name="entry_time" value="' . $entry['entry_time'] . '" required>';
      echo '<label>Mood:</label>';
      echo '<input type="text" name="mood" value="' . $entry['mood'] . '" required>';
      echo '<label>Content:</label>';
      echo '<textarea name="content" required>' . $entry['content'] . '</textarea>';
      echo '<input type="hidden" name="id" value="' . $entry['id'] . '">';
      echo '<button type="button" onclick="saveEntry()">Save</button>';
      echo '<button type="button" onclick="closePopup()">Close</button>';
      echo '</form>';
  } else {
      echo "<p>No entry found.</p>";
  }
  $stmt->close();
}
?>

