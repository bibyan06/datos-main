@extends('layouts.office_staff_layout')

@section('title', 'Archived Documents')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/archived.css') }}">

@endsection

@section('main-id', 'dashboard-content')

@section('content')
    <section class="title">
        <div class="title-content">
            <h3>Archive</h3>
            <div class="date-time">
                <i class="bi bi-calendar2-week-fill"></i>
                <p id="current-date-time"></p>
            </div>
        </div>
    </section>

    <div id="dashboard-section">
        <div class="dashboard-container">
            @if ($forward->isEmpty() && $uploaded->isEmpty())
                <p class="no-notifications">You have no Archive Notifications at this time.</p>
            @else
                <table class="email-list">
                    <!-- Forwarded Notifications -->
                    @if (!$forward->isEmpty())
                        @foreach ($forward as $r)
                            <tr class="email-item">
                                <td class="checkbox"><input type="checkbox"></td>
                                <td class="sender">{{ $r->forwardedBy->first_name . ' ' . $r->forwardedBy->last_name }}</td>
                                <td class="document-type">Forwarded Document</td>
                                <td class="subject">
                                    <span class="snippet">{{ $r->documents->document_name }} - {{ $r->message }}</span>
                                </td>
                                <td class="date">{{ \Carbon\Carbon::parse($r->documents->upload_date)->format('M d H:i') }}</td>
                                <td class="email-actions">
                                    <a notif-id='{{ $r->forwarded_document_id }}' status='viewed' class="notifForward"
                                       style="text-decoration: none; color:black;">
                                        <i class="bi bi-arrow-counterclockwise" title="Restore"></i>
                                    </a>
                                    <a notif-id={{ $r->forwarded_document_id }} status= 'deleted'
                                        class = "notifForward" style="text-decoration: none; color:black;"><i class="bi bi-trash" title ="Delete"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                   <!-- Uploaded Declined Documents -->
                   @if (!$uploaded->isEmpty())
                        @foreach ($uploaded as $u)
                            <tr class="email-item">
                                <td class="checkbox"><input type="checkbox"></td>
                                <td class="sender">{{ $u->declined_by }}</td> 
                                <td class="document-type">Declined Document</td>
                                <td class="subject">
                                    <span class="snippet">{{ $u->document_name }} - {{ $u->remark }}</span>
                                </td>
                                <td class="date">{{ \Carbon\Carbon::parse($u->declined_date)->format('M d H:i') }}</td>
                                <td class="email-actions">
                                    <a notif-id="{{ $u->document_id }}" status="viewed" class="notifDeclined" 
                                        style="text-decoration: none; color:black;">
                                        <i class="bi bi-arrow-counterclockwise" title="Restore"></i>
                                    </a>
                                    <a notif-id={{ $u->document_id }} status= 'deleted'
                                        class="notifDeclined" style="text-decoration: none; color: black;">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            @endif
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="js/archived.js"></script>
@endsection
