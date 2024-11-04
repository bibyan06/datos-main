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

document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.querySelector('#category-filter');
    const tableRows = document.querySelectorAll('#documents-table tr');

    selectElement.addEventListener('change', function() {
        const selectedCategory = this.value;

        tableRows.forEach(row => {
            const category = row.getAttribute('data-category');

            if (!selectedCategory || category === selectedCategory) {
                row.style.display = ''; // Show row
            } else {
                row.style.display = 'none'; // Hide row
            }
        });
    });
});
