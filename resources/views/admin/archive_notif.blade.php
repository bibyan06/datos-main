@extends('layouts.admin_layout')

@section('title', 'Archived Notification')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/archived.css') }}">
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
            @if ($forward->isEmpty() && $uploaded->isEmpty())
                <p class="no-notifications">You have no Archive Notifications at this time.</p>
            @else
                <table class="email-list">
                    <!-- Forwarded Notifications -->
                    @if ($forward->isNotEmpty())
                        @foreach ($forward as $r)
                            <tr class="email-item">
                                <td class="checkbox"><input type="checkbox"></td>
                                <td class="sender">{{ $r->forwardedTo->first_name . ' ' . $r->forwardedTo->last_name }}</td>
                                <td class="subject">
                                    <span class="snippet">{{ $r->documents->document_name }} - {{ $r->message }}</span>
                                </td>
                                <td class="date">{{ \Carbon\Carbon::parse($r->documents->upload_date)->format('M d H:i') }}</td>
                                <td class="email-actions">
                                    <a notif-id="{{ $r->forwarded_document_id }}" status="viewed" class="notifForward"
                                       style="text-decoration: none; color:black;">
                                        <i class="bi bi-arrow-counterclockwise" title="Restore"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                    <!-- Uploaded Documents -->
                    @if ($uploaded->isNotEmpty())
                        @foreach ($uploaded as $u)
                            <tr class="email-item">
                                <td class="checkbox"><input type="checkbox"></td>
                                <td class="sender">{{ $u->uploadedBy->first_name . ' ' . $u->uploadedBy->last_name }}</td>
                                <td class="subject">
                                    <span class="snippet">{{ $u->document_name }} - {{ $u->description }}</span>
                                </td>
                                <td class="date">{{ \Carbon\Carbon::parse($u->upload_date)->format('M d H:i') }}</td>
                                <td class="email-actions">
                                    <a notif-id="{{ $u->id }}" status="viewed" class="notifForward"
                                       style="text-decoration: none; color:black;">
                                        <i class="bi bi-arrow-counterclockwise" title="Restore"></i>
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
