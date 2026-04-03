<?php
session_start(); // Start session

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    echo "<div class='popup-message $message_type'>$message</div>";
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}


// Include the database connection
include('dbc/dbh.php'); // Ensure the path is correct for your project structure



// Fetch recent diary entries for the current user (user_id is assumed to be stored in the session)
$user_id = $_SESSION['user_id']; // Assuming the user is logged in

// Prepare and execute the query to fetch the entries
$query = "SELECT * FROM diary_entries WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();



?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Smart Diary</title>
    <link rel="stylesheet" href="css/home.css" type="text/css">
    <link rel="stylesheet" href="edit.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" ref="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DynaPuff:wght@400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/18ed86d51a.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/4.0.0/apexcharts.min.js" integrity="sha512-f82EGNY/Wwa6E9g6tKFHoyiaytlgfd0g5gfaOJjSIF6k5T7vqmWrP83iXZdUZoc0DvO3kR4jRpmAZUBt5MhBbA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
     .logo-img {
      max-width: 90px;
     }
      
      .popup-message {
          position: fixed;
          top: 20px;
          right: 20px;
          padding: 10px 20px;
          border-radius: 5px;
          color: white;
          font-size: 16px;
          z-index: 1000;
          animation: fadeOut 5s forwards;
      }

      .popup-message.success {
          background-color: #4CAF50;
      }

      .popup-message.error {
          background-color: #F44336;
      }

      @keyframes fadeOut {
          0% { opacity: 1; }
          80% { opacity: 1; }
          100% { opacity: 0; }
      }

      /* footer section */
      
</style>
  
  
  
  </head>
  <body>


    <!-- Header Section -->
    <header class="header">
      <div class="container header-container">
        <!-- Logo -->
        <div class="logo">
          <img src="img/Logo.png" alt="Smart Diary Logo" class="logo-img" />
        </div>

        <!-- Navigation Menu -->
        <nav class="nav">
          <ul>
            <li>
              <a href="#welcome-section"><i class="fas fa-home"></i> Home</a>
            </li>
            <li>
              <a href="#recent-entries"><i class="fas fa-book"></i> Diary</a>
            </li>
            <li>
              <a href="#image-slider"><i class="fas fa-image"></i> Images</a>
            </li>
            <li>
              <a href="#quote-section"
                ><i class="fas fa-quote-left"></i> Quotes</a
              >
            </li>
          </ul>
        </nav>

        <!-- Logout Button -->
        <div class="logout-btn-container">
          <button class="logout-btn" onclick="logout()">
            <i class="fas fa-sign-out-alt"></i> Logout
          </button>
        </div>
      </div>
    </header>
        
        <div class="heading"> 
            <h3>We're
                <span style="--i:4;" data-text="Personal Organizer">Personal Organizer</span>
                <span style="--i:3;" data-text="Emotion Tracker">Emotion Tracker</span>
                <span style="--i:2;" data-text="Daily Updater">Daily Updater</span>
                <span style="--i:1;" data-text="Secret Diary">Secret Diary</span>
            </h3>
        </div>


    <!-- Section 01: Welcome and Features -->
    <section class="welcome-section" id="welcome-section">
      <div class="container">
        <h1 class="welcome-title">
          Welcome, <span id="username">Saubhagya</span>!
        </h1>
        <p class="intro-text">Explore all the amazing features:</p>
        <div class="features-container">

          <a href="entry.html" class="feature-item">
            <i class="fas fa-pencil-alt"></i>
            <h3>New Entry</h3>
            <p>Capture your thoughts, plans, and more easily.</p>
          </a>
          <a href="calendar.html" class="feature-item">
            <i class="fas fa-calendar-day"></i>
            <h3>Calendar</h3>
            <p>View and organize your schedule effortlessly.</p>
          </a>
          <a href="analytics.php" class="feature-item">
            <i class="fas fa-chart-line"></i>
            <h3>Analytics</h3>
            <p>Gain valuable insights into your mood and habits.</p>
          </a>
          <a href="reminder.html" class="feature-item">
            <i class="fas fa-bell"></i>
            <h3>Reminders</h3>
            <p>Stay on top of your tasks with smart notifications.</p>
          </a>
          <a href="setting.php" class="feature-item">
            <i class="fas fa-cogs"></i>
            <h3>Settings</h3>
            <p>Customize the app to your preferences and needs.</p>
          </a>
        </div>
      </div>
    </section>

  <!-- Section 02: Recent Diary Entries -->
