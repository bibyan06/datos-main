document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menu-toggle");
    const sidebar = document.getElementById("sidebar");
    const homeContent = document.getElementById("home-section");
    const navLinks = document.querySelector(".nav-left ul");

    menuToggle.addEventListener("click", function () {
        sidebar.classList.toggle("visible");
        homeContent.classList.toggle("shifted");
        navLinks.classList.toggle("hidden");
    });


    function updateTime() {
        const now = new Date();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const formattedHours = hours % 12 || 12; // Convert 24-hour time to 12-hour time
        const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;

        const timeString = `${formattedHours}:${formattedMinutes} ${ampm}`;
        const dateString = now.toLocaleDateString('en-US', { weekday: 'long', day: 'numeric', month: 'long' });

        document.querySelector('.current-time h3').textContent = timeString;
        document.querySelector('.current-time p').textContent = dateString;
    }

    updateTime();
    setInterval(updateTime, 60000); // Update the time every minute
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


document.addEventListener('DOMContentLoaded', (event) => {
    var dropdownButton = document.getElementById("dropdownMenuButton");
    var dropdownContent = document.getElementById("more-option");

    dropdownButton.onclick = function(event) {
        event.preventDefault();
        dropdownContent.classList.toggle("show");
    }

    window.onclick = function(event) {
        if (!event.target.matches('#dropdownMenuButton')) {
            var dropdowns = document.getElementsByClassName("dropdown-more");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
});