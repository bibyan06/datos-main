@extends('layouts.office_staff_layout')

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
                            <option value="" disabled>Select Document</option>
                            <option value="" {{ request('category') === '' ? 'selected' : '' }}>All</option>
                            <option value="Memorandum" {{ request('category') === 'Memorandum' ? 'selected' : '' }}>Memorandum</option>
                            <option value="Audited Disbursement Voucher" {{ request('category') === 'Audited Disbursement Voucher' ? 'selected' : '' }}>Audited Disbursement Voucher</option>
                            <option value="Monthly Report Service of Personnel" {{ request('category') === 'Monthly Report Service of Personnel' ? 'selected' : '' }}>Monthly Report Service of Personnel</option>
                            <option value="Claim Monitoring Sheet" {{ request('category') === 'Claim Monitoring Sheet' ? 'selected' : '' }}>Claim Monitoring Sheet</option>
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
                        <div class="document" data-name="{{ $document->document_name }}">
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
                                            <a href="{{ route('office_staff.documents.os_view_docs', $document->document_id) }}" class="view-btn">View</a>
                                            <a href="{{ route('document.serve', basename($document->file_path)) }}" download>Download</a>
                                            <a href="{{ route('office_staff.documents.edit_docs', $document->document_id) }}">Edit</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="other-details">
                                    <p>Date Updated: {{ \Carbon\Carbon::parse($document->updated_at)->format('F d, Y') }}</p>
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
        document.addEventListener('DOMContentLoaded', function() {
        const hidden = document.querySelector('#hidden');
        const searchText = document.querySelector('#search-document'); // Updated selector
        const documents = document.querySelectorAll('#documents-list .document');
        const month = document.querySelector('#option-text'); // Correct ID for month select element
        
        hidden.style.display = "none";

        function filterDocuments() {
            const query = searchText.value.toLowerCase();
            const selectedMonth = month.value.toLowerCase();

            documents.forEach(doc => {
                const name = doc.getAttribute('data-name').toLowerCase();
                const docMonth = doc.querySelector('input[type="text"]').value.toLowerCase();
                const matchesSearch = name.includes(query);
                const matchesMonth = docMonth === selectedMonth;

                // Show document only if it matches both the search text and selected month
                if (query && !month.value) {
                    hidden.style.display = matchesSearch ? 'none' : 'block';
                    doc.style.display = matchesSearch ? '' : 'none';
                } else if (!query && month.value) {
                    hidden.style.display = matchesMonth ? 'none' : 'block';
                    doc.style.display = matchesMonth ? '' : 'none';
                } else if (query && month.value) {
                    doc.style.display = matchesSearch && matchesMonth ? '' : 'none';
                } else {
                    hidden.style.display = "block";
                    doc.style.display = ''; // Show all if no filter is applied
                }
            });

            hidden.style.display = documents.length && Array.from(documents).every(doc => doc.style.display === 'none') ? 'block' : 'none';
            }

            searchText.addEventListener('input', filterDocuments);
            month.addEventListener('change', filterDocuments);
        });
    </script>

@endsection

</body>
</html>