<section class="recent-entries" id="recent-entries">
  <div class="container">
    <h2>Your Recent Entries</h2>
    <div class="entries-list">
      <?php while ($entry = $result->fetch_assoc()) { ?>
        <div class="entry-item" id="entry<?php echo $entry['id']; ?>">
          <h4><?php echo htmlspecialchars($entry['entry_date']); ?> - <?php echo htmlspecialchars($entry['entry_time']); ?></h4>
          <p><?php echo htmlspecialchars(substr(strip_tags($entry['content']), 0, 50)); ?>...</p>
          <button class="view-btn" onclick="openPopup(<?php echo $entry['id']; ?>)">View</button>
          <button class="edit-btn" onclick="openEditPopup(<?php echo $entry['id']; ?>)">Edit</button>

          <button class="delete-btn" onclick="deleteEntry(<?php echo $entry['id']; ?>)">Delete</button>
        </div>
      <?php } ?>
    </div>
    <br />
    <button class="see-all-btn" onclick="location.href='see_all.php';">See All</button>

  </div>
</section>

<!-- Popup for Viewing Diary Entry -->
<div id="entry-popup" class="popup" style="display:none;">
  <div class="popup-content">
    <button class="close-btn" onclick="closePopup()">&times;</button>
    <h3 id="popup-title">Diary Entry Title</h3>
    <p><strong>Date:</strong> <span id="popup-date">2024-11-18</span></p>
    <p><strong>Time:</strong> <span id="popup-time">10:00 AM</span></p>
    <p><strong>Mood:</strong> <span id="popup-mood">Happy</span></p>
    <p><strong>Sentiment:</strong> <span id="popup-sentiment">Positive</span></p>
    <p><strong>Tags:</strong> <span id="popup-tags">Life, Inspiration</span></p>
    <p><strong>Content:</strong></p>
    <p id="popup-content">This is the content of the diary entry...</p>
    <div id="popup-images"></div>
    <div id="popup-audio"></div>
    <button class="view-btn" onclick="closePopup()">Close</button>
  </div>
</div>



<!-- Popup for Editing Diary Entry -->
<div id="edit-popup" class="popup" style="display:none;">
  <div class="popup-content">
    <button class="close-btn" onclick="closeEditPopup()">&times;</button>
    <h3>Edit Diary Entry</h3>
    <form id="edit-entry-form">
      <input type="hidden" id="edit-entry-id">
      <label for="edit-date">Date:</label>
      <input type="date" id="edit-date" name="date"><br>

      <label for="edit-time">Time:</label>
      <input type="time" id="edit-time" name="time"><br>

      <label for="edit-mood">Mood:</label>
      <input type="text" id="edit-mood" name="mood"><br>

      <label for="edit-sentiment">Sentiment:</label>
      <input type="text" id="edit-sentiment" name="sentiment"><br>

      <label for="edit-tags">Tags:</label>
      <input type="text" id="edit-tags" name="tags"><br>

      <label for="edit-content">Content:</label>
      <textarea id="edit-content" name="content"></textarea><br>

      <label>Images:</label>
      <div id="edit-images"></div>
      <input type="file" id="new-image" name="new-image[]" multiple><br>

      <button type="button" onclick="saveEditEntry()">Save</button>
    </form>
  </div>
