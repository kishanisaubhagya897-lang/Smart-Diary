// Initialize Quill editor
var quill = new Quill("#editor", {
  theme: "snow",
  placeholder: "Write your diary entry...",
});

// Function to handle audio recording
let audioData = "";
let mediaRecorder;
let audioChunks = [];

document.getElementById("record-btn").addEventListener("click", async () => {
  if (!mediaRecorder) {
    // Get access to the microphone
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    mediaRecorder = new MediaRecorder(stream);

    mediaRecorder.ondataavailable = (event) => {
      audioChunks.push(event.data);
    };

    mediaRecorder.onstop = () => {
      const audioBlob = new Blob(audioChunks, { type: "audio/wav" });
      audioData = URL.createObjectURL(audioBlob);
      document.getElementById("audio-preview").src = audioData;
      document.getElementById("audio-preview").style.display = "block";
      document.getElementById("delete-audio-btn").style.display =
        "inline-block";
    };

    mediaRecorder.start();
    document.getElementById("record-btn").textContent = "Stop Recording";
  } else {
    mediaRecorder.stop();
    document.getElementById("record-btn").textContent = "Start Recording";
  }
});

document.getElementById("delete-audio-btn").addEventListener("click", () => {
  audioData = "";
  document.getElementById("audio-preview").style.display = "none";
  document.getElementById("delete-audio-btn").style.display = "none";
});

// Function to save diary entry
function saveEntry() {
  const entryDate = document.getElementById("entry-date").value;
  const entryTime = document.getElementById("entry-time").value;
  const sentiment = document.getElementById("sentiment").value;
  const tags = document.getElementById("entry-tags").value;
  const mood = document.querySelector(".mood-icon.active")
    ? document.querySelector(".mood-icon.active").dataset.mood
    : "";

  const content = quill.root.innerHTML; // Get the content from Quill editor

  // Form data to be sent to PHP
  const formData = new FormData();
  formData.append("entry_date", entryDate);
  formData.append("entry_time", entryTime);
  formData.append("sentiment", sentiment);
  formData.append("tags", tags);
  formData.append("content", content);
  formData.append("mood", mood);

  // If audio data is available, include it in the formData
  if (audioData) {
    const audioBase64 = audioData.replace("data:audio/wav;base64,", "");
    formData.append("audio_data", audioBase64);
  }

  // Send the form data to PHP
  fetch("entry.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      alert(data.message);
      if (data.status === "success") {
        // Clear the form after saving
        document.getElementById("entry-date").value = "";
        document.getElementById("entry-time").value = "";
        quill.root.innerHTML = ""; // Clear Quill editor
        audioData = "";
        document.getElementById("audio-preview").style.display = "none";
        document.getElementById("delete-audio-btn").style.display = "none";
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Failed to save entry.");
    });
}

// Handle mood selection
document.querySelectorAll(".mood-icon").forEach((icon) => {
  icon.addEventListener("click", () => {
    document
      .querySelectorAll(".mood-icon")
      .forEach((i) => i.classList.remove("active"));
    icon.classList.add("active");
  });
});
