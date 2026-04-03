// Utility function to fetch data from PHP endpoints
async function fetchData(action) {
  const response = await fetch(`getData.php?action=${action}`);
  const data = await response.json();
  return data;
}

// Update Total Entries, Average Mood, and Most Used Word
async function updateOverview() {
  // Fetch entry trends and common words
  const entryTrends = await fetchData("entry_trends");
  const commonWords = await fetchData("common_words");

  // Update total entries
  const totalEntries = Object.values(entryTrends).reduce((a, b) => a + b, 0);
  document.getElementById("entryCounter").innerText = totalEntries;

  // Fetch mood analysis and calculate average mood
  const moodData = await fetchData("mood");
  const moods = Object.entries(moodData).sort((a, b) => b[1] - a[1]);
  document.getElementById("averageMood").innerText = moods.length
    ? moods[0][0]
    : "Neutral";

  // Update most used word
  const topWord = Object.keys(commonWords)[0];
  document.getElementById("mostUsedWord").innerText = topWord || "N/A";
}

// Update Mood Analysis Chart
async function updateMoodChart() {
  const moodData = await fetchData("mood");
  const labels = Object.keys(moodData);
  const values = Object.values(moodData);

  new Chart(document.getElementById("moodChart").getContext("2d"), {
    type: "pie",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Mood Analysis",
          data: values,
          backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0"],
        },
      ],
    },
  });
}

// Update Word Cloud
async function updateWordCloud() {
  const commonWords = await fetchData("common_words");
  const wordCloudContainer = document.getElementById("wordCloud");
  wordCloudContainer.innerHTML = "";

  Object.entries(commonWords).forEach(([word, count]) => {
    const span = document.createElement("span");
    span.textContent = `${word} (${count})`;
    span.style.fontSize = `${Math.min(14 + count * 2, 32)}px`; // Adjust size based on frequency
    wordCloudContainer.appendChild(span);
  });
}

// Update Entry Frequency Chart
async function updateFrequencyChart() {
  const entryTrends = await fetchData("entry_trends");
  const labels = Object.keys(entryTrends);
  const values = Object.values(entryTrends);

  new Chart(document.getElementById("frequencyChart").getContext("2d"), {
    type: "line",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Entries Per Day",
          data: values,
          borderColor: "#4BC0C0",
          fill: false,
        },
      ],
    },
  });
}

// Update Tag Analysis Chart
async function updateTagChart() {
  const tagData = await fetchData("tags");
  const labels = Object.keys(tagData);
  const values = Object.values(tagData);

  new Chart(document.getElementById("tagChart").getContext("2d"), {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Tag Analysis",
          data: values,
          backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0"],
        },
      ],
    },
  });
}

// Update Sentiment Analysis Chart
async function updateSentimentChart() {
  const sentimentData = await fetchData("sentiment");
  const labels = Object.keys(sentimentData);
  const values = Object.values(sentimentData);

  new Chart(document.getElementById("sentimentChart").getContext("2d"), {
    type: "doughnut",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Sentiment Analysis",
          data: values,
          backgroundColor: ["#4BC0C0", "#FF6384", "#FFCE56"],
        },
      ],
    },
  });
}

// Initialize All Charts and Data
function initAnalytics() {
  updateOverview();
  updateMoodChart();
  updateWordCloud();
  updateFrequencyChart();
  updateTagChart();
  updateSentimentChart();
}

document.addEventListener("DOMContentLoaded", initAnalytics);