</div>


    <!-- Section 03: Diary Entry Images -->
    <section class="slider-section" id="image-slider">
      <div class="container">
        <h2>Your Visual Memories</h2>
        <br />
        <div class="slider-container">
          <div class="slider">
            <img src="img/image1.jpeg" alt="Image 1" />
            <img src="img/image2.jpg" alt="Image 2" />
            <img src="img/image3.jpg" alt="Image 3" />
            <img src="img/image4.jpg" alt="Image 4" />
            <img src="img/image5.jpg" alt="Image 5" />
            <img src="img/image6.jpg" alt="Image 6" />
            <img src="img/image7.jpg" alt="Image 7" />
            <img src="img/image8.jpg" alt="Image 8" />
          </div>
        </div>
      </div>
    </section>

    <!-- Section 04: Quote Section & Music Integration -->
    <section class="quote-section" id="quote-section">
    
      <div class="container">
       
        <!-- line chart-->
        <div class="features-container1">
                
        

      <!--Quotes-->
                <div class="wrapper-container">
                    <div class="quote-header">Quote of the Day</div>
                    <div class="wrapper-content">
                        <div class="quote-area">
                            <i class="fa-solid fa-quote-left"></i>
                            <p class="quote">The only way to do great work is to love what you do.</p>
                            <i class="fa-solid fa-quote-right"></i>
                        </div>
                        <div class="author">
                            <span>__</span>
                            <span class="name">Steve Jobs</span>
                        </div>
                    </div>
                    <div class="quote-button">
                        <div class="features">
                            <ul>
                                <li class="sound" role="button" aria-label="Play sound"><i class="fa-solid fa-volume-high"></i></li>
                                <li class="copy" role="button" aria-label="Copy quote"><i class="fa-regular fa-copy"></i></li>
                            </ul>
                            <button id="new_quote">New Quote</button>
                        </div>
                    </div>
                </div>

      <!--music-->
                <div class = "body" >
      <iframe 
        id="spotify-player"
        src="https://open.spotify.com/embed/track/3n3Ppam7vgaVa1iaRUc9Lp" 
        width="300" 
        height="380" 
        frameborder="0" 
        allowtransparency="true" 
        allow="encrypted-media">
    </iframe>

    <!-- Navigation Buttons -->
    <button id="back-btn">Back</button>
    <button id="next-btn">Next</button><br><br>
      </div>

      <!--weather-->
                <div class="weather-container">
                    <div class="search-box">
                        <i class="fa-solid fa-location-dot"></i>
                        <input type="text" placeholder="Enter City Name">
                        <button id="Search" class="fa-solid fa-magnifying-glass"></button>
                    </div>

                    <div class="weather-box">
                        <div class="box">
                            <div class="info-weather">
                                <div class="weather">
                                    <img src="img/clear.png" alt="cloud img">
                                    <p class="temperature">0<span> °C</span></p>
                                    <p class="description">How is Temperature</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="full-forecast">
                        <a class="weather-link" href="weather/weather.html" target="_blank">See Full Forecast</a>
                    </div>
                </div>
      </div>
    </section>

      

    <!-- Footer Section -->
    
<footer>
    <div class="footer-row">
        <div class="footer-col">
            <img src="img/logo.png" class="footer-logo" alt="Logo">
            <p>Smart diary is a periodical software which is suited for everyone: It can be anyone's 
                personal organizer, an emotion tracker, daily updater, secret diary, schedule 
                software, tasks manager. </p>
        </div>

        <div class="footer-col">
            <h2>Support <div class="underline"><span></span></div></h2><br>
            <ul>
                <li><a href="#">Privacy Policy</a></li><br>
                <li><a href="#">Terms Of Service</a></li><br>
                <li><a href="#">Help</a></li><br>
                <li><a href="#">FAQ</a></li><br>
            </ul>

            

        </div>

        <div class="footer-col">
            <h2>Contact Us <div class="underline"><span></span></div></h2><br><br>
            <h4><i class="ri-home-2-line" style="margin-right: 8px;"></i>Address:</h4>
                <p>IT Department,<br>Rajarata University of Sri Lanka.</p>
            <h4><i class="ri-mail-line" style="margin-right: 8px;"></i>E-mail:</h4>
                <p>infosmartdiary@gmail.com</p>
            <h4><i class="ri-phone-line" style="margin-right: 8px;"></i>Telephone:</h4>
                <p>+94 - 766242152</p>
        </div>

        <div class="footer-col">
            <h2>Fallow Us <div class="underline"><span></span></div></h2><br>
        <div class="social-icon">
            <i class='bx bxl-facebook'></i>
            <i class='bx bxl-messenger'></i>
            <i class='bx bxl-twitter'></i>
            <i class='bx bxl-google'></i>
            <i class='bx bxl-whatsapp'></i>
        </div>
        </div>
    </div>
    <hr>
    <p class="copyright">Smart Diary &copy; 2024 - All Rights Reserved</p>
    
</footer>


<script src="js/home.js"></script>
<script src = "edit.js"></script>
    <script>
        // Display popup message
        const popupMessage = document.getElementById('popupMessage');
        if (popupMessage) {
            popupMessage.style.display = 'block'; // Show the message

            // Hide the message after 3 seconds
            setTimeout(() => {
                popupMessage.style.display = 'none';
            }, 3000);
        }
    </script>

