@extends('layouts.office_staff_layout')

@section('title', 'Edit Document' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/os/staff_edit.css') }}">
@endsection

@section('main-id','view-section')

@section('content')
        <div class="documents-content">
            <div class="doc-container">
                <div class="view-documents">

                 <!-- Display success message if present -->
                 @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('office_staff.documents.update', $document->document_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                        <div class="doc-description">
                            <a href="#" class="back-icon" onclick="confirmBack(event)">
                                <i class="bi bi-arrow-return-left"></i>
                            </a>                        
                            <h5 class="file-title">Title:</h5>
                                <input type="text" name="document_name" class="document_name form-control" value="{{ old('document_name', $document->document_name) }}" required>
                            <h5>Date Uploaded:</h5>
                                <h3 class="issued_date">{{ \Carbon\Carbon::parse($document->upload_date)->format('F j, Y') }}</h3>
                            <div class="description">
                                <h5>Description:</h5>
                                <textarea name="description" class="description form-control" rows="5" required>{{ old('description', $document->description) }}</textarea>
                            </div>
                        </div>
                        <div class="viewing-btn">
                            <button type="button" class="save-btn">Save Changes</button>
                            <button href="{{ route('office_staff.documents.os_view_docs', $document->document_id) }}" class="cancel-btn" style="background-color: red;"  onclick="confirmBack(event)">Cancel</button>
                        </div>
                        <div class="doc-file">
                             <iframe src="{{ route('document.serve', basename($document->file_path)) }}#toolbar=0&zoom=126" frameborder="0"></iframe>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('custom-js')
    <script src="{{ asset('js/edit_document.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmBack(event) {
            event.preventDefault(); // Prevent default anchor behavior

            Swal.fire({
                title: 'Are you sure?',
                text: "You will lose any unsaved changes!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('office_staff.documents.os_view_docs', $document->document_id) }}";
                }
            });
        }

        document.querySelector('.save-btn').addEventListener('click', function(event) {
            event.preventDefault(); 

            Swal.fire({
                title: 'Save changes?',
                text: "Are you sure you want to save these changes?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your changes have been saved.',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        event.target.closest('form').submit();
                    });
                }
            });
        });
    </script>
@endsection
   
</body>
</html>