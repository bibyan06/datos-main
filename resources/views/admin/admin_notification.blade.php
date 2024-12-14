@extends('layouts.admin_layout')

@section('title', 'Notification')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
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
            <table class="email-list">
                <th></th>
                <th>Type</th>
                <th>Sender</th>
                <th>Document Name - Message</th>
                <th>Date</th>
                <th>Action</th>
                <tbody>
                    @if ($forwardedDocuments->isEmpty() && $sentDocuments->isEmpty() && $declinedDocuments->isEmpty())
                        <tr>
                            <td colspan="6" class="no-notifications" style="text-align:center; color:red;">You have no Archive Notifications at this time.</td>
                        </tr>
                    @else
                        {{-- Display Forwarded Documents --}}
                        @foreach ($forwardedDocuments as $forwarded)
                            <tr class="email-item {{ $forwarded->status !== 'viewed' ? 'delivered' : '' }}" 
                                data-id="{{ $forwarded->forwarded_document_id }}"
                                data-sender="{{ $forwarded->forwardedByEmployee->first_name ?? 'Unknown' }} {{ $forwarded->forwardedByEmployee->last_name ?? '' }}"
                                data-document="{{ $forwarded->document->document_name ?? 'No Title' }}"
                                data-snippet="{{ $forwarded->message ?? 'No message' }}" 
                                data-type="forward"
                                data-file-url="{{ asset('storage/' . $forwarded->document->file_path) }}">
                                <td class="checkbox"><input type="checkbox"></td>
                                <td class="sender {{ $forwarded->status === 'delivered' ? 'delivered' : 'viewed' }}">{{ $forwarded->forwardedByEmployee->first_name ?? 'Unknown' }} {{ $forwarded->forwardedByEmployee->last_name ?? '' }}</td>
                                <td class="document-type {{ $forwarded->status === 'delivered' ? 'delivered' : 'viewed' }}">Forwarded Document</td>
                                <td class="subject {{ $forwarded->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                    <span class="subject-text">{{ $forwarded->document->document_name ?? 'No Title' }}</span>
                                    <span class="snippet"> - {{ $forwarded->message ?? 'No message' }}</span>
                                </td>
                                <td class="date {{ $forwarded->status === 'delivered' ? 'delivered' : 'viewed' }}">{{ \Carbon\Carbon::parse($forwarded->forwarded_date)->format('M d H:i') }}</td>
                                <td class="email-actions">
                                    <a notif-id="{{ $forwarded->forwarded_document_id }}" status="archiveNotif" class="notifForward" style="text-decoration: none; color:black;">
                                        <i class="bi bi-archive"></i>
                                    </a>
                                    <a notif-id="{{ $forwarded->forwarded_document_id }}" status="deleted" class="notifForward" style="text-decoration: none; color:black;">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        {{-- Display Sent Documents --}}
                        @foreach ($sentDocuments as $sentDocument)
                            <tr class="email-item {{ $sentDocument->status !== 'viewed' || $sentDocument->status === 'deleted' ? 'delivered' : '' }}"
                                data-id="{{ $sentDocument->sent_id }}"
                                data-sender="{{ $sentDocument->sender->first_name ?? 'Unknown' }} {{ $sentDocument->sender->last_name ?? '' }}"
                                data-document="{{ $sentDocument->document_subject ?? 'No Title' }}" 
                                data-snippet="No message"
                                data-type="sent" data-file-url="{{ asset('storage/' . $sentDocument->file_path) }}">
                                <td class="checkbox"><input type="checkbox"></td>
                                <td class="sender {{ $sentDocument->status === 'delivered' ? 'delivered' : 'viewed' }}">{{ $sentDocument->sender->first_name ?? 'Unknown Sender' }} {{ $sentDocument->sender->last_name ?? '' }}</td>
                                <td class="document-type {{ $sentDocument->status === 'delivered' ? 'delivered' : 'viewed' }}">Sent Document</td>
                                <td class="subject {{ $sentDocument->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                    <span class="subject-text">{{ $sentDocument->document_subject ?? 'No Title' }}</span>
                                </td>
                                <td class="date">{{ \Carbon\Carbon::parse($sentDocument->issued_date)->format('M d H:i') }}</td>
                                <td class="email-actions">
                                    <a notif-id="{{ $sentDocument->send_id }}" status="archiveNotif" class="notifSent" style="text-decoration: none; color:black;">
                                        <i class="bi bi-archive"></i>
                                    </a>
                                    <a notif-id="{{ $sentDocument->send_id }}" status="deleted" class="notifSent" style="text-decoration: none; color:black;">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        {{-- Display Declined Documents --}}
                        @foreach ($declinedDocuments as $declined)
                            <tr class="email-item {{ $declined->status === 'viewed' || $declined->status === 'deleted' ? 'delivered' : '' }}"
                                data-id="{{ $declined->document_id }}"
                                data-type="declined"
                                data-sender="{{ $declined->declined_by ?? 'Admin' }}"
                                data-document="{{ $declined->document_name ?? 'No Title' }}"
                                data-snippet="Your document was declined. Please review and try again."
                                data-remark="{{ $declined->remark ?? 'Your document was declined. Please review and re-upload.' }}"
                                data-status="{{ $declined->document_status }}"
                                data-file-url="{{ asset('storage/' . $declined->file_path) }}">
                                <td class="checkbox"><input type="checkbox"></td>
                                <td class="sender {{ $declined->status === 'delivered' || $declined->status === 'deleted' ? 'delivered' : 'viewed' }}">{{ $declined->declined_by ?? 'Admin' }}</td>
                                <td class="document-type {{ $declined->status === 'delivered' ? 'delivered' : 'viewed' }}">Declined Document</td>
                                <td class="subject {{ $declined->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                    <span class="subject-text">{{ $declined->document_name ?? 'No Title' }}</span>
                                    <span class="remark"> - {{ $declined->remark ?? 'No remark' }}</span>  
                                </td>
                                <td class="date {{ $declined->status === 'delivered' || $declined->status === 'deleted' ? 'delivered' : 'viewed' }}">{{ \Carbon\Carbon::parse($declined->declined_date)->format('M d H:i') }}</td>
                                <td class="email-actions">
                                    <a notif-id="{{ $declined->document_id }}" status="archiveNotif" class="notifDeclined" style="text-decoration: none; color:black;">
                                        <i class="bi bi-archive"></i>
                                    </a>
                                    <a notif-id="{{ $declined->document_id }}" status="deleted" class="notifDeclined" style="text-decoration: none; color:black;">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('js/notification.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
