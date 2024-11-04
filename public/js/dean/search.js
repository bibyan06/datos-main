document.addEventListener("DOMContentLoaded", () => {



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