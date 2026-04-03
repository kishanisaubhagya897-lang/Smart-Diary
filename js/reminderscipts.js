let notificationCount = 0;

function setReminder() {
    const reminderText = document.getElementById("reminderText").value;
    const reminderMessage = document.getElementById("reminderMessage").value;
    const reminderTime = new Date(document.getElementById("reminderTime").value);
    const reminderFrequency = document.getElementById("reminderFrequency").value;

    if (reminderText && reminderTime) {
        scheduleNotification(reminderText, reminderMessage, reminderTime, reminderFrequency);
        showDialog(`Reminder set for ${reminderText} at ${reminderTime.toLocaleString()} with frequency ${reminderFrequency}.`);
        
        document.getElementById("reminderText").value = "";
        document.getElementById("reminderMessage").value = "";
        document.getElementById("reminderTime").value = "";
        document.getElementById("reminderFrequency").value = "once";
    } else {
        alert("Please enter both reminder event and time.");
    }
}

function showDialog(message) {
    const modal = document.getElementById("successModal");
    document.getElementById("modalMessage").innerText = message;
    modal.style.display = "flex";
}

function closeModal() {
    const modal = document.getElementById("successModal");
    modal.style.display = "none";
}

function scheduleNotification(text, message, time, frequency) {
    const now = new Date().getTime();
    const delay = time.getTime() - now;

    if (delay > 0) {
        setTimeout(() => {
            displayTriggeredNotification(text, message, time);
            playNotificationSound();
        }, delay);
    }

    if (frequency !== "once") {
        const interval = calculateInterval(frequency);
        setInterval(() => {
            displayTriggeredNotification(text, message, new Date());
            playNotificationSound();
        }, interval);
    }
}

function calculateInterval(frequency) {
    switch (frequency) {
        case "daily":
            return 24 * 60 * 60 * 1000;
        case "weekly":
            return 7 * 24 * 60 * 60 * 1000;
        case "monthly":
            return 30 * 24 * 60 * 60 * 1000;
        default:
            return 0;
    }
}

function displayTriggeredNotification(text, message, time) {
    const triggeredNotificationList = document.getElementById("triggeredNotificationList");
    const listItem = document.createElement("li");
    listItem.className = "notification-item";

    // Alternating background colors
    listItem.style.backgroundColor = notificationCount % 2 === 0 ? "#D1E8E2" : "#FFD8B8";

    const formattedTime = time.toLocaleString();

    listItem.innerHTML = `
        <strong>${text}</strong><br><small>${message}</small><br>
        <span class="notification-time">${formattedTime}</span>
        <button class="close-btn" onclick="removeNotification(this)">✖️</button>
    `;

    triggeredNotificationList.appendChild(listItem);
    notificationCount++;
    document.getElementById("notificationBadge").innerText = notificationCount;
}

function removeNotification(button) {
    const notificationItem = button.parentElement;
    notificationItem.remove();
    notificationCount--;
    document.getElementById("notificationBadge").innerText = notificationCount;

    if (notificationCount === 0) {
    document.getElementById("notificationBadge").innerText = ""; // Clear badge if no notifications
}
}

function playNotificationSound() {
    const notificationSound = document.getElementById("notificationSound");
    notificationSound.volume = 0.5;
    notificationSound.play();
}

function goBack() {
    window.history.back();
}