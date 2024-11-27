@extends('layouts.admin_layout')

@section('title', 'Search Document' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/all_docs.css') }}">
@endsection

@section('main-id','memorandum-content')


@section('content')
    <main id="memorandum-content">
        <div class="memorandum-container">
            <div class="memorandum-title">
                <h1>ALL DIGITIZED DOCUMENTS</h1>
            </div>
            <div class="left-content">
                <div class="memorandum-search-bar">
                    <input type="text" class="search-text" id="search-document" placeholder="Search Document">
                    <div class="icon"><i class="bi bi-search"></i></div>
                </div>
                <div class="memorandum-option">
                    <div class="search">
                        <select id="category-filter">
                            <option value="" disabled selected>Select a Category</option>
                            <option value="all">All</option>
                            <option value="Memorandum">Memorandum</option>
                            <option value="Audited Disbursement Voucher">Audited Disbursement Voucher</option>
                            <option value="Monthly Report Service of Personnel">Monthly Report Service of Personnel</option>
                            <option value="Claim Monitoring Sheet">Claim Monitoring Sheet</option>
                        </select>
                    </div>
                </div>
                <div class="month-option">
                    <div class="search">
                        <select id="option-text" class="option-text">
                            <option value="" selected>Select Month</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div id="dashboard-section">
            <div class="dashboard-container">
                <div class="documents" id="documents-list">
                    @forelse($documents as $document)
                        <div class="document" data-name="{{ $document->document_name }}" data-category="{{ $document->category }}">
                            <div class="file-container">
                                <div class="document-card">
                                <iframe src="{{ route('document.serve', basename($document->file_path)) }}#toolbar=0" width="100%" frameborder="0"></iframe>
                                </div>                        
                            </div>
                            <div class="document-description">
                                <div class="row">
                                    <div class="column-left">
                                        <h3>{{ $document->document_name }}</h3>
                                    </div>
                                    <input type="text" hidden
                                    value="{{ \Carbon\Carbon::parse($document->updated_date)->format('F') }}">

                                    <div class="column-right">
                                        <a href="#" class="dropdown-toggle"><i class="bi bi-three-dots-vertical"></i></a>
                                        <div class="dropdown-more">
                                            <a href="{{ route('admin.documents.view_docs', $document->document_id) }}" class="view-btn">View</a>
                                            <a href="{{ route('document.serve', basename($document->file_path)) }}" download>Download</a>
                                            <a href="{{ route('admin.documents.edit_docs', $document->document_id) }}">Edit</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="other-details">
                                    <p>Date Upload: {{ \Carbon\Carbon::parse($document->upload_date)->format('F d, Y') }}</p>
                                    <p>{{ $document->description }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No document available at the moment.</p>
                    @endforelse
                    <p id="hidden">No document available at the moment.</p>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('custom-js')
    <script src="{{ asset('js/all_docs.js') }}"></script>
    <script>
       document.addEventListener('DOMContentLoaded', function () {
    const hidden = document.querySelector('#hidden');
    const searchText = document.querySelector('#search-document');
    const documents = document.querySelectorAll('#documents-list .document');
    const month = document.querySelector('#option-text'); // Month filter
    const categoryFilter = document.querySelector('#category-filter'); // Category filter

    hidden.style.display = "none";

    function filterByCategory(category) {
        let anyDocumentVisible = false;

        documents.forEach(doc => {
            const docCategory = doc.getAttribute('data-category')?.toLowerCase().trim() || '';
            const matchesCategory = category === 'all' || docCategory === category;

            doc.style.display = matchesCategory ? '' : 'none';

            if (matchesCategory) anyDocumentVisible = true;
        });

        hidden.style.display = anyDocumentVisible ? 'none' : 'block';
    }

    function filterDocuments() {
        const query = searchText?.value.toLowerCase() || '';
        const selectedMonth = month?.value.toLowerCase() || '';
        const selectedCategory = categoryFilter?.value.toLowerCase().trim() || '';

        let anyDocumentVisible = false;

        documents.forEach(doc => {
            const name = doc.getAttribute('data-name')?.toLowerCase() || '';
            const docMonth = doc.querySelector('input[type="text"]')?.value.toLowerCase() || '';
            const docCategory = doc.getAttribute('data-category')?.toLowerCase().trim() || '';

            const matchesSearch = !query || name.includes(query);
            const matchesMonth = !selectedMonth || docMonth === selectedMonth;
            const matchesCategory = selectedCategory === 'all' || docCategory === selectedCategory;

            const shouldDisplay = matchesSearch && matchesMonth && matchesCategory;
            doc.style.display = shouldDisplay ? '' : 'none';

            if (shouldDisplay) anyDocumentVisible = true;
        });

        hidden.style.display = anyDocumentVisible ? 'none' : 'block';
    }

    // Add event listeners for filters
    searchText?.addEventListener('input', filterDocuments);
    month?.addEventListener('change', filterDocuments);

    // Filter by category specifically when the category dropdown changes
    categoryFilter?.addEventListener('change', function () {
        const selectedCategory = categoryFilter.value.toLowerCase().trim() || '';
        filterByCategory(selectedCategory);
    });
});

    </script>
@endsection

