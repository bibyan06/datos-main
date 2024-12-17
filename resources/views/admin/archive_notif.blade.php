@extends('layouts.admin_layout')

@section('title', 'Archived Notification')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
@endsection

@section('main-id', 'dashboard-content')

@section('content')
    <section class="title">
        <div class="title-content">
            <h3>Archive Notification</h3>
            <div class="date-time">
                <i class="bi bi-calendar2-week-fill"></i>
                <p id="current-date-time"></p>
            </div>
        </div>
    </section>

    <div id="dashboard-section">
        <div class="dashboard-container">
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
                <th>Sender</th>
                <th>Type</th>
                <th>Document Name - Message</th>
                <th>Date</th>
                <th>Action</th>
                <tbody>
                    @if ($forward->isEmpty() && $uploaded->isEmpty())
                        <tr>
                            <td colspan="6" class="no-notifications" style="text-align:center; color:red;">You have no Archive Notifications at this time.</td>
                        </tr>
                    @else
                        <!-- Forwarded Notifications -->
                        @if (!$forward->isEmpty())
                            @foreach ($forward as $r)
                                <tr class="email-item {{ $r['status']=='delivered'?'delivered':'viewed' }}"
                                    data-id="{{ $r->forwarded_document_id }}"
                                    data-sender="{{ $r->forwardedByEmployee->first_name ?? 'Unknown' }} {{ $forwarded->forwardedByEmployee->last_name ?? 'User' }}"
                                    data-document="{{ $r->document->document_name ?? 'No Title' }}"
                                    data-snippet="{{ $r->message ?? 'No message' }}"
                                    data-type="forward"
                                    data-file-url="{{ asset('storage/' . $r->document->file_path) }}">

                                    <td class="checkbox">
                                        <input type="checkbox" class="check" data-type={{ $r['type'] }} data-id={{ $r['id'] }}>
                                    </td>
                                    <td class="sender {{ $r->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                        {{ $r->forwardedByEmployee->first_name ?? 'Unknown' }} {{ $r->forwardedByEmployee->last_name ?? 'User' }}
                                    </td>
                                    <td class="document-type {{ $r->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                        Forwarded Document
                                    </td>
                                    <td class="subject {{ $r->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                        <span class="subject-text">{{ $r->document->document_name ?? 'No Title' }}</span>
                                        <span class="snippet"> - {{ $r->message ?? 'No message' }}</span>
                                    </td>
                                    <td class="date {{ $r->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                        {{ \Carbon\Carbon::parse($r->forwarded_date)->format('M d H:i') }}
                                    </td>
                                    <td class="email-actions">
                                        <a notif-id="{{ $r->forwarded_document_id }}" status='viewed' class="notifForward" style="text-decoration: none; color:black;">
                                            <i class="bi bi-arrow-counterclockwise" title="Restore"></i>
                                        </a>
                                        <a notif-id="{{ $r->forwarded_document_id }}" status="deleted" class="notifForward" style="text-decoration: none; color:black;">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        <!-- Uploaded Declined Documents -->
                        @if (!$uploaded->isEmpty())
                            @foreach ($uploaded as $u)
                                <tr class="declined-docs {{ $u->status === 'delivered' ? 'delivered' : 'viewed' }}"
                                    data-id="{{ $u->document_id }}"
                                    data-type="declined"
                                    data-sender="{{ $u->declined_by ?? 'Admin' }}"
                                    data-document="{{ $u->document_name ?? 'No Title' }}"
                                    data-snippet="Your document was declined. Please review and try again."
                                    data-remark="{{ $u->remark ?? 'Your document was declined. Please review and re-upload.' }}"
                                    data-status="{{ $u->document_status }}"
                                    data-file-url="{{ asset('storage/' . $u->file_path) }}">

                                    <td class="checkbox">
                                        <input type="checkbox" class="check" data-type={{$u['type']}} data-id={{ $u['id']}}>
                                    </td>
                                    <td class="sender {{ $u->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                        {{ $u->declined_by ?? 'Admin' }}
                                    </td>
                                    <td class="document-type {{ $u->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                        Declined Document
                                    </td>
                                    <td class="subject {{ $u->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                        <span class="subject-text">{{ $u->document_name ?? 'No Title' }}</span>
                                        <span class="remark"> - {{ $u->remark ?? 'No remark' }}</span>
                                    </td>
                                    <td class="date {{ $u->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                        {{ \Carbon\Carbon::parse($u->declined_date)->format('M d H:i') }}
                                    </td>
                                    <td class="email-actions">
                                        <a notif-id= {{ $u->document_id }} status='viewed' class="notifDeclined" style="text-decoration: none; color:black;">
                                            <i class="bi bi-arrow-counterclockwise" title="Restore"></i>
                                        </a>
                                        <a notif-id={{ $u->document_id }} status='deleted' class="notifDeclined" style="text-decoration: none; color:black;">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('js/notification.js') }}"></script>
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
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#8592A3',
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Yes',          
                    reverseButtons: true, 
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (listId.length > 0) {
                            listId.forEach(data => {
                                console.log(data)
                                fetch(`/admin/batch_delete/${data.id}/deleted/${data.type}`)
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
