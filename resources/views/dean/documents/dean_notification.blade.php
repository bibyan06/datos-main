@extends('layouts.dean_layout')

@section('title', 'Notification')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/dean/notification.css') }}">
    <style>
        .delivered {
            font-weight: bold;
        }

        .viewed {
            font-weight: normal;
        }
    </style>
@endsection

@section('main-id', 'dashboard-content')

@section('content')
<section class="title">
    <div class="title-content">
        <h3>Notification</h3>
        <div class="date-time">
            <i class="bi bi-calendar2-week-fill"></i>
            <p id="current-date-time"></p>
        </div>
    </div>
</section>

<div id="dashboard-section">
    <div class="dashboard-container">
        @if ($forwardedDocuments->isEmpty() && $sentDocuments->isEmpty())
            <p class="no-notifications">You have no notifications at this time.</p>
        @else
            <table class="email-list">
                {{-- Display Forwarded Documents --}}
                @foreach ($forwardedDocuments as $forwarded)
                    <tr class="email-item {{ $forwarded->status !== 'viewed' ? 'delivered' : '' }}"
                        data-id="{{ $forwarded->forwarded_document_id }}"
                        data-sender="{{ $forwarded->forwardedByEmployee->first_name ?? 'Unknown' }} {{ $forwarded->forwardedByEmployee->last_name ?? '' }}"
                        data-document="{{ $forwarded->document->document_name ?? 'No Title' }}"
                        data-snippet="{{ $forwarded->message ?? 'No message' }}" data-type="forward"
                        data-file-url="{{ asset('storage/' . $forwarded->document->file_path) }}">

                        <td class="checkbox"><input type="checkbox"></td>
                        <!-- <td class="star">★</td> -->
                        <td class="sender {{ $forwarded->status === 'delivered' ? 'delivered' : 'viewed' }}">
                            {{ $forwarded->forwardedByEmployee->first_name ?? 'Unknown' }}
                            {{ $forwarded->forwardedByEmployee->last_name ?? '' }}
                        </td>
                        <td>Forwarded Document</td>
                        <td class="subject {{ $forwarded->status === 'delivered' ? 'delivered' : 'viewed' }}">
                            <span class="subject-text">{{ $forwarded->document->document_name ?? 'No Title' }}</span>
                            <span class="snippet"> - {{ $forwarded->message ?? 'No message' }}</span>
                        </td>
                        
                        <td class="date">{{ \Carbon\Carbon::parse($forwarded->forwarded_date)->format('M d H:i') }}
                        </td>
                        <td class="email-actions">
                            <a notif-id={{ $forwarded->forwarded_document_id }} status='archive' class="notifForward"
                                style="text-decoration: none; color:black;"><i class="bi bi-archive"></i>
                            </a>
                            <a notif-id={{ $forwarded->forwarded_document_id }} status='deleted' class="notifForward"
                                style="text-decoration: none; color:black;"><i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach

                {{-- Display Sent Documents --}}
                @foreach ($sentDocuments as $sentDocument)
                    <tr class="email-items {{ $sentDocument->status === 'delivered' ? 'delivered' : 'viewed' }}"
                        data-id="{{ $sentDocument->send_id }}"
                        data-sender="{{ $sentDocument->sender->first_name ?? 'Unknown Sender' }} {{ $sentDocument->sender->last_name ?? '' }}"
                        data-document="{{ $sentDocument->document_subject ?? 'No Title' }}" data-type="request"
                        data-file-url="{{ asset('storage/' . $sentDocument->file_path) }}">
                        <td class="checkbox"><input type="checkbox"></td>
                        <!-- <td class="star">★</td> -->
                        <td class="sender {{ $sentDocument->status === 'delivered' ? 'delivered' : 'viewed' }}">
                            {{ $sentDocument->sender->first_name ?? 'Unknown Sender' }}
                            {{ $sentDocument->sender->last_name ?? '' }}
                        </td>
                        <td class="subject {{ $sentDocument->status === 'delivered' ? 'delivered' : 'viewed' }}">
                            <span class="subject-text">{{ $sentDocument->document_subject ?? 'No Title' }}</span>
                        </td>
                        <td>Requested Document</td>
                        <td class="date">{{ \Carbon\Carbon::parse($sentDocument->issued_date)->format('M d') }}</td>
                        <td class="email-actions">
                            <a notif-id={{ $sentDocument->send_id }} status='archive' class="notifSent"
                                style="text-decoration: none; color:black;"><i class="bi bi-archive"></i></a>
                            <a notif-id={{ $sentDocument->send_id }} status='deleted' class="notifSent"
                                style="text-decoration: none; color:black;"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
</div>
@endsection

