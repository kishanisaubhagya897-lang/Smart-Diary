<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirect to login page if not logged in
    exit;
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Database connection details
$host = "localhost";
$dbname = "smart_diary";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch only the logged-in user's diary entries
    $stmt = $pdo->prepare("SELECT id, entry_date, mood, content FROM diary_entries WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Diary Entries</title>
    <style>

        /* Basic Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    padding-top: 60px;
}

header {
    background-color: #333;
    color: #fff;
    padding: 10px 20px;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header .logo {
    font-size: 24px;
}

header .back-home-btn {
    background-color: #008CBA;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    text-decoration: none;
}

header .back-home-btn:hover {
    background-color: #005f7f;
}

.main-content {
    margin-top: 80px;
    padding: 20px;
}

h1 {
    font-size: 32px;
    margin-bottom: 20px;
}

.entries-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.entry-card {
    background-color: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;  /* Prevent text from overflowing */
}

.entry-header h2 {
    font-size: 20px;
    margin-bottom: 10px;
}

.mood {
    font-size: 14px;
    color: #888;
}

.entry-snippet {
    font-size: 16px;
    color: #555;
    margin-bottom: 15px;
}

.entry-actions {
    display: flex;
    gap: 10px;
}

.entry-actions button {
    padding: 8px 16px;
    background-color: #008CBA;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.entry-actions button:hover {
    background-color: #005f7f;
}

.view-popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    justify-content: center;
    align-items: center;
    z-index: 1001;
}

.view-popup-content {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    width: 80%;
    max-width: 800px;
    position: relative;
}

.view-popup-content h2 {
    font-size: 24px;
    margin-bottom: 15px;
}

.close-popup-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    cursor: pointer;
}

    </style>
</head>
<body>
    <header class="header">
        <div class="logo">My Smart Diary</div>
        <a href="home.php" class="back-home-btn">Back to Home</a>
    </header>

    <main class="main-content">
        <h1>Your Diary Entries</h1>
        <div id="entries-container" class="entries-container">
            <?php if (count($entries) > 0): ?>
                <?php foreach ($entries as $entry): ?>
                    <div class="entry-card" id="entry-<?php echo $entry['id']; ?>">
                        <div class="entry-header">
                            <h2><?php echo htmlspecialchars($entry['entry_date']); ?></h2>
                            <span class="mood">Mood: <?php echo htmlspecialchars($entry['mood']); ?></span>
                        </div>
                        <p class="entry-snippet"><?php echo htmlspecialchars(explode('.', $entry['content'])[0]); ?>...</p>

                        <div class="entry-actions">
                        <button class="view-btn" onclick="openPopup(<?php echo $entry['id']; ?>)">View</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No entries found.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- View Entry Popup -->
    <div id="view-popup" class="view-popup">
        <div class="view-popup-content">
            <span class="close-popup-btn" onclick="closePopup()">&times;</span>
            <h2 id="view-title"></h2>
            <div id="view-content"></div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2024 My Smart Diary. All rights reserved.</p>
    </footer>

    <script >
        // Open the View Popup and display the full content
function viewEntry(entryId) {
    fetch(`get_entry.php?id=${entryId}`)
        .then(response => response.json())
        .then(data => {
            // Set the entry title and content in the popup
            document.getElementById('view-title').innerText = data.entry_date;
            document.getElementById('view-content').innerHTML = data.content.replace(/<\/?p>/g, ''); // Remove <p> tags
            document.getElementById('view-popup').style.display = 'flex'; // Show the popup
        });
}

// Close the View Popup
function closePopup() {
    document.getElementById('view-popup').style.display = 'none';
}

    </script>
</body>
</html>
