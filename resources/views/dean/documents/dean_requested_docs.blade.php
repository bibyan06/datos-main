@extends('layouts.dean_layout')

@section('title', 'Requested Document')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/sent_document.css') }}">
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
                <table class="email-list">
                    <!-- Loop through Forwarded Documents -->
                    @foreach($requestedDocuments as $r)
                        <tr class="email-item"
                            data-status="{{ $r->approval_status }}">
                            
                            <td class="checkbox"><input type="checkbox"></td>
                            <td class="star">★</td>
                            <td class="document-name">{{ $r->document_subject ?? 'Unknown Document' }}</td>
                            <td class="request-purpose">{{ $r->request_purpose ?? 'Unknown Purpose' }}</td>
                            <td class="date">{{ \Carbon\Carbon::parse($r->request_date)->format('M d H:i') }}</td>
                            <td class="email-actions">
                                <a notif-id={{ $r->request_id }} status= 'deleted'
                                    class = "notifForward" style="text-decoration: none; color:black;"><i
                                        class="bi bi-trash"></i>
                                </a>
                                <a notif-id={{ $r->request_id }} 
                                    class = "" style="text-decoration: none; color:black;"><i
                                    class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('js/dean_notification.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection