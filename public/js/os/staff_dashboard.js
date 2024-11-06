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

document.getElementById('status-filter').addEventListener('change', filterDocuments);
        document.getElementById('category-filter').addEventListener('change', filterDocuments);

        function filterDocuments() {
            const status = document.getElementById('status-filter').value.toLowerCase();
            const category = document.getElementById('category-filter').value.toLowerCase();
            
            document.querySelectorAll('#documents-table tr').forEach(row => {
                const rowStatus = row.getAttribute('data-status').toLowerCase();
                const rowCategory = row.getAttribute('data-category').toLowerCase();

                const matchesStatus = status === 'all' || rowStatus === status;
                const matchesCategory = category === 'all' || rowCategory === category;

                row.style.display = matchesStatus && matchesCategory ? '' : 'none';
            });
        }