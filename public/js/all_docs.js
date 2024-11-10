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

document.addEventListener('DOMContentLoaded', function () {
    // URL for searching documents
    const searchRoute = "{{ route('admin.admin_search') }}";
    const documentsContainer = document.getElementById('documents-container');

    // Search document by name
    document.getElementById('search-document').addEventListener('input', function () {
        fetchDocuments({ query: this.value });
    });

    // Filter by category
    document.getElementById('category-filter').addEventListener('change', function () {
        fetchDocuments({ category: this.value });
    });

    // Filter by month
    document.getElementById('option-text').addEventListener('change', function () {
        fetchDocuments({ month: this.value });
    });

    // Fetch documents based on filters
    function fetchDocuments(filters) {
        const queryString = new URLSearchParams(filters).toString();
        fetch(`${searchRoute}?${queryString}`)
            .then(response => response.json())
            .then(data => renderDocuments(data))
            .catch(error => console.error('Error fetching documents:', error));
    }

    // Render documents in the DOM
    function renderDocuments(data) {
        documentsContainer.innerHTML = ''; // Clear previous documents
        if (data.length) {
            data.forEach(document => {
                const documentElement = documentTemplate(document);
                documentsContainer.appendChild(documentElement);
            });
        } else {
            documentsContainer.innerHTML = '<p>No documents found.</p>';
        }
    }

    // Template for each document
    function documentTemplate(document) {
        const element = document.createElement('div');
        element.className = 'document';
        element.innerHTML = `
            <div class="file-container">
                <div class="document-card">
                    <iframe src="{{ route('document.serve', '') }}/${document.file_path}#toolbar=0" width="100%" frameborder="0"></iframe>
                </div>
            </div>
            <div class="document-description">
                <div class="row">
                    <div class="column-left">
                        <h3>${document.document_name}</h3>
                    </div>
                    <div class="column-right">
                        <a href="#" class="dropdown-toggle"><i class="bi bi-three-dots-vertical"></i></a>
                        <div class="dropdown-more">
                            <a href="{{ route('admin.documents.view_docs', '') }}/${document.document_id}" class="view-btn">View</a>
                            <a href="{{ route('document.serve', '') }}/${document.file_path}" download>Download</a>
                            <a href="{{ route('admin.documents.edit_docs', '') }}/${document.document_id}">Edit</a>
                        </div>
                    </div>
                </div>
                <div class="other-details">
                    <p>Date Updated: ${new Date(document.upload_date).toLocaleDateString()}</p>
                    <p>${document.description}</p>
                </div>
            </div>
        `;
        return element;
    }
});



