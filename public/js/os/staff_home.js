document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.getElementById("menu-toggle");
    const sidebar = document.getElementById("sidebar");
    const homeContent = document.getElementById("home-section");
    const navLinks = document.querySelector(".nav-left ul");
    const dateTimeElement = document.getElementById("current-date-time");
    const footer = document.querySelector('footer');

    if (menuToggle && sidebar && homeContent && navLinks && footer) {
        menuToggle.addEventListener("click", () => {
            const isSidebarVisible = sidebar.classList.toggle("visible");
            homeContent.classList.toggle("shifted");
            navLinks.classList.toggle("hidden");

            if (isSidebarVisible) {
                footer.classList.add("sidebar-visible");
                footer.classList.remove("full-width");
            } else {
                footer.classList.remove("sidebar-visible");
                footer.classList.add("full-width");
            }
        });
    }

    function updateTime() {
        const now = new Date();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const seconds = now.getSeconds();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const formattedHours = hours % 12 || 12;
        const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
        const formattedSeconds = seconds < 10 ? '0' + seconds : seconds;

        const timeString = `${formattedHours}:${formattedMinutes}:${formattedSeconds} ${ampm}`;
        const dateString = now.toLocaleDateString('en-US', { weekday: 'long', day: 'numeric', month: 'numeric', year: 'numeric' });

        if (dateTimeElement) {
            dateTimeElement.textContent = `${dateString} ${timeString}`;
        }
    }

    updateTime();
    setInterval(updateTime, 1000);

    // Dropdown toggle functionality
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', (event) => {
            event.preventDefault();
            const dropdown = toggle.nextElementSibling;
            if (dropdown) {
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            }
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (event) => {
        if (!event.target.matches('.dropdown-toggle, .dropdown-toggle *')) {
            document.querySelectorAll('.dropdown-more').forEach(dropdown => {
                dropdown.style.display = 'none';
            });
        }
    });
});

/*Shortcuts js*/

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("documents-shortcut").addEventListener("click", function() {
        window.location.href = "staff_all_documents.html";
    });

    document.getElementById("upload-shortcut").addEventListener("click", function() {
        window.location.href = "staff_upload.html";
    });
});

// Forward document 

document.addEventListener('DOMContentLoaded', function () {
    // Event listener for forward button
    document.querySelectorAll('.forward-btn').forEach(button => {
        button.addEventListener('click', function () {
            const documentId = this.dataset.documentId;

            Swal.fire({
                title: 'Forward Document',
                html: `
                    <input type="text" id="searchEmployee" class="swal2-input" placeholder="Search Employee" 
                           style="margin-left: 20px; margin-right: 20px; width: calc(100% - 40px);">
                    <div id="message-box" style="color: red; font-size: 14px; margin-left: 20px; margin-right: 20px;"></div>
                    <textarea id="forwardMessage" class="swal2-textarea" placeholder="Add a message (optional)" 
                              style="margin-left: 20px; margin-right: 20px; width: calc(100% - 40px); margin-top: 10px;"></textarea>
                    <div id="employee-list" style="max-height: 200px; overflow-y: auto; margin-top: 15px; margin-left: 20px; margin-right: 20px;">
                        <!-- Employee list will be populated here -->
                    </div>
                `,
                showConfirmButton: false,  
                cancelButtonText: 'Cancel',
                showCancelButton: true,
                customClass: {
                    cancelButton: 'custom-cancel-button'  // Assigning a custom class to the Cancel button
                },
                didOpen: () => {
                    fetchEmployees(documentId);  // Fetch employee list on modal open
                    document.getElementById('searchEmployee').addEventListener('input', function () {
                        searchEmployee(this.value); // Filter employee list as the user types
                    });
                }
            });
        });
    });
});

// Function to fetch employees from the server (excluding current user)
function fetchEmployees(documentId) {
    // Make an AJAX request to get the list of employees excluding the current user
    axios.get('/employees/exclude-current').then(response => {
        const employees = response.data;
        const employeeList = document.getElementById('employee-list');
        const messageBox = document.getElementById('message-box');

        if (employees.length === 0) {
            messageBox.textContent = 'No employees available to forward the document.';
            employeeList.innerHTML = ''; // Clear the list if no employees are found
        } else {
            messageBox.textContent = '';  // Clear the message box if employees are found

            // Clear existing content and append new employees
            employeeList.innerHTML = employees.map(employee => `
                <div class="employee-item" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="text-align: left;">${employee.first_name} ${employee.last_name}</span>
                    <button class="send-btn" data-employee-id="${employee.id}" data-document-id="${documentId}">Send</button>
                </div>
            `).join('');

            // Attach event listeners to each send button
            document.querySelectorAll('.send-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const forwardMessage = document.getElementById('forwardMessage').value;
                    forwardDocument(button.dataset.documentId, button.dataset.employeeId, forwardMessage);
                });
            });
        }
    }).catch(error => {
        console.error('Error fetching employees:', error);
    });
}

// Function to filter employees based on search input
function searchEmployee(query) {
    const employees = document.querySelectorAll('.employee-item');
    const messageBox = document.getElementById('message-box');
    let filteredCount = 0;

    employees.forEach(employee => {
        const employeeName = employee.querySelector('span').textContent.toLowerCase();
        const isVisible = employeeName.includes(query.toLowerCase());
        employee.style.display = isVisible ? '' : 'none';
        if (isVisible) filteredCount++;
    });

    // Show a message if no employees match the search query
    if (filteredCount === 0) {
        messageBox.textContent = 'No matching employees found.';
    } else {
        messageBox.textContent = '';  // Clear the message if employees are found
    }
}

// Function to handle forwarding the document
function forwardDocument(documentId, employeeId, forwardMessage) {
    axios.post('/documents/forward', {
        document_id: documentId,
        employee_id: employeeId,
        message: forwardMessage
    }).then(response => {
        Swal.fire({
            title: 'Success',
            text: 'Document has been forwarded.',
            icon: 'success'
        });
    }).catch(error => {
        Swal.fire({
            title: 'Error',
            text: 'Failed to forward document.',
            icon: 'error'
        });
        console.error('Error forwarding document:', error);
    });
}