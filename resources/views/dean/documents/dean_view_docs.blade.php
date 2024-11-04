@extends('layouts.dean_layout')

@section('title', 'View Document' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/dean/view_docs.css') }}">
@endsection

@section('main-id','view-section')

@section('content')
        <div class="documents-content">
        <div class="doc-container">
            <div class="view-documents">
                <div class="doc-description">
                    <a href="dean_page.html" class="back-icon">
                        <i class="bi bi-arrow-return-left"></i>
                        <span class="tooltip">Go back</span>
                    </a>                        
                    <h5 class="file-title">Title:</h5>
                    <h1 class="document_name">Administrative Order No. 331 Series of 2023</h1>
                    <h3 class="issued_date">June 2, 2023</h3>
                    <div class="description">
                        <h5>Description:</h5>
                        <p>In view of the University's continued quest for quality management system and to ensure the highest level of efficiency and effectiveness in the performance of office transactions at the office of the University President, you are hereby designated as Senior Staff at the Presidential Management Staff Office and University Documents and Records Controller on concurrent capacity effective 02 May 2023 until revoked by a subsequent issuance from this Office in accordance with the existing Civil Service rules and regulations.</p>
                    </div>
                </div>
                <div class="viewing-btn">
                    <button class="edit-btn" onclick="location.href='dean_edit.html'">Edit</button>
                    <button class="download-btn" onclick="downloadDocument()">Download</button>
                </div>
                <div class="doc-file">
                    <iframe src="digitized_documents/CERTIFICATION.pdf#toolbar=0&zoom=126" width="100%" height="600px" ></iframe>
                </div>
            </div>
        </div>
        </div>
@endsection

@section('custom-js')
    <script src="{{ asset ('js/dean/view_docs.js') }}"></script>
@endsection

</body>
</html>