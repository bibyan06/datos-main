@extends('layouts.admin_layout')

@section('title', 'View Documents' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/view_document.css') }}">
@endsection

@section('main-id','view-section')

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
                        @if ($document->status==NULL)
                        <button class="edit-btn" onclick="location.href='{{ route('admin.documents.edit_docs', $document->document_id) }}'">Edit</button>

                        @endif
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
@endsection

@section('custom-js')
    <script src="{{ asset('js/view_document.js') }}"></script>
@endsection

</body>
</html>