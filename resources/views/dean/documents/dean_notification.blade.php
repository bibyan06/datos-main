@extends('layouts.dean_layout')

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
                        data-snippet="{{ $forwarded->message ?? 'No message' }}" 
                        data-type="forward"
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
                        
                        <td class="date">{{ \Carbon\Carbon::parse($forwarded->forwarded_date)->format('M d H:i') }} </td>
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
                        data-document="{{ $sentDocument->document_subject ?? 'No Title' }}" 
                        data-type="request"
                        data-file-url="{{ asset('storage/' . $sentDocument->file_path) }}">
                       
                        <td class="checkbox"><input type="checkbox"></td>
                        <td class="sender {{ $sentDocument->status === 'delivered' ? 'delivered' : 'viewed' }}">
                            {{ $sentDocument->sender->first_name ?? 'Unknown Sender' }}
                            {{ $sentDocument->sender->last_name ?? '' }}
                        </td>
                        <td>Sent Document</td>
                        <td class="subject {{ $sentDocument->status === 'delivered' ? 'delivered' : 'viewed' }}">
                            <span class="subject-text">{{ $sentDocument->document_subject ?? 'No Title' }}</span>
                        </td>
                       
                        <td class="date">{{ \Carbon\Carbon::parse($sentDocument->issued_date)->format('M d H:i') }}</td>
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
@endsection

