document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('view-section');

    menuToggle.addEventListener('click', function () {
        sidebar.classList.toggle('visible');
        mainContent.classList.toggle('shifted');
    });
});


document.addEventListener('DOMContentLoaded', () => {
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownMenu = document.querySelector('.dropdown');
    const dropdownIcon = document.getElementById('dropdown-icon');

    dropdownToggle.addEventListener('click', (e) => {
        e.preventDefault();
        const isDropdownVisible = dropdownMenu.style.display === 'block';

        // Toggle the dropdown menu
        dropdownMenu.style.display = isDropdownVisible ? 'none' : 'block';

        // Change the icon
        dropdownIcon.className = isDropdownVisible ? 'bi bi-caret-right-fill' : 'bi bi-caret-down-fill';
    });

    document.addEventListener('click', (e) => {
        if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.style.display = 'none';
            dropdownIcon.className = 'bi bi-caret-right-fill';
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const profileIcon = document.getElementById('profile-icon');
    const profileDropdown = document.getElementById('profile-dropdown');

    profileIcon.addEventListener('click', function (e) {
        e.stopPropagation(); // Prevent click from bubbling up
        profileDropdown.style.display = profileDropdown.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', function () {
        profileDropdown.style.display = 'none'; // Hide dropdown when clicking outside
    });
});


// public/js/view_document.js

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', function() {
            const documentId = this.getAttribute('data-document-id');

            fetch(`/document/${documentId}`)
                .then(response => response.json())
                .then(data => {
                    // Update the view with the document data
                    document.querySelector('#document-details').innerHTML = `
                        <div class="doc-description">
                            <a href="officer.html" class="back-icon">
                                <i class="bi bi-arrow-return-left"></i>
                                <span class="tooltip">Go back</span>
                            </a>
                            <h5 class="file-title">Title:</h5>
                            <h1 class="document_name">${data.title}</h1>
                            <h3 class="issued_date">${data.issued_date}</h3>
                            <div class="description">
                                <h5>Description:</h5>
                                <p>${data.description}</p>
                            </div>
                        </div>
                        <div class="viewing-btn">
                            <button class="edit-btn" onclick="location.href='admin_edit.html'">Edit</button>
                            <button class="download-btn" onclick="downloadDocument()">Download</button>
                        </div>
                        <div class="doc-file">
                            <iframe src="${data.file_path}#toolbar=0&zoom=126" width="100%" height="600px"></iframe>
                        </div>
                    `;
                });
        });
    });
});




