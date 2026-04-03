// Logout Function
function logout() {
  alert("You have logged out successfully!");
  window.location.href = "login.html"; // Redirect to the login page
}

// Fetch username from the server
fetch("username_retrieval.php")
  .then((response) => response.json())
  .then((data) => {
    if (data.username) {
      document.getElementById("userName").textContent = data.username;
    } else {
      console.error(
        "Error retrieving username:",
        data.error || "Unknown error"
      );
    }
  })
  .catch((error) => console.error("Error:", error));

// Function to open the popup with the correct content
function openPopup(entryId) {
  // Get data related to the entry (This can be dynamic from a database)
  const entryData = {
    entry1: {
      title: "First Entry Title",
      date: "2024-11-18",
      time: "10:00 AM",
      mood: "Happy",
      sentiment: "Positive",
      tags: "Life, Inspiration",
      content: "This is the content of the first diary entry.",
      images: ["image1.jpg", "image2.jpg"],
      audio: "audio1.mp3",
    },
    entry2: {
      title: "Second Entry Title",
      date: "2024-11-19",
      time: "2:00 PM",
      mood: "Sad",
      sentiment: "Negative",
      tags: "Emotions, Struggles",
      content: "This is the content of the second diary entry.",
      images: ["image3.jpg"],
      audio: null,
    },
    entry3: {
      title: "Third Entry Title",
      date: "2024-11-20",
      time: "5:00 PM",
      mood: "Excited",
      sentiment: "Neutral",
      tags: "Achievements",
      content: "This is the content of the third diary entry.",
      images: [],
      audio: "audio2.mp3",
    },
  };

  const entry = entryData[entryId];

  // Populate the popup with entry data
  document.getElementById("popup-title").textContent = entry.title;
  document.getElementById("popup-date").textContent = entry.date;
  document.getElementById("popup-time").textContent = entry.time;
  document.getElementById("popup-mood").textContent = entry.mood;
  document.getElementById("popup-sentiment").textContent = entry.sentiment;
  document.getElementById("popup-tags").textContent = entry.tags;
  document.getElementById("popup-content").textContent = entry.content;

  // Handle images
  const imagesContainer = document.getElementById("popup-images");
  imagesContainer.innerHTML = "";
  entry.images.forEach((img) => {
    const imageElement = document.createElement("img");
    imageElement.src = img;
    imageElement.alt = "Attached Image";
    imagesContainer.appendChild(imageElement);
  });

  // Handle audio
  const audioContainer = document.getElementById("popup-audio");
  audioContainer.innerHTML = "";
  if (entry.audio) {
    const audioElement = document.createElement("audio");
    audioElement.controls = true;
    audioElement.src = entry.audio;
    audioContainer.appendChild(audioElement);
  }

  // Show the popup
  document.getElementById("entry-popup").style.display = "flex";
}

// Function to close the popup
function closePopup() {
  document.getElementById("entry-popup").style.display = "none";
}

// Function for editing an entry (Placeholder)
function editEntry(entryId) {
  alert(`Editing ${entryId}`);
  // You can implement actual editing logic here
}

// Function for deleting an entry (Placeholder)
function deleteEntry(entryId) {
  alert(`Deleting ${entryId}`);
  // You can implement actual deletion logic here
}



document.addEventListener("DOMContentLoaded", () => {
  // Load notification count from localStorage on Home Page load
  const homeNotificationBadge = document.getElementById("homeNotificationBadge");
  const notificationCount = parseInt(localStorage.getItem("notificationCount")) || 0;
  homeNotificationBadge.innerText = notificationCount > 0 ? notificationCount : "";

  // Add click functionality to redirect to the Reminder Page
  const homeBellIcon = document.getElementById("homeBellIcon");
  homeBellIcon.addEventListener("click", () => {
      window.location.href = "reminder.html"; // Redirect to Reminder Page
  });
});




document.addEventListener("DOMContentLoaded", () => {
    const homeNotificationBadge = document.getElementById("homeNotificationBadge");
    const notificationCount = parseInt(localStorage.getItem("notificationCount")) || 0;

    homeNotificationBadge.innerText = notificationCount > 0 ? notificationCount : "";

    // Redirect to Reminder Page on Bell Icon Click
    const homeBellIcon = document.getElementById("homeBellIcon");
    homeBellIcon.addEventListener("click", () => {
        window.location.href = "reminder.html"; // Redirect to Reminder Page
    });
});


//line chart
const colorPrimary = getComputedStyle(document.documentElement)
    .getPropertyValue("--hover-color")
    .trim();

const colorLabel = getComputedStyle(document.documentElement)
    .getPropertyValue("--color-label")
    .trim();

const fontFamily = getComputedStyle(document.documentElement)
    .getPropertyValue("--font-family")
    .trim();


const defaultOptions = {
    chart: {
        toolbar: {
            show: false
        },
        zoom: {
            enabled: false
        },
        width: '100%',
        height: 180,
        offsetY: 18
    },
    dataLabels: {
        enabled: false
    }
}

