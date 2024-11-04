document.addEventListener('DOMContentLoaded', function () {
    // Initialize inputs to be editable
    enableEditing();

    // Load saved data if any
    loadSavedData();
});

function enableEditing() {
    document.querySelector('.document_name').disabled = false;
    document.querySelector('.issued_date').disabled = false;
    document.querySelector('.description').disabled = false;
}

function cancelEditing() {
    // Redirect to view-document.html
    window.location.href = 'staff_view.html';
}

function saveChanges() {
    // Get the current values
    const documentName = document.querySelector('.document_name').value;
    const issuedDate = document.querySelector('.issued_date').value;
    const description = document.querySelector('.description').value;

    // Save the values to localStorage
    localStorage.setItem('documentName', documentName);
    localStorage.setItem('issuedDate', issuedDate);
    localStorage.setItem('description', description);

    // Disable inputs after saving
    document.querySelector('.document_name').disabled = true;
    document.querySelector('.issued_date').disabled = true;
    document.querySelector('.description').disabled = true;

    // Show popup message
    showPopupMessage("Edited successfully", function() {
        // Redirect to view-document.html after showing the popup
        window.location.href = 'staff_view.html';
    });
}

function loadSavedData() {
    // Load the saved values from localStorage
    const documentName = localStorage.getItem('documentName');
    const issuedDate = localStorage.getItem('issuedDate');
    const description = localStorage.getItem('description');

    // Set the values to the fields if available
    if (documentName) {
        document.querySelector('.document_name').value = documentName;
    }
    if (issuedDate) {
        document.querySelector('.issued_date').value = issuedDate;
    }
    if (description) {
        document.querySelector('.description').value = description;
    }
}

function showPopupMessage(message, callback) {
    const popup = document.createElement('div');
    popup.className = 'popup-message';
    popup.innerText = message;
    document.body.appendChild(popup);
    popup.classList.add('show');

    setTimeout(() => {
        popup.classList.remove('show');
        document.body.removeChild(popup);
        if (callback) callback();
    }, 1000); // Hide after 1 second
}

function showBackPopup() {
    const backPopup = document.getElementById('back-popup');
    backPopup.style.display = 'block';
    backPopup.classList.add('show');
}

function hideBackPopup() {
    const backPopup = document.getElementById('back-popup');
    backPopup.style.display = 'none';
    backPopup.classList.remove('show');
}

function confirmBack() {
    window.location.href = 'staff_view.html';
}