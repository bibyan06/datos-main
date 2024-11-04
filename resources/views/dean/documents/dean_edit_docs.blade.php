@extends('layouts.dean_layout')

@section('title', 'Edit Document' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/dean/edit_docs.css') }}">
@endsection

@section('main-id','view-section')

@section('content')      
    <div class="documents-content">
         <div class="doc-container">
             <div class="view-documents">
                <div class="doc-description">
                    <a href="#" class="back-icon" onclick="showBackPopup()">
                        <i class="bi bi-arrow-return-left"></i>
                    </a>                        
                    <h5 class="file-title">Title:</h5>
                        <input type="text" class="document_name" value="Administrative Order No. 331 Series of 2023">
                    <h5 class="issued_date">Issued Date:</h5>
                        <input type="text" class="issued_date" value="June 2, 2023">
                    <div class="description">
                    <h5>Description:</h5>
                        <textarea class="description">
                        In view of the University's continued quest for quality management 
                        system and to ensure the highest level of efficiency and effectiveness
                        in the performance of office transactions at the office of the University President, 
                        you are hereby designated as Senior Staff at the Presidential Management Staff Office and 
                        University Documents and Records Controller on concurrent capacity effective 02 May 2023 until
                         revoked by a subsequent issuance from this Office in accordance with the existing Civil Service rules and regulations.
                        </textarea>
                    </div>
                </div>
                <div class="viewing-btn">
                    <button class="cancel-btn" onclick="cancelEditing()">Cancel</button>
                    <button class="save-btn" onclick="saveChanges()">Save Changes</button>
                </div>
                <div class="doc-file">
                    <iframe src="digitized_documents/CERTIFICATION.pdf#toolbar=0&zoom=125" width="100%" height="600px"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
<script src="{{ asset('js/dean/dean_edit.js') }}"></script>
@endsection

</body>
</html>