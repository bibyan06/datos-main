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
            @if ($forward->isEmpty() && $uploaded->isEmpty())
                <p class="no-notifications">You have no Archive Notifications at this time.</p>
            @else
                <table class="email-list">
                    <!-- Forwarded Notifications -->
                    @if (!$forward->isEmpty())
                        @foreach ($forward as $r)
                            <tr class="email-item" 
                                data-id="{{ $r->forwarded_document_id }}"
                                data-sender="{{ $r->forwardedByEmployee->first_name ?? 'Unknown' }} {{ $forwarded->forwardedByEmployee->last_name ?? '' }}"
                                data-document="{{ $r->document->document_name ?? 'No Title' }}"
                                data-snippet="{{ $r->message ?? 'No message' }}" 
                                data-type="forward"
                                data-file-url="{{ asset('storage/' . $r->document->file_path) }}">

                                <td class="checkbox"><input type="checkbox"></td>
                                <!-- <td class="star">★</td> -->
                                <td class="sender {{ $r->status === 'delivered' ? 'delivered' : 'viewed' }}">{{ $r->forwardedByEmployee->first_name ?? 'Unknown' }}
                                    {{ $r->forwardedByEmployee->last_name ?? '' }}
                                </td>
                                <td class="document-type {{ $r->status === 'delivered' ? 'delivered' : 'viewed' }}">Forwarded Document</td>
                                <td class="subject {{ $r->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                    <span class="subject-text">{{ $r->document->document_name ?? 'No Title' }}</span>
                                    <span class="snippet"> - {{ $r->message ?? 'No message' }}</span>
                                </td>
                                
                                <td class="date{{ $r->status === 'delivered' ? 'delivered' : 'viewed' }}">{{ \Carbon\Carbon::parse($r->forwarded_date)->format('M d H:i') }}</td>
                                <td class="email-actions">
                                    <a notif-id={{ $r->forwarded_document_id }} status= 'viewed'
                                        class = "notifForward" style="text-decoration: none; color:black;"><i
                                        class="bi bi-arrow-counterclockwise" title="Restore"></i>
                                    </a>
                                    <a notif-id={{ $r->forwarded_document_id }} status= 'deleted'
                                        class = "notifForward" style="text-decoration: none; color:black;"><i
                                            class="bi bi-trash"></i>
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
                                data-status ="{{$u->document_status}}"
                                data-file-url="{{ asset('storage/' . $u->file_path) }}">

                                <td class="checkbox"><input type="checkbox"></td>
                                <td class="sender {{ $u->status === 'delivered' ? 'delivered' : 'viewed' }}">{{ $u->declined_by ?? 'Admin' }}</td>
                                
                                <td class="document-type  {{ $u->status === 'delivered' ? 'delivered' : 'viewed' }}">Declined Document</td>

                                <td class="subject {{ $u->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                    <span class="subject-text">{{ $u->document_name ?? 'No Title' }}</span>
                                    <span class="remark "> - {{ $u->remark ?? 'No remark' }}</span>  
                                </td>

                                <td class="date  {{ $u->status === 'delivered' ? 'delivered' : 'viewed' }}">{{ \Carbon\Carbon::parse($u->declined_date)->format('M d H:i') }}</td>
                                <td class="email-actions">
                                    <a notif-id={{ $u->document_id }} status= 'viewed'
                                        class = "notifDeclined" style="text-decoration: none; color:black;"><i
                                        class="bi bi-arrow-counterclockwise" title="Restore"></i>
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
    <script src="{{ asset('js/notification.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
