document.addEventListener("DOMContentLoaded", function () {
    const iconContainers = document.querySelectorAll(".icon-container");
    const extraSidebar = document.querySelector(".extra-sidebar");
    const dropdownToggle = document.querySelector(".dropdown-toggle");
    const dropdownMenu = document.querySelector(".more-dropdown-menu");
    const closeIcon = document.querySelector(".bi-text-right");

    // Dropdown toggle behavior
    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener("click", function () {
            dropdownMenu.classList.toggle("more-dropdown-active");
            this.querySelector("i.bi-chevron-right").classList.toggle("more-dropdown-active");
        });

        // Close dropdown menu when the cursor leaves
        dropdownMenu.addEventListener("mouseleave", function () {
            dropdownMenu.classList.remove("more-dropdown-active");
            dropdownToggle.querySelector("i.bi-chevron-right").classList.remove("more-dropdown-active");
        });
    }

    // Close the extra sidebar when the close icon is clicked
    if (closeIcon) {
        closeIcon.addEventListener("click", function () {
            extraSidebar.classList.remove("active");
            iconContainers.forEach((icon) => icon.classList.remove("active"));
        });
    }

    // Add click event to icons to toggle extra-sidebar and close dropdowns
    iconContainers.forEach((container) => {
        container.addEventListener("click", function () {
            extraSidebar.classList.toggle("active");

            // Hide dropdowns if they are open
            if (dropdownMenu && dropdownToggle) {
                dropdownMenu.classList.remove("more-dropdown-active");
                dropdownToggle.querySelector("i.bi-chevron-right").classList.remove("more-dropdown-active");
            }

            iconContainers.forEach((icon) => icon.classList.remove("active"));
            this.classList.add("active");
        });
    });
});

document.addEventListener('click', function(e) {
    const suggestionsContainer = document.getElementById('suggestions-container');
    const headerSearch = document.getElementById('header-search');
    
    // Check if suggestionsContainer and headerSearch exist
    if (suggestionsContainer && headerSearch) {
        if (!suggestionsContainer.contains(e.target) && e.target !== headerSearch) {
            suggestionsContainer.style.display = 'none'; // Hide suggestions when clicking outside
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('sidebar-search');
    const searchResultsContainer = document.createElement('div');
    searchResultsContainer.classList.add('search-results');
    document.querySelector('.search-container').appendChild(searchResultsContainer);

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            let query = searchInput.value.trim();

            if (query.length > 0) {
                fetch(`/search-documents?query=${query}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not okay');
                        }
                        return response.json();
                    })
                    .then(data => {
                        searchResultsContainer.innerHTML = '';  // Clear previous results
                        if (data.length > 0) {
                            data.forEach(doc => {
                                const resultItem = document.createElement('div');
                                resultItem.classList.add('search-result-item');
                                resultItem.innerHTML = `<a href="/documents/view/${doc.document_id}">${doc.document_name}</a>`;
                                searchResultsContainer.appendChild(resultItem);
                            });
                        } else {
                            // Display 'No results found' if no documents match the query
                            searchResultsContainer.innerHTML = '<p style="color: black;">No documents found.</p>';
                        }
                        searchResultsContainer.style.display = 'block'; // Show the results
                    })
                    .catch(error => {
                        console.error('Error fetching documents:', error);
                        searchResultsContainer.innerHTML = '<p>There was an error fetching the documents.</p>';
                    });
            } else {
                searchResultsContainer.innerHTML = '';  // Clear the results if the query is empty
                searchResultsContainer.style.display = 'none'; // Hide the results
            }
        });

        // Hide search results when clicking outside of the search container or the results
        document.addEventListener('click', function (event) {
            if (!searchInput.contains(event.target) && !searchResultsContainer.contains(event.target)) {
                searchResultsContainer.style.display = 'none'; // Hide the search results
            }
        });

        // Prevent hiding search results when clicking inside the search input or results container
        searchInput.addEventListener('click', function (event) {
            if (searchResultsContainer.innerHTML !== '') {
                searchResultsContainer.style.display = 'block'; // Show the search results if not empty
            }
        });
    }
});


document.addEventListener('DOMContentLoaded', function () {
    console.log("DOM fully loaded and parsed");

    // Initial fetch of the notification count
    fetchNotificationCount();

    // Set an interval to fetch the notification count every 5 minutes (300000 ms)
    setInterval(fetchNotificationCount, 300000);
});

function fetchNotificationCount() {
    let notificationCountElement = document.getElementById('notification-counts');

    fetch('/notification/count')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log(data)
            
            console.log('Notification Count Element:', notificationCountElement);

            if (notificationCountElement) {
                const notificationCount = data.notificationCount;
                console.log('Fetched Notification Count:', notificationCount);
                
                notificationCountElement.textContent = notificationCount;

                // Show or hide the notification count based on the value
                if (notificationCount > 0) {
                    notificationCountElement.style.display = 'inline';
                } else {
                    notificationCountElement.style.display = 'none';
                }
            } else {
                console.error('Notification count element not found');
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}


document.addEventListener("DOMContentLoaded", function () {
    const profileIcon = document.getElementById("profile-icon");
    const profileDropdown = document.getElementById("profile-dropdown");

    profileIcon.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevent event from bubbling up
        profileDropdown.style.display = profileDropdown.style.display === "block" ? "none" : "block";
    });

    // Hide the dropdown if clicking outside of it
    document.addEventListener("click", function (event) {
        if (!profileIcon.contains(event.target) && !profileDropdown.contains(event.target)) {
            profileDropdown.style.display = "none";
        }
    });
});