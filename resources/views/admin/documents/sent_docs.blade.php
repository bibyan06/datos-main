@extends('layouts.admin_layout')

@section('title', 'Sent and Forwarded Documents')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
@endsection

@section('main-id', 'dashboard-content')

@section('content')
    <section class="title">
        <div class="title-content">
            <h3>Sent Documents</h3>
            <div class="date-time">
                <i class="bi bi-calendar2-week-fill"></i>
                <p id="current-date-time"></p>
            </div>
        </div>
    </section>

    <div id="dashboard-section">
        <div class="dashboard-container">
            @if($forwardedDocuments->isEmpty() && $sentDocuments->isEmpty())
                <p class="no-notifications">You have no sent or forwarded documents at this time.</p>
            @else
                <table class="email-list">
                    <th></th>
                    <th></th>
                    <th>Receiver</th>
                    <th>Document Name - Message</th>
                    <th>Date</th>
                    <th>Action</th>
                    <!-- Loop through Forwarded Documents -->
                    @foreach($forwardedDocuments as $forwarded)
                        <tr class="email-item"
                            data-file-url="{{ asset('storage/' . $forwarded->document->file_path) }}"
                            data-status="{{ $forwarded->status }}"
                            data-message="{{ $forwarded->message ?? 'No message' }}" 
                            data-document-name="{{ $forwarded->document->document_name ?? 'Unknown Document' }}">
                            
                            <td class="checkbox"><input type="checkbox"></td>
                            <td class="sender">Forwarded Document to:</td>
                            <td class="document-type">
                                <span class="receiver">
                                    {{ $forwarded->forwardedToEmployee->first_name ?? 'Unknown' }} 
                                    {{ $forwarded->forwardedToEmployee->last_name ?? 'User' }}
                                </span>
                            </td>
                            <td class="document-name">
                                {{ $forwarded->document->document_name ?? 'Unknown Document' }} - {{ $forwarded->message ?? 'No message' }}
                            </td>

                            <td class="date">{{ \Carbon\Carbon::parse($forwarded->forwarded_date)->format('M d H:i') }}</td>
                            <td class="email-actions">
                                <a notif-id={{ $forwarded->forwarded_document_id }} status= 'deleted'
                                    class = "notifForward" style="text-decoration: none; color:black;"><i
                                        class="bi bi-trash"></i></a>

                            </td>
                        </tr>
                    @endforeach

                    <!-- Loop through Sent Documents -->
                    @foreach($sentDocuments as $sent)
                        <tr class="sent-items"
                            data-file-url="{{ asset('storage/' . $sent->file_path) }}"
                            data-status="{{ $sent->status }}"
                            data-document-name="{{ $sent->document_subject ?? 'Unknown Document' }}"
                            data-receiver="{{ $sent->recipient->first_name ?? 'Unknown' }} {{ $sent->recipient->last_name ?? 'User' }} ">
                            
            
                            <td class="checkbox"><input type="checkbox"></td>
                            <td class="sender">Sent Requested Document to:</td>
                            <td class="document-type">
                                <span class="receiver">
                                    {{ $sent->recipient->first_name ?? 'Unknown' }} 
                                    {{ $sent->recipient->last_name ?? 'User' }} 

                                </span>
                            </td>
                            <td class="document-name">{{ $sent->document_subject ?? 'Unknown Document' }}</td>
                            <td class="date">{{ \Carbon\Carbon::parse($sent->issued_date)->format('M d H:i') }}</td>
                            <td class="email-actions">
                                <a notif-id={{ $sent->send_id }} status= 'deleted' class = "notifSent"
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
    <script src="{{ asset('js/admin_notification.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection