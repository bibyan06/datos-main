@extends('layouts.dean_layout')

@section('title', 'Requested Document')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
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
                    <input type="checkbox">
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
@endsection
