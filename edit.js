function editEntry(entryId) {
    const popup = document.getElementById('popup');
    const popupBody = document.getElementById('popup-body');

    // Fetch the entry for editing via AJAX
    fetch(`edit_entry.php?id=${entryId}`)
        .then(response => response.text())
        .then(data => {
            popupBody.innerHTML = data; // Load the edit form into the popup
            popup.style.display = 'block'; // Show the popup
        })
        .catch(error => console.error('Error:', error));
}

function saveEntry() {
    const form = document.getElementById('edit-form');
    const formData = new FormData(form);

    // Save the updated entry via AJAX
    fetch('save_entry.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            alert(data); // Notify the user of the update status
            closePopup(); // Close the popup
            location.reload(); // Reload the page to see updated entry
        })
        .catch(error => console.error('Error:', error));
}

function closePopup() {
    const popup = document.getElementById('popup');
    popup.style.display = 'none'; // Hide the popup
}