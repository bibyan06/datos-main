@extends('layouts.admin_layout')

@section('title', 'View Document' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/view-document.css') }}">
@endsection

@section('main-id','view-section')

@section('content') 
        <div class="documents-content">
            <div class="doc-container">
                <div class="view-documents">
                    <div class="doc-description">
                        <a href="{{ route('home.admin') }}" class="back-icon">
                            <i class="bi bi-arrow-return-left"></i>
                            <span class="tooltip">Go back</span>
                        </a>                        
                        <h5 class="file-title">Title:</h5>
                        <h1 class="document_name">{{ $document->title }}</h1>
                        <h3 class="issued_date">{{ $document->issued_date }}</h3>
                        <div class="description">
                            <h5>Description:</h5>
                            <p>{{ $document->description }}</p>
                        </div>
                    </div>
                    <div class="viewing-btn">
                        <button class="edit-btn" onclick="location.href='admin_edit.html'">Edit</button>
                        <button class="download-btn" onclick="downloadDocument()">Download</button>
                    </div>
                    <div class="doc-file">
                        <iframe src="{{ asset($document->file_path) }}#toolbar=0&zoom=126" width="100%" height="600px"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('custom-js')
    <script src="{{ asset ('js/view_document.js')}}"></script>
@endsection

</body>
</html>