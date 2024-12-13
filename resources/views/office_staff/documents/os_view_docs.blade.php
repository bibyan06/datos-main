@extends('layouts.office_staff_layout')

@section('title', 'View Document' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/os/staff_view.css') }}">
@endsection

@section('main-id','view-secotion')

@section('content')
        <div class="documents-content">
            <div class="doc-container">
                <div class="view-documents">
                    <div class="doc-description">
                        <a  href="javascript:history.back()" class="back-icon" aria-label="Go back">
                            <i class="bi bi-arrow-return-left"></i>
                            <span class="tooltip">Go back</span>
                        </a>                        
                        <h5 class="file-title">Title:</h5>
                        <h1 class="document_name">{{ $document->document_name }}</h1>
                        <h3 class="issued_date">{{ \Carbon\Carbon::parse($document->upload_date)->format('F j, Y') }}</h3>
                        <div class="description">
                            <h5>Description:</h5>
                            <p>{{ $document->description }}</p>
                        </div>
                    </div>
                    <div class="viewing-btn">
                        <button class="edit-btn" onclick="location.href='{{ route('office_staff.documents.edit_docs', $document->document_id) }}'">Edit</button>
                        <button class="download-btn">
                            <a  style="color:#FEFEFF; text-decoration:none;" href="{{ route('document.serve', basename($document->file_path)) }}" download>Download</a>
                        </button>
                    </div>
                    <div class="doc-file">
                        <iframe src="{{ route('document.serve', basename($document->file_path)) }}#toolbar=0&zoom=126" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('custom-js')
    <script src="{{ asset('js/view_document.js') }}"></script>
@endsection

</body>
</html>