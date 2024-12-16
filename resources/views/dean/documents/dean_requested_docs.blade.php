@extends('layouts.dean_layout')

@section('title', 'Requested Document')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
    <style>
        .deletes {
            display: none;
            padding: 10px 20px;
            color: rgb(173, 18, 18);
            font-size: 1em;
            width: 200px;
            cursor: pointer;
            font-weight: bold
        }   
    </style>
@endsection

@section('main-id', 'dashboard-content')

@section('content')
    <section class="title">
        <div class="title-content">
            <h3>Requested Document</h3>
            <div class="date-time">
                <i class="bi bi-calendar2-week-fill"></i>
                <p id="current-date-time"></p>
            </div>
        </div>
    </section>

    <div id="dashboard-section">
        <div class="dashboard-container">
    @if($requestedDocuments->isEmpty())
        <p class="no-notifications">You have no requested documents at this time.</p>
    @else

    <p>
        <div class="deletes btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
            fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
        <path
            d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
            </svg><span>Delete</span>
         </div>
    </p>
    <table class="email-list">
        <th></th>
        <th>Type</th>
        <th>Document Name - Message</th>
        <th>Date</th>
        <th>Action</th>

        @foreach ($requestedDocuments as $r)
             <tr class="requested-docs {{ $r->approval_status === 'Pending' ? 'Pending' : 'Declined' }} {{ $r->status === 'delivered' ? 'bold-row' : '' }}"
                data-id="{{ $r->request_id }}" 
                data-document="{{ $r->document_subject ?? 'No Title' }}" 
                data-status="{{ $r->approval_status }}" 
                data-declined-by="{{ $r->declined_by ?? 'Admin' }}" 
                data-remarks="{{ $r->remarks ?? '' }}" 
                data-request-purpose="{{ $r->request_purpose ?? 'No Purpose Provided' }}">
                
                <td class="checkbox">
                    <input type="checkbox" class="check" data-type={{$r['type']}}data-id={{ $r['id']}}>
                </td>
                
                <td class="document-type {{ $r->approval_status === 'Pending' ? 'Pending' : 'Approved' }}">
                    Requested Document:
                </td>

                <td class="subject {{ $r->approval_status === 'Pending' ? 'Pending' : 'Approved' }}" style="display: flex; align-items: center;">
                                <span class="subject-text">{{ $r->document_subject ?? 'No Title' }}</span>

                @if($r->approval_status === 'Approved')
                    <!-- <span class="request-purpose">
                        - {{ $r->request_purpose ?? 'No Purpose Provided' }}
                    </span> -->
                @endif
                
                </td>
                    <td class="date {{ $r->approval_status === 'Pending' ? 'Pending' : 'Approved' }}">
                        {{ \Carbon\Carbon::parse($r->request_date)->format('M d H:i') }}
                    </td>
                    <td class="email-actions">
                        <a notif-id={{ $r->request_id }} status='archive'
                            class="notifReqDeclined" style="text-decoration: none; color: black;">
                                <i class="bi bi-archive"></i>
                        </a>
                    <a notif-id={{ $r->request_id }} status='deleted'
                        class="notifReqDeclined" style="text-decoration: none; color: black;">
                            <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
@endif


@endsection

@section('custom-js')
    <script src="{{ asset('js/dean/notification.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // let table = new DataTable('#myTable');
        let listId = [];

        document.addEventListener('DOMContentLoaded', () => {
            const selectedDocuments = [{}];
            const deleteBtn = document.querySelector('.deletes')
            // Listen for changes on all checkboxes within the declined-docs rows
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const documentId = checkbox.getAttribute('data-id');
                    const documentType = checkbox.getAttribute('data-type');
                   
                    if (this.checked) {
                        // Add the row attributes to the array
                        listId.push({
                            id: documentId,
                            type: documentType
                        });
                    } else {
                        const index = listId.findIndex(doc => doc.id === documentId);
                        if (index !== -1) {
                            listId.splice(index, 1);
                        }
                    }
                    if (listId.length > 0) {
                        deleteBtn.style.display = 'block';
                    } else {
                        deleteBtn.style.display = 'none';
                    }

                    // console.log(listId); // Debug: View the current array
                });
            });
            deleteBtn.addEventListener('click', () => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to proceed with this action?",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',          
                    reverseButtons: true, 
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (listId.length > 0) {
                            listId.forEach(data => {
                                console.log(data)
                                fetch(`/dean/batch_delete/${data.id}/deleted/${data.type}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            console.log("Deleted", data)
                                        }
                                    })
                            });
                            setTimeout(() => {
                                Swal.fire(
                                    `Deleted`,
                                    "Documents are deleted successfully", 'success').then(() => {
                                    // Optionally refresh or redirect
                                    window.location.reload(); // Refresh the page
                                })
                            }, 1500);

                        }
                    }
                });
            });
        });
    </script>
@endsection
