<?php
session_start(); // Start the session to store messages
include('dbc/dbentry.php'); // Include the database connection

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if user is not logged in
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract form data from the POST request
    $date = $_POST['entry_date'];
    $time = $_POST['entry_time'];
    $mood = $_POST['mood'];
    $sentiment = $_POST['sentiment'];
    $tags = $_POST['tags'];
    $content = $_POST['diary_content']; // Content from the Quill editor
    $audio_name = null; // Initialize audio file name to null

    // Check if an audio file was uploaded
    if (isset($_FILES['audio_recording']) && $_FILES['audio_recording']['error'] == 0) {
        $audio = $_FILES['audio_recording'];
        $audio_name = 'audio_' . time() . '.mp3';
        $audio_tmp = $audio['tmp_name'];

        // Ensure the uploads directory exists
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }

        // Move the uploaded audio file
        if (!move_uploaded_file($audio_tmp, "uploads/" . $audio_name)) {
            $_SESSION['message'] = "Error in saving the audio file.";
            $_SESSION['message_type'] = "error";
            header("Location: home.php");
            exit;
        }
    }

    // SQL query for inserting the diary entry
    $sql = "INSERT INTO diary_entries (user_id, entry_date, entry_time, mood, sentiment, tags, content, audio_file) 
            VALUES (:user_id, :entry_date, :entry_time, :mood, :sentiment, :tags, :content, :audio_file)";
    $stmt = $conn->prepare($sql);

    // Bind values
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':entry_date', $date, PDO::PARAM_STR);
    $stmt->bindValue(':entry_time', $time, PDO::PARAM_STR);
    $stmt->bindValue(':mood', $mood, PDO::PARAM_STR);
    $stmt->bindValue(':sentiment', $sentiment, PDO::PARAM_STR);
    $stmt->bindValue(':tags', $tags, PDO::PARAM_STR);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':audio_file', $audio_name, PDO::PARAM_STR);

    try {
        if ($stmt->execute()) {
            $diary_entry_id = $conn->lastInsertId();

            // Handle image uploads
            if (isset($_FILES['images']) && $_FILES['images']['error'][0] == 0) {
                $images = $_FILES['images'];
                $image_folder = 'uploads/images/';
                if (!file_exists($image_folder)) {
                    mkdir($image_folder, 0777, true);
                }

                foreach ($images['tmp_name'] as $index => $tmp_name) {
                    $image_name = 'image_' . time() . '_' . $index . '.jpg';
                    $image_tmp = $tmp_name;

                    if (move_uploaded_file($image_tmp, $image_folder . $image_name)) {
                        $image_sql = "INSERT INTO diary_images (diary_entry_id, image_path) 
                                      VALUES (:diary_entry_id, :image_path)";
                        $image_stmt = $conn->prepare($image_sql);
                        $image_stmt->bindValue(':diary_entry_id', $diary_entry_id, PDO::PARAM_INT);
                        $image_stmt->bindValue(':image_path', $image_folder . $image_name, PDO::PARAM_STR);
                        $image_stmt->execute();
                    }
                }
            }

            // Success message
            $_SESSION['message'] = "Diary entry saved successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            // Failure message
            $_SESSION['message'] = "Failed to save diary entry.";
            $_SESSION['message_type'] = "error";
        }

        // Redirect to home
        header("Location: home.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
        header("Location: home.php");
        exit;
    }
}
?>
