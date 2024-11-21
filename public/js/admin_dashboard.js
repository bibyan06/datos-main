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


document.addEventListener('DOMContentLoaded', function () {
    const statusFilter = document.getElementById('status-filter');
    const categoryFilter = document.getElementById('category-filter');
    const documentsTable = document.getElementById('documents-table');

    function filterDocuments() {
        const selectedStatus = statusFilter.value.toLowerCase();
        const selectedCategory = categoryFilter.value.toLowerCase();

        Array.from(documentsTable.rows).forEach(row => {
            const documentStatus = row.getAttribute('data-status').toLowerCase();
            const documentCategory = row.getAttribute('data-category').toLowerCase();

            const matchesStatus = selectedStatus === 'all' || documentStatus === selectedStatus;
            const matchesCategory = selectedCategory === 'all' || documentCategory === selectedCategory;

            row.style.display = matchesStatus && matchesCategory ? '' : 'none';
        });
    }

    // Add event listeners to both filters
    statusFilter.addEventListener('change', filterDocuments);
    categoryFilter.addEventListener('change', filterDocuments);
});