@section('custom-js')
<script src="{{ asset('js/dean/notification.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const emailItems = document.querySelectorAll('.email-item');

        emailItems.forEach(item => {
            item.addEventListener('click', function (e) {
                if (!e.target.closest('.email-actions') && !e.target.closest('.checkbox')) {
                    const forwardedDocumentId = this.getAttribute('data-id');
                    const sender = this.getAttribute('data-sender');
                    const documentName = this.getAttribute('data-document');
                    const snippet = this.getAttribute('data-snippet');
                    const fileUrl = this.getAttribute('data-file-url');
                    const types = this.getAttribute('data-type');
                    let text;
                    if (types === 'forward')
                        text = `<p><strong>Description:</strong> ${snippet}</p>`
                    else
                        text = ""
                    console.log(fileUrl)
                    Swal.fire({
                        title: `<strong>${documentName}</strong>`,
                        html: `
                        <div style="text-align: left; margin-top: 10px;">
                            <p><strong>Sender:</strong> ${sender}</p>
                            ${text}
                           
                        </div>
                        <iframe src="${fileUrl}" width="100%" height="400px" style="border:none; margin-top: 20px;"></iframe>
                    `,
                        showCloseButton: true,
                        confirmButtonText: 'Mark as viewed',
                        showCancelButton: true,
                        cancelButtonText: 'Close',
                        customClass: {
                            popup: 'custom-swal-width',
                            title: 'custom-title'
                        }
                    }).then((result) => {

                        if (result.isConfirmed && forwardedDocumentId) {
                            console.log(
                                "Attempting to send request to update status...");

                            fetch(`/forwarded-documents/${forwardedDocumentId}/update-status`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            })
                                .then(response => {
                                    console.log("Received response:", response);
                                    if (!response.ok) {
                                        throw new Error(
                                            `HTTP error! Status: ${response.status}`
                                        );
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    console.log("Response data:", data);
                                    if (data.success) {
                                        Swal.fire('Success',
                                            'Document status updated to "viewed".',
                                            'success')
                                            .then(() => {
                                                document.querySelector(
                                                    `[data-id="${forwardedDocumentId}"]`
                                                ).classList.remove(
                                                    'delivered');
                                                document.querySelector(
                                                    `[data-id="${forwardedDocumentId}"] .sender`
                                                ).style.fontWeight =
                                                    'normal';
                                                document.querySelector(
                                                    `[data-id="${forwardedDocumentId}"] .subject`
                                                ).style.fontWeight =
                                                    'normal';
                                                document.querySelector(
                                                    `[data-id="${forwardedDocumentId}"] .data-document`
                                                ).style.fontWeight =
                                                    'normal';
                                            });
                                    } else {
                                        Swal.fire('Error', data.message ||
                                            'Failed to update document status.',
                                            'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error during fetch operation:',
                                        error);
                                    Swal.fire('Error',
                                        'Failed to update document status. Please try again.',
                                        'error');
                                });
                        }
                    });
                }
            });
        });
        const emailSentItem = document.querySelectorAll('.email-items');

emailSentItem.forEach(item => {
    item.addEventListener('click', function(e) {
        if (!e.target.closest('.email-actions') && !e.target.closest('.checkbox')) {
            const forwardedDocumentId = this.getAttribute('data-id');
            const sender = this.getAttribute('data-sender');
            const documentName = this.getAttribute('data-document');
            const snippet = this.getAttribute('data-snippet');
            const fileUrl = this.getAttribute('data-file-url');
            console.log(forwardedDocumentId,sender,documentName,snippet,fileUrl)
            Swal.fire({
                title: `<strong>${documentName}</strong>`,
                html: `
                    <div style="text-align: left; margin-top: 10px;">
                        <p><strong>Sender:</strong> ${sender}</p>
                        <p><strong>Description:</strong> ${snippet}</p>
                    </div>
                    <iframe src="${fileUrl}" width="100%" height="400px" style="border:none; margin-top: 20px;"></iframe>
                `,
                showCloseButton: true,
                confirmButtonText: 'Mark as viewed',
                showCancelButton: true,
                cancelButtonText: 'Close',
                customClass: {
                    popup: 'custom-swal-width',
                    title: 'custom-title'
                }
            }).then((result) => {
              
                if (result.isConfirmed && forwardedDocumentId) {
                    console.log("Attempting to send request to update status...");
                    
                    fetch(`/sent-documents/${forwardedDocumentId}/update-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        console.log("Received response:", response);
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Response data:", data);
                        if (data.success) {
                            Swal.fire('Success', 'Document status updated to "viewed".', 'success')
                                .then(() => {
                                    document.querySelector(`[data-id="${forwardedDocumentId}"]`).classList.remove('delivered');
                                    document.querySelector(`[data-id="${forwardedDocumentId}"] .sender`).style.fontWeight = 'normal';
                                    document.querySelector(`[data-id="${forwardedDocumentId}"] .data-document`).style.fontWeight = 'normal';
                                });
                        } else {
                            Swal.fire('Error', data.message || 'Failed to update document status.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error during fetch operation:', error);
                        Swal.fire('Error', 'Failed to update document status. Please try again.', 'error');
                    });
                }
            });
        }
    });
});
    });
</script>
@endsection

</body>

</html>