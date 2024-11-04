document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.querySelector('#category-filter');
    const tableRows = document.querySelectorAll('#documents-table tr');

    selectElement.addEventListener('change', function() {
        const selectedCategory = this.value;
        console.log('Selected Category:', selectedCategory); // Debugging line

        tableRows.forEach(row => {
            const category = row.getAttribute('data-category');
            console.log('Row Category:', category); // Debugging line

            if (selectedCategory === '' || category === selectedCategory) {
                row.style.display = ''; // Show row
            } else {
                row.style.display = 'none'; // Hide row
            }
        });
    });
});
