@extends('layouts.dean_layout')

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
                    <input type="text" class="search-text" placeholder="Search Document">
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
            </div>
        </div>

        <div class="dashboard-container" id="documents-container">
            @forelse($documents as $document)
                <div class="document">
                    <div class="file-container">
                        <div class="document-card">
                        <iframe src="{{ route('document.serve', basename($document->file_path)) }}#toolbar=0" width="100%" frameborder="0"></iframe>                    </div>
                    </div>
                    <div class="document-description">
                        <div class="row">
                            <div class="column-left">
                                <h3>{{ $document->document_name }}</h3>
                            </div>
                            <div class="column-right">
                                <a href="#" class="dropdown-toggle"><i class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-more">
                                    <a href="{{ route('dean.documents.dean_view_docs', $document->document_id) }}" class="view-btn">View</a>
                                    <a href="{{ route('document.serve', basename($document->file_path)) }}" download>Download</a>
                                </div>
                            </div>
                        </div>
                        <div class="other-details">
                            <p>Date Uploaded: {{ \Carbon\Carbon::parse($document->upload_date)->format('F j, Y') }}</p>
                            <p>{{ $document->description }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p>No approved documents found.</p>
            @endforelse
        </div>
    </main>   
@endsection
 

@section('custom-js')
    <script src="{{ asset ('js/dean/search.js') }}"></script>
@endsection

</body>
</html>