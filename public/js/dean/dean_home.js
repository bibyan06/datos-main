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
        window.location.href = "{{ route ('dean.documents.dean_search') }}";
    });

    // document.getElementById("upload-shortcut").addEventListener("click", function() {
    //     window.location.href = "dean_upload.html";
    // });
});