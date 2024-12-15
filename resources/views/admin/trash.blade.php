@extends('layouts.admin_layout')

@section('title', 'Deleted Documents')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
@endsection

@section('main-id', 'dashboard-content')

@section('content')
    <section class="title">
        <div class="title-content">
            <h3>Trash</h3>
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
                    @if ($forwardedDocuments->isEmpty() && $sent->isEmpty())
                        <tr>
                            <td colspan="6" class="no-notifications" style="text-align:center; color:red;">You have no deleted document at this time.</td>
                        </tr>
                    @else
                        @if ($forward)

                            @foreach ($forward as $r)
                            <tr class="email-item" 
                                    data-id="{{ $r->forwarded_document_id }}"
                                    data-sender="{{ $r->forwardedByEmployee->first_name ?? 'Unknown' }} {{ $r->forwardedByEmployee->last_name ?? '' }}"
                                    data-document="{{ $r->document->document_name ?? 'No Title' }}"
                                    data-snippet="{{ $r->message ?? 'No message' }}" 
                                    data-type="forward"
                                    data-file-url="{{ asset('storage/' . $r->document->file_path) }}">

                                    <td class="checkbox"><input type="checkbox"></td>
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
                                                class="bi bi-trash" title="Delete Forever"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        @if($forwardedDocuments)
                            @foreach($forwardedDocuments as $forwarded)
                                <tr class="forwarded-item"
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
                                        <a notif-id={{ $forwarded->forwarded_document_id }} status= 'viewed'
                                            class = "notifForward" style="text-decoration: none; color:black;"><i
                                            class="bi bi-arrow-counterclockwise" title="Restore"></i>
                                        </a>
                                        <a notif-id={{ $forwarded->forwarded_document_id }} status= 'deleted'
                                            class = "notifForward" style="text-decoration: none; color:black;"><i
                                            class="bi bi-trash"title="Delete Forever"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        @if ($sent)
                            @foreach ($sent as $s)
                                <tr class="email-item">
                                    <td class="checkbox"><input type="checkbox"></td>
                                    <td class="sender">{{ $s->sender->first_name . ' ' . $s->sender->last_name }}</td>
                                    <td class="document-type">Sent Document</td>
                                    <td class="subject">
                                        <span class="snippet">{{ $s->document_subject }}
                                        </span>
                                    </td>
                                    <td class="date">{{ \Carbon\Carbon::parse($s->issued_date)->format('M d H:i') }}
                                    </td>
                                    <td class="email-actions">
                                        <a notif-id={{ $s->send_id }} status= 'viewed'
                                            class = "notifSent" style="text-decoration: none; color:black;"><i
                                                class="bi bi-arrow-counterclockwise" title="Restore"></i></a>        
                                        <a delete-id={{ $s->send_id }}  class = "deletesent" status= 'delete'
                                            style="text-decoration: none; color:black;"><i class="bi bi-trash3-fill" title="Delete Forever"></i></a>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        @if ($uploaded)
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
