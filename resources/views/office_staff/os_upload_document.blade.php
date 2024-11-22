@extends('layouts.office_staff_layout')

@section('title', 'Upload Document' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/os/staff_upload.css') }}">
@endsection

@section('main-id','dashboard-content')

@section('content')
        <section class="title">
            <div class="title-content">
                <h3>Upload Document</h3>
            </div>
        </section>
            <div class="dashboard-container">
            <div class="upload-section">
                <div class="upload-box">
                <div class="upload-area" id="upload-area" 
                    ondrop="handleDrop(event)" 
                    ondragover="handleDragOver(event)" 
                    ondragleave="handleDragLeave(event)">
                    <i class="bi bi-cloud-arrow-up-fill upload-icon"></i>
                    <p>Select File or Drag and drop files to upload</p>
                    <input type="file" id="file-input" name="file" hidden accept=".pdf" required>
                    <button class="select-files" onclick="document.getElementById('file-input').click()">Select File</button>
                    <p>*Supported format: PDF</p>
                    <h6>Maximum file size: 5MB</h6>
                </div>
                    <div class="uploaded-files">
                        <h4>File Selected</h4>
                        <ul id="file-list"></ul>
                    </div>
                </div>

                <div class="form-section">
                     <!-- Display error messages here -->
                     @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form id="uploadDocumentForm" action="{{route('admin.admin_upload_document')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="document-number" id="dNumber">Document Number</label>
                            <div style="display: flex; align-items: center; width: 100%;">
                                <input type="text" id="document-number" name="document_number" class="form-control" value="{{$documentsCount}}" disabled>
                                <span id="edit-document-number-btn" style="margin-left: 10px; cursor: pointer;" data-bs-toggle="tooltip" title="Edit">
                                    <i class="bi bi-pencil-square icon-size"></i>
                                </span>
                            </div>
                            <small style="color: red; font-weight: bold" id="documentNumberError"></small>
                        </div>
                        <div class="form-group">
                            <label for="document-name" id="dName">Document Name</label>
                            <input type="text" id="document-name" name="document_name" class="form-control">
                            <small style="color: red; font-weight: bold" id="nameText"></small>
                            
                        </div>
                        <div class="form-group">
                            <label for="description" id="dText">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                            <small style="color: red; font-weight: bold" id="descText"></small>
                        </div>
                        <div class="form-group">
                            <label for="category_name">Category</label>
                            <select name="category_name" id="category_name" class="form-control">
                                @foreach($categories as $category)
                                <!-- <option value="">Select Category</option> -->
                                    <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            <!-- @error('category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror -->
                        </div>
    
                        <div class="form-group">
                            <label for="tags" id="dTags">Tags</label>
                            <input type="text" id="tags" name="tags" class="form-control" placeholder="Enter Tags (comma-separated)">
                            <small style="color: red; font-weight: bold" id="tagText"></small>
                            
                        </div>
    
                        <button type="submit" class="submit-btn">Upload Document</button>                
                    </form>
                </div>
            </div>
        </div>
    </main>
 @endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/upload_document.js') }}"></script>

    <script>
        document.getElementById('uploadDocumentForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            const descriptionerror = document.getElementById("descText")
            const descriptionText = document.getElementById("dText")
            const descriptionValue = document.getElementById("description")
    
            const nameerror = document.getElementById("nameText")
            const nameText = document.getElementById("dName")
            const nameValue = document.getElementById("document-name")
    
            const tagerror = document.getElementById("tagText")
            const tagText = document.getElementById("dTags")
            const tagValue = document.getElementById("tags")
    
            if(!descriptionValue.value){
                descriptionText.style.color="#DC3545"
                descriptionerror.style.color="#DC3545"
                descriptionerror.innerHTML = "Description is Required!"
                descriptionValue.style.borderColor="#DC3545"
                
            }else{
                descriptionText.style.color="#333333"
                descriptionerror.style.color="#333333"
                descriptionerror.innerHTML = ""
                descriptionValue.style.borderColor="#CCCCCC"
            }
    
            if(!nameValue.value){
                nameText.style.color="#DC3545"
                nameerror.style.color="#DC3545"
                nameerror.innerHTML = "Document Name is Required!"
                nameValue.style.borderColor="#DC3545"
            }else{
                nameText.style.color="#333333"
                nameerror.style.color="#333333"
                nameerror.innerHTML = ""
                nameValue.style.borderColor="#CCCCCC"
            }
    
            if(!tagValue.value){
                tagText.style.color="#DC3545"
                tagerror.style.color="#DC3545"
                tagerror.innerHTML = "Tag is Required!"
                tagValue.style.borderColor="#DC3545"
            }else{
                tagText.style.color="#333333"
                tagerror.style.color="#333333"
                tagerror.innerHTML = ""
                tagValue.style.borderColor="#CCCCCC"
            }
    
            if(!descriptionValue.value || !nameValue.value || !tagValue.value)
                return
            
            const fileInput = document.getElementById('file-input');
            const file = fileInput.files[0];
    
            if (file && file.type === 'application/pdf' && file.size <= 5242880) { // 5MB = 5242880 bytes
                var formData = new FormData(this);
    
                // Explicitly append the file to the FormData object
                formData.append('file', file);
    
                fetch("{{ route('office_staff.os_upload_document') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload the page to reset the form
                        });
                    } else if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error,
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.',
                        confirmButtonText: 'OK'
                    });
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid file',
                    text: 'Please upload a valid PDF file of up to 5MB.',
                    confirmButtonText: 'OK'
                });
            }
        });
    function handleDrop(event) {
        event.preventDefault();
        const files = event.dataTransfer.files;
        handleFiles(files);
    }

    function handleDragOver(event) {
        event.preventDefault();
        document.getElementById('upload-area').classList.add('drag-over');
    }

    function handleDragLeave(event) {
        document.getElementById('upload-area').classList.remove('drag-over');
    }

    // <!-- Inside the handleFiles function -->
    function handleFiles(files) {
        const fileInput = document.getElementById('file-input');
        const fileList = document.getElementById('file-list');

        // Clear the previous file list
        fileList.innerHTML = '';

        // Loop through the selected files
        for (let i = 0; i < files.length; i++) {
            const file = files[i];

            // Check if the file is a PDF and under 5MB
            if (file.type === 'application/pdf' && file.size <= 5242880) {
                // Create a list item for the file
                const listItem = document.createElement('li');
                listItem.classList.add('file-item');

                // Add the PDF icon
                const icon = document.createElement('i');
                icon.classList.add('bi', 'bi-file-earmark-pdf-fill', 'pdf-icon');

                // Add file name text
                const fileName = document.createElement('span');
                fileName.textContent = file.name;

                // Add a delete button next to the file name
                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'X';
                deleteButton.classList.add('delete-btn');
                deleteButton.addEventListener('click', function() {
                    // Remove the file from the input
                    fileInput.value = '';
                    // Remove the list item from the UI
                    listItem.remove();
                });

                // Append icon, file name, and delete button to the list item
                listItem.appendChild(icon);
                listItem.appendChild(fileName);
                listItem.appendChild(deleteButton);
                
                // Append the list item to the file list
                fileList.appendChild(listItem);

                // Manually set the selected file in the file input
                fileInput.files = files;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid file',
                    text: 'Please upload a valid PDF file of up to 5MB.',
                    confirmButtonText: 'OK'
                });
            }
        }
    }

    document.getElementById('file-input').addEventListener('change', (event) => {
        handleFiles(event.target.files);
    });

    document.addEventListener('DOMContentLoaded', () => {
        const documentNumberField = document.getElementById('document-number');
        const editButton = document.getElementById('edit-document-number-btn');
        const errorText = document.getElementById('documentNumberError');

        // Toggle the disabled state of the input field when the edit button is clicked
        editButton.addEventListener('click', () => {
            if (documentNumberField.disabled) {
                // Enable the field for editing
                documentNumberField.disabled = false;
                documentNumberField.focus(); 
                documentNumberField.style.borderColor = "#007BFF"; 
                editButton.innerHTML = '<i class="bi bi-check2-square icon-size"></i>'; 
            } else {
                // Disable the field again after editing
                documentNumberField.disabled = true;
                documentNumberField.style.borderColor = "#CCCCCC"; 
                editButton.innerHTML = '<i class="bi bi-pencil-square icon-size"></i>'; 
            }
        });

        // Validate document number on blur (when the input field loses focus)
        documentNumberField.addEventListener('blur', () => {
            const documentNumber = documentNumberField.value.trim();

            if (!documentNumber) {
                errorText.textContent = "";
                documentNumberField.style.borderColor = "#CCCCCC"; 
                return;
            }

            // Check if the document number already exists via AJAX (example route)
            fetch("{{ route('office_staff.check_document_number') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ document_number: documentNumber })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    errorText.textContent = "Document number already exists!";
                    documentNumberField.style.borderColor = "#DC3545"; 
                } else {
                    errorText.textContent = "";
                    documentNumberField.style.borderColor = "#CCCCCC"; 
                }
            })
            .catch(err => {
                console.error('Error:', err);
            });
        });
    });

    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>

@endsection

</body>
</html>
