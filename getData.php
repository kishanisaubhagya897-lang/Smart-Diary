<?php
// Include the database connection file
include('dbc/dba.php');

// Assume user_id is stored in a session
session_start();
$user_id = $_SESSION['user_id'];

// Fetch data for statistics overview
$totalEntries = $conn->query("SELECT COUNT(*) AS total FROM diary_entries WHERE user_id = $user_id")->fetch_assoc()['total'];

$moodData = $conn->query("SELECT mood, COUNT(mood) AS count FROM diary_entries WHERE user_id = $user_id GROUP BY mood ORDER BY count DESC");
$averageMood = $moodData->fetch_assoc()['mood'] ?? 'Neutral';

// Fetch words from content for word cloud generation
$wordData = $conn->query("SELECT content FROM diary_entries WHERE user_id = $user_id");
$wordCounts = [];
while ($row = $wordData->fetch_assoc()) {
    $words = explode(' ', $row['content']);
    foreach ($words as $word) {
        $word = strtolower(trim($word, ".,!?\"'"));
        if (!empty($word)) {
            $wordCounts[$word] = ($wordCounts[$word] ?? 0) + 1;
        }
    }
}
arsort($wordCounts);
$mostUsedWord = key($wordCounts) ?? 'N/A';

// Fetch data for entry trends
$entryTrends = $conn->query("SELECT DATE(entry_date) AS date, COUNT(*) AS count FROM diary_entries WHERE user_id = $user_id GROUP BY DATE(entry_date)");
$entryTrendsData = [];
while ($row = $entryTrends->fetch_assoc()) {
    $entryTrendsData[$row['date']] = $row['count'];
}

// Fetch and count tags for tag analysis
$tags = $conn->query("SELECT tags FROM diary_entries WHERE user_id = $user_id");
$tagCounts = [];
while ($row = $tags->fetch_assoc()) {
    $tagList = explode(',', $row['tags']);
    foreach ($tagList as $tag) {
        $tag = trim($tag);
        if (!empty($tag)) {
            $tagCounts[$tag] = ($tagCounts[$tag] ?? 0) + 1;
        }
    }
}

// Fetch data for sentiment analysis
$sentimentData = $conn->query("SELECT sentiment, COUNT(sentiment) AS count FROM diary_entries WHERE user_id = $user_id GROUP BY sentiment");
$sentimentCounts = [];
while ($row = $sentimentData->fetch_assoc()) {
    $sentimentCounts[$row['sentiment']] = $row['count'];
}

$conn->close();
?>