<script>
  // Fetch username from the server
  fetch('username_retrieval.php')
    .then(response => response.json())
    .then(data => {
      if (data.username) {
        // Update the username in all places
        document.querySelectorAll("#userName").forEach(el => {
          el.textContent = data.username;
        });
      } else {
        // Log an error message if username retrieval failed
        console.error("Error retrieving username:", data.error || "Unknown error");
      }
    })
    .catch(error => {
      console.error("Error:", error);
    });


// new js for php connection 

// Open the View Popup with the entry details
function openPopup(entryId) {
  fetch(`get_entry.php?id=${entryId}`)
    .then((response) => response.json())
    .then((data) => {
      // Fill popup with fetched details
      document.getElementById("popup-title").innerText = data.entry_date;
      document.getElementById("popup-date").innerText = data.entry_date;
      document.getElementById("popup-time").innerText = data.entry_time;
      document.getElementById("popup-mood").innerText = data.mood;
      document.getElementById("popup-sentiment").innerText = data.sentiment;
      document.getElementById("popup-tags").innerText = data.tags;
      document.getElementById("popup-content").innerText = data.content;

      // Handle images
      const imagesContainer = document.getElementById("popup-images");
      imagesContainer.innerHTML = ""; // Clear previous images
      data.images.forEach((image) => {
        const img = document.createElement("img");
        img.src = image;
        img.alt = "Diary Entry Image";
        img.style.maxWidth = "100%"; // Responsive styling
        img.style.marginBottom = "10px";
        imagesContainer.appendChild(img);
      });

      // Show the popup
      document.getElementById("entry-popup").style.display = "block";
    })
    .catch((error) => console.error("Error fetching entry:", error));
}

// Close the View Popup
function closePopup() {
  document.getElementById("entry-popup").style.display = "none";
}

// Open the Edit Popup with the entry details
function openEditPopup(entryId) {
  fetch(`get_entry.php?id=${entryId}`)
    .then((response) => response.json())
    .then((data) => {
      // Fill popup with fetched details
      document.getElementById("edit-entry-id").value = entryId;
      document.getElementById("edit-date").value = data.entry_date;
      document.getElementById("edit-time").value = data.entry_time;
      document.getElementById("edit-mood").value = data.mood;
      document.getElementById("edit-sentiment").value = data.sentiment;
      document.getElementById("edit-tags").value = data.tags;
      document.getElementById("edit-content").value = data.content.replace(/<p>|<\/p>/g, ""); // Remove <p> tags

      // Handle existing images
      const imagesContainer = document.getElementById("edit-images");
      imagesContainer.innerHTML = ""; // Clear previous images
      data.images.forEach((image, index) => {
        const imageContainer = document.createElement("div");
        imageContainer.style.marginBottom = "10px";

        const img = document.createElement("img");
        img.src = image;
        img.style.maxWidth = "100%";

        const removeBtn = document.createElement("button");
        removeBtn.innerText = "Remove";
        removeBtn.style.marginLeft = "10px";
        removeBtn.onclick = function () {
          imageContainer.remove();
        };

        imageContainer.appendChild(img);
        imageContainer.appendChild(removeBtn);
        imagesContainer.appendChild(imageContainer);
      });

      // Show the popup
      document.getElementById("edit-popup").style.display = "block";
    })
    .catch((error) => console.error("Error fetching entry:", error));
}

// Close the Edit Popup
function closeEditPopup() {
  document.getElementById("edit-popup").style.display = "none";
}

// Save the Edited Entry
function saveEditEntry() {
  const entryId = document.getElementById("edit-entry-id").value;
  const formData = new FormData(document.getElementById("edit-entry-form"));
  formData.append("id", entryId);

  fetch("save_entry.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Entry updated successfully!");
        closeEditPopup();
        location.reload(); // Reload page to reflect changes
      } else {
        alert("Error updating entry: " + data.message);
      }
    })
    .catch((error) => console.error("Error saving entry:", error));
}

// Delete entry
function deleteEntry(entryId) {
  if (confirm("Are you sure you want to delete this entry?")) {
    fetch(`delete_entry.php?id=${entryId}`, {
      method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Entry deleted successfully!');
        document.getElementById(`entry${entryId}`).remove();
      } else {
        alert('Failed to delete the entry');
      }
    })
    .catch(error => console.error('Error deleting entry:', error));
  }
}


</script>


  </body>
</html>
