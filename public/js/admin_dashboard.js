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

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-text');
    const documentsTable = document.getElementById('documents-table');
    const rows = documentsTable.getElementsByTagName('tr');

    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.toLowerCase();
        
        Array.from(rows).forEach(row => {
            const documentName = row.cells[1]?.textContent.toLowerCase() || ''; // Get document name from the second column
            const documentNumber = row.cells[0]?.textContent.toLowerCase() || ''; // Get document number from the first column

            if (documentName.includes(searchTerm) || documentNumber.includes(searchTerm)) {
                row.style.display = ''; // Show the row if it matches
            } else {
                row.style.display = 'none'; // Hide the row if it doesn't match
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', () => {
    // Select all the edit icons
    const editIcons = document.querySelectorAll('.edit-icon');

    editIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            // Get the document ID and the document name, description, and remark cells
            const documentId = this.getAttribute('data-id');
            const documentNameCell = this.closest('tr').querySelector('[data-field="document_name"]');
            const descriptionCell = this.closest('tr').querySelector('[data-field="description"]');
            const remarkCell = this.closest('tr').querySelector('[data-field="remark"]');

            // If the icon is a pencil, start editing
            if (this.classList.contains('bi-pencil-square')) {
                documentNameCell.contentEditable = true;
                descriptionCell.contentEditable = true;
                remarkCell.contentEditable = true; // Make remark editable

                // Add 'editing' class to apply the blue border
                documentNameCell.classList.add('editing');
                descriptionCell.classList.add('editing');
                remarkCell.classList.add('editing'); // Add 'editing' class to remark

                // Change icon to checkmark (save)
                this.classList.remove('bi-pencil-square');
                this.classList.add('bi-check2-square');
            } else if (this.classList.contains('bi-check2-square')) {
                // Save the changes using an AJAX request
                const updatedData = {
                    document_id: documentId,
                    document_name: documentNameCell.innerText,  // Get updated document name
                    description: descriptionCell.innerText,     // Get updated description
                    remark: remarkCell.innerText               // Get updated remark
                };
                
                fetch(adminDashboardUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(updatedData),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Success alert with SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Changes saved successfully!',
                            text: 'The document details were updated.',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        // Failure alert with SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to save changes',
                            text: 'There was an issue saving the document details. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Error alert with SweetAlert2
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again later.',
                        confirmButtonText: 'OK'
                    });
                });

                // Disable editing after saving
                documentNameCell.contentEditable = false;
                descriptionCell.contentEditable = false;
                remarkCell.contentEditable = false; // Disable editing for remark

                // Remove the 'editing' class to remove the blue border
                documentNameCell.classList.remove('editing');
                descriptionCell.classList.remove('editing');
                remarkCell.classList.remove('editing'); // Remove 'editing' class from remark

                // Change icon back to pencil (edit)
                this.classList.remove('bi-check2-square');
                this.classList.add('bi-pencil-square');
            }
        });
    });
});