let barOptions = {
    ...defaultOptions,
    chart: {
        ...defaultOptions.chart,
        type: "area"
    },

    tooltip: {
        enabled: true,
        style: {
            fontFamily: fontFamily
        },
        y: {
            formatter: value => `${value}%`
        }
    },

    series: [
        {
            name: "Mood", // Named y-axis as Mood
            data: [15, 50, 18, 90, 30, 65, 40, 40] //Add data to y-axis
        }
    ],

        colors: [colorPrimary],
        fill: {
            type: "gradient",
            gradient: {
                type: "vertical",
                opacityFrom: 1,
                opacityTo: 0,
                stops: [0, 100],
                colorStops: [
                    {
                        offset: 0,
                        opacity: .2,
                        color: "#ffffff"
                    },
                    {
                        offset: 100,
                        opacity: 0,
                        color: "#ffffff"
                    },
                ]
            }       
    },

    stroke: {colors: [colorPrimary], lineCap: "round"},

    grid: {
        borderColor: "rgba(0, 0, 0, 0)",
        padding: {
            top: -30,
            right: 0,
            bottom: -8,
            left: 12
        }
    },

    markers: {
        strokeColors: colorPrimary
    },

    yaxis: {
        show: false
    },

    xaxis: {
        labels: {
            show: true,
            floating: true,
            style: {
                colors: colorLabel,
                fontFamily: fontFamily
            }
        },

        axisBorder: {
            show: false
        },

        crosshairs: {
            show:false
        },

        categories: ["Mon", "Tues", "Wed", "Thurs", "Fri", "Sat", "Sun"] //add values to x-axis
    }
};

let chart = new ApexCharts(
    document.querySelector(".area-chart"), barOptions
);

chart.render();


//quote of the Day

const quoteText = document.querySelector(".quote"),
authorName = document.querySelector(".author .name"),
quoteBtn =  document.querySelector("#new_quote"),
soundBtn =  document.querySelector(".sound"),
copyBtn =  document.querySelector(".copy");


//create function to get random quotes
function randomQuote(){
    quoteBtn.classList.add("loading");
    quoteBtn.innerText = "Loading Quote...";
    
    //fetching random quotes from weather API
    fetch("http://api.quotable.io/random").then(res => res.json()).then(result => {
        
        
        quoteText.innerText = result.content;
        authorName.innerText = result.author;
        quoteBtn.innerText = "New Quote";
        quoteBtn.classList.remove("loading");
    });
    
}

soundBtn.addEventListener("click", () =>{
    //web speech API use to represent a speech request
    let utterance = new SpeechSynthesisUtterance(`${quoteText.innerText} by ${authorName.innerText}`);
    speechSynthesis.speak(utterance); //This is speak method that speechSynthesis use to speaks the utterance
});

copyBtn.addEventListener("click", () =>{
    //copying the quote on copyBtn click and then writeText() writes the copied text
    navigator.clipboard.writeText(quoteText.innerText);
    alert("Quote copied to the clipboard");
});


quoteBtn.addEventListener("click", randomQuote);


//weather forecast

const container = document.querySelector('.weather-container');
const search = document.querySelector('#Search');
const weatherBox = document.querySelector('.weather-box');

search.addEventListener('click', () => {
    const api_key = 'd80881ac1741c9f32e4e581795bdebbd';
    const city = document.querySelector('.search-box input').value;

    if(city == '')
        return;

    fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${api_key}`).then(response => response.json()).then(json => {

            const image = document.querySelector('.weather-box img');
            const temperature = document.querySelector('.weather-box .temperature');
            const description = document.querySelector('.weather-box .description');

            switch (json.weather[0].main) {
                case 'Clear':
                    image.src = 'img/clear.png';
                    break;

                case 'Rain':
                    image.src = 'img/rain.webp';
                    break;

                case 'Snow':
                    image.src = 'img/snow.png';
                    break;

                case 'Clouds':
                    image.src = 'img/cloudy.png';
                    break;

                case 'Mist':
                    image.src = 'img/mist.png';
                    break;

                case 'Haze':
                    image.src = 'img/mist.png';
                    break;

                    default:
                        image.src = 'img/clear.png';
            }

            temperature.innerHTML = `${parseInt(json.main.temp)}<span>°C</span>`;
            description.innerHTML = `${json.weather[0].description}`;
        });
});

// Array of Spotify track URLs
const tracks = [
  "https://open.spotify.com/embed/track/3n3Ppam7vgaVa1iaRUc9Lp", // First Track
  "https://open.spotify.com/embed/track/7GhIk7Il098yCjg4BQjzvb", // Second Track
  "https://open.spotify.com/embed/track/4uLU6hMCjMI75M1A2tKUQC"  // Third Track
];

let currentTrackIndex = 0;

// Reference to the iframe
const spotifyPlayer = document.getElementById("spotify-player");

// Event listeners for buttons
document.getElementById("back-btn").addEventListener("click", () => {
  if (currentTrackIndex > 0) {
      currentTrackIndex--;
      spotifyPlayer.src = tracks[currentTrackIndex];
  }
});

document.getElementById("next-btn").addEventListener("click", () => {
  if (currentTrackIndex < tracks.length - 1) {
      currentTrackIndex++;
      spotifyPlayer.src = tracks[currentTrackIndex];
  }
});