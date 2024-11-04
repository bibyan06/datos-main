@extends('layouts.admin_layout')

@section('title', 'Administrative Orders' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/documents.css') }}">
@endsection

@section('main-id','memorandum-content')

@section('content') 
    <div class="documents-container">
        <div class="documents-title">
            <h1>Administrative Orders</h1>
        </div>
        <div class="left-content">
            <div class="documents-search-bar">
                <input type="text" class="search-text" placeholder="Search Document">
                <div class="icon"><i class="bi bi-search"></i></div>
            </div>
            <div class="documents-option">
                <div class="search">
                    <select class="option-text">
                        <option value="" disabled selected>Select Month</option>
                        <option value="doc1">January</option>
                        <option value="doc2">February</option>
                        <option value="doc3">April</option>
                        <option value="doc4">May</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="dashboard-section">
        <div class="dashboard-container">
            <div class="documents">
                @forelse($documents as $document)
                    <div class="document">
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
                                <p>Date Updated: {{ \Carbon\Carbon::parse($document->updated_at)->format('F d, Y') }}</p>
                                <p>{{ $document->description }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>No approved memorandums available at the moment.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset ('js/memorandum.js') }}"></script>
@endsection
