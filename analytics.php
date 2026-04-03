<?php
// Include the getData.php file to fetch data from the database
include('getData.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Diary Analytics</title>
    <link rel="stylesheet" href="css/analytics.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wordcloud2.js/1.0.6/wordcloud2.min.js"></script>
</head>
<body>
    <header class="app-header">
        <div class="header-container">
            <div class="back-button-container">
                <a href="home.php" class="back-button">
                    <span class="arrow">&#8592;</span> Home
                </a>
            </div>
            <div class="title-container">
                <h1>Smart Diary Analytics</h1>
                <p class="header-description">Gain insights and visualize your diary entries.</p>
            </div>
            <div class="logo-container">
                <img src="img/logo.png" alt="Smart Diary Logo" class="logo">
            </div>
        </div>
        <br>
        <nav class="navigation" id = "navigation">
            <ul>
                <li><a href="#mood-analysis">Mood Analysis</a></li>
                <li><a href="#word-cloud">Common Words</a></li>
                <li><a href="#entry-trends">Entry Trends</a></li>
                <li><a href="#tag-analysis">Tag Analysis</a></li>
                <li><a href="#sentiment-analysis">Sentiment Analysis</a></li>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        <section class="statistics-overview">
            <h2>Overview of Your Diary Entries</h2>
            <div class="stats-summary">
                <div class="stat-item">
                    <h3>Total Entries</h3>
                    <p id="entryCounter"><?php echo $totalEntries; ?></p>
                </div>
                <div class="stat-item">
                    <h3>Average Mood</h3>
                    <p id="averageMood"><?php echo $averageMood; ?></p>
                </div>
                <div class="stat-item">
                    <h3>Most Used Word</h3>
                    <p id="mostUsedWord"><?php echo $mostUsedWord; ?></p>
                </div>
            </div>
        </section>

        <div class="filters">
            <label for="date-range">Date Range:</label>
            <input type="date" id="start-date">
            <span>to</span>
            <input type="date" id="end-date">
            <button id="filter-button">Apply Filter</button>
        </div>

        <section class="analytics-section" id="mood-analysis">
            <h2>Mood Analysis</h2>
            <canvas id="moodChart" class="chart"></canvas>
        </section>

        <section class="analytics-section" id="word-cloud">
            <h2>Common Words</h2>
            <div id="wordCloud"></div>
        </section>

        <section class="analytics-section" id="entry-trends">
            <h2>Entry Frequency & Trends</h2>
            <canvas id="frequencyChart" class="chart"></canvas>
        </section>

        <section class="analytics-section" id="tag-analysis">
            <h2>Tag Analysis</h2>
            <canvas id="tagChart" class="chart"></canvas>
        </section>

        <section class="analytics-section" id="sentiment-analysis">
            <h2>Sentiment Analysis</h2>
            <canvas id="sentimentChart" class="chart"></canvas>
        </section>
    </main>

    <div class="button-container">
        <button id="export">Export Analytics Data</button>
        <button id="download-pdf">Download as PDF</button>
    </div>

    <footer class="app-footer">
        <div class="footer-content">
            <div class="clock">
                <div id="Date">Monday, 26 September 2023</div>
                <ul>
                    <li id="hours">05</li>
                    <li id="point">:</li>
                    <li id="min">20</li>
                    <li id="point">:</li>
                    <li id="sec">30</li>
                </ul>
            </div>
            <p>&copy; 2024 Smart Diary. All rights reserved.</p>
        </div>
    </footer>

    <a href="#navigation" id="go-to-top" title="Go to Top">&#8679;</a>

    <script>
    const moodData = <?php echo json_encode($moodData->fetch_all(MYSQLI_ASSOC)); ?>;
    const entryTrendsData = <?php echo json_encode($entryTrendsData); ?>;
    const tagCounts = <?php echo json_encode($tagCounts); ?>;
    const sentimentCounts = <?php echo json_encode($sentimentCounts); ?>;

    // Mood Chart
    const moodChart = new Chart(document.getElementById('moodChart'), {
        type: 'pie',
        data: {
            labels: moodData.map(item => item.mood),
            datasets: [{
                data: moodData.map(item => item.count),
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
            }]
        },
    });

    // Word Cloud
    const wordData = <?php echo json_encode($wordCounts); ?>;
    const wordArray = Object.entries(wordData).map(([word, count]) => [word, count]);

    WordCloud(document.getElementById('wordCloud'), {
        list: wordArray,
        gridSize: Math.round(16 * document.getElementById('wordCloud').clientWidth / 1024),
        weightFactor: 3,
        color: '#5A7184',
        backgroundColor: '#ECECEC'
    });

    // Entry Trends Chart
    const frequencyChart = new Chart(document.getElementById('frequencyChart'), {
        type: 'line',
        data: {
            labels: Object.keys(entryTrendsData),
            datasets: [{
                label: 'Entry Frequency',
                data: Object.values(entryTrendsData),
                borderColor: 'rgba(75,192,192,1)',
                fill: false,
            }]
        },
    });

    // Tag Analysis Chart
    const tagLabels = Object.keys(tagCounts);
    const tagValues = Object.values(tagCounts);

    const tagChart = new Chart(document.getElementById('tagChart'), {
        type: 'bar',
        data: {
            labels: tagLabels,
            datasets: [{
                label: 'Tag Frequency',
                data: tagValues,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }]
        },
    });

    // Sentiment Analysis Chart
    const sentimentLabels = Object.keys(sentimentCounts);
    const sentimentValues = Object.values(sentimentCounts);

    const sentimentChart = new Chart(document.getElementById('sentimentChart'), {
        type: 'doughnut',
        data: {
            labels: sentimentLabels,
            datasets: [{
                data: sentimentValues,
                backgroundColor: ['#FF9F40', '#FFCD56', '#4BC0C0'],
            }]
        },
    });




 // Function to update the clock in real-time
 function updateClock() {
    const now = new Date();
    
    const daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    const monthsOfYear = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    const day = daysOfWeek[now.getDay()];
    const date = now.getDate();
    const month = monthsOfYear[now.getMonth()];
    const year = now.getFullYear();
    
    let hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    // Determine AM/PM and convert to 12-hour format
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // The hour '0' should be '12'

    // Update the clock display
    document.getElementById("Date").textContent = `${day}, ${date} ${month} ${year}`;
    document.getElementById("hours").textContent = String(hours).padStart(2, '0');
    document.getElementById("min").textContent = minutes;
    document.getElementById("sec").textContent = seconds;
    document.getElementById("ampm").textContent = ampm;
  }

  // Call updateClock every second
  setInterval(updateClock, 1000);

  // Initial clock update when the page loads
  updateClock();

</script>
</body>
</html>
