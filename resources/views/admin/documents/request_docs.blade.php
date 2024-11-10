@extends('layouts.admin_layout')

@section('title', 'Request Document')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/request.css') }}">
@endsection

@section('main-id', 'dashboard-content')

@section('content')
    <section class="title">
        <div class="title-content">
            <h3>Requested Documents</h3>
            <div class="date-time">
                <i class="bi bi-calendar2-week-fill"></i>
                <p id="current-date-time"></p>
            </div>
        </div>
    </section>

    <div id="dashboard-section">
        <div class="dashboard-container">
            <table>
                <thead>
                    <tr>
                        <th>Document Subject</th>
                        <th>Request Purpose</th>
                        <th>Requested Date</th>
                        <th>Requested By</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $item)
                        <tr>
                            <td>{{ $item->document_subject }}</td>
                            <td>{{ $item->request_purpose }}</td>
                            <td>{{ $item->request_date }}</td>
                            <td>{{ $item->requestedBy ? $item->requestedBy->first_name . ' ' . $item->requestedBy->last_name : 'N/A' }}</td>
                            <td class="{{ $item->approval_status }}">{{ $item->approval_status }}</td>
                            <td class="action-buttons">
                                <i class="bi bi-pencil-square" title="Send Document"
                                    onclick="showPopupForm({{$item->request_id}},{{$item->requested_by}},'{{ $item->document_subject }}','{{ $item->request_purpose }}')"></i>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="overlay" class="overlay" onclick="hidePopupForm()"></div>
        <div id="popup-form" class="popup-form">
            <h2>Send Document</h2>
            <!-- Removed the form tag -->
            <div id="dean-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="sender-name">Sender Name</label>
                        <input type="hidden" id="docu-id" value="">
                        <input type="hidden" id="sender-id" value="{{ auth()->user()->employee_id }}">
                        <input type="text" id="sender-names"
                            value="{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <input type="hidden" name="request_id" id="request_id">
                        <label for="document-subject">Document Subject</label>
                        <input type="text" id="document-subject">
                    </div>
                    <div class="form-group">
                        <label for="document-purpose">Document Purpose</label>
                        <input type="text" id="document-purpose">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="file-name">File</label>
                        <input type="file" id="file-name">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="date">Sent Date</label>
                        <input type="text" id="date" value="{{ now()->format('Y-m-d H:i:s') }}">
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="button" id="cancel-btn" onclick="hidePopupForm()">Cancel</button>
                    <button type="button" id="send-document-btn" onclick="submitFormData()">Send Document</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('js/request.js') }}"></script>
    <script>
        function submitFormData() {
            const fileInput = document.getElementById('file-name');
            const file = fileInput.files[0];

            if (file && file.type === 'application/pdf' && file.size <= 5242880) {
                const formData = new FormData();
                
                formData.append('docu-id', document.getElementById('docu-id').value);
                formData.append('request-id', document.getElementById('request_id').value);
                formData.append('sender-name', document.getElementById('sender-id').value);
                formData.append('document-subject', document.getElementById('document-subject').value);
                formData.append('document-purpose', document.getElementById('document-purpose').value);
                formData.append('file', file);
                formData.append('date', document.getElementById('date').value);

                fetch("{{ route('admin.admin_send_document') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    console.log(response);
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    if (data.msg) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.msg,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload the page to reset the form
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Unexpected response format',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.log(error);
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
                    title: 'File Error',
                    text: 'Please upload a PDF file under 5MB.',
                    confirmButtonText: 'OK'
                });
            }
        }
    </script>
@endsection
