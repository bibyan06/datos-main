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
