@extends('layouts.dean_layout')

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
            <p>
                <div class="deletes btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                        <path
                            d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                    </svg><span>Delete</span>
                </div>
            </p>
            <table class="email-list">
                <th></th>
                <th>Sender</th>
                <th>Type</th>
                <th>Document Name - Message</th>
                <th>Date</th>
                <th>Action</th>
            <tbody>
                @if ($sent->isEmpty() && $forward->isEmpty() && $request->isEmpty())
                    <tr>
                        <td colspan="6" style="text-align: center;">
                            <p class="no-notifications" style="color:red;">You have no Trash at this time.</p>
                        </td>
                    </tr>
                @else
                    @if ($forward)
                        @foreach ($forward as $r)
                            <tr class="email-item {{ $r->status !== 'viewed' ? 'delivered' : '' }}"
                                data-id="{{ $r->forwarded_document_id }}"
                                data-sender="{{ $r->forwardedByEmployee->first_name ?? 'Unknown' }} {{ $r->forwardedByEmployee->last_name ?? '' }}"
                                data-document="{{ $r->document->document_name ?? 'No Title' }}"
                                data-snippet="{{ $r->message ?? 'No message' }}" 
                                data-type="forward"
                                data-file-url="{{ asset('storage/' . $r->document->file_path) }}">

                                <td class="checkbox"><input type="checkbox"></td>
                                <td class="sender {{ $r->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                    {{ $r->forwardedByEmployee->first_name ?? 'Unknown' }}
                                    {{ $r->forwardedByEmployee->last_name ?? '' }}
                                </td>
                                <td>Forwarded Document</td>
                                <td class="subject {{ $r->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                    <span class="subject-text">{{ $r->document->document_name ?? 'No Title' }}</span>
                                    <span class="snippet"> - {{ $r->message ?? 'No message' }}</span>
                                </td>
                                <td class="date">{{ \Carbon\Carbon::parse($r->forwarded_date)->format('M d H:i') }} </td>
                                <td class="email-actions">
                                    <a notif-id={{ $r->forwarded_document_id }} status= 'viewed'
                                        class = "notifForward" style="text-decoration: none; color:black;"><i
                                        class="bi bi-arrow-counterclockwise" title="Restore"></i>
                                    </a>
                                    <a notif-id={{ $r->forwarded_document_id }} status='deleted' class="notifForward"
                                        style="text-decoration: none; color:black;"><i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @if ($sent)
                        @foreach ($sent as $s)
                            <tr class="email-items {{ $s->status === 'delivered' ? 'delivered' : 'viewed' }}"
                                data-id="{{ $s->send_id }}"
                                data-sender="{{ $s->sender->first_name ?? 'Unknown Sender' }} {{ $s->sender->last_name ?? '' }}"
                                data-document="{{ $s->document_subject ?? 'No Title' }}" 
                                data-type="request"
                                data-file-url="{{ asset('storage/' . $s->file_path) }}">
                            
                                <td class="checkbox"><input type="checkbox"></td>
                                <td class="sender {{ $s->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                    {{ $s->sender->first_name ?? 'Unknown Sender' }}
                                    {{ $s->sender->last_name ?? '' }}
                                </td>
                                <td>Sent Document</td>
                                <td class="subject {{ $s->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                    <span class="subject-text">{{ $s->document_subject ?? 'No Title' }}</span>
                                </td>
                            
                                <td class="date">{{ \Carbon\Carbon::parse($s->issued_date)->format('M d H:i') }}</td>
                                <td class="email-actions">
                                    <a notif-id={{ $s->send_id }} status= 'viewed'
                                        class = "notifSent" style="text-decoration: none; color:black;">
                                        <i class="bi bi-arrow-counterclockwise" title="Restore"></i>
                                    </a>
                                    <a notif-id={{ $s->send_id }} status='deleted' class="notifSent"
                                        style="text-decoration: none; color:black;"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                    @if ($request)
                        @foreach ($request as $r)
                        <tr class="requested-docs {{ $r->approval_status === 'Pending' ? 'Pending' : 'Declined' }} {{ $r->status === 'delivered' ? 'bold-row' : '' }}"
                                data-id="{{ $r->request_id }}"
                                data-type="{{ $r->approval_status }}"
                                data-document="{{ $r->document_subject ?? 'No Title' }}"
                                data-status="{{ $r->approval_status }}"
                                data-declined-by="{{ $r->declined_by ?? 'Admin' }}"
                                data-remarks="{{ $r->remarks ?? '' }}"
                                data-request-purpose ="{{ $r->request_purpose ?? 'No Purpose Provided' }}">
                                
                                <td class="checkbox">
                                    <input type="checkbox">
                                </td>
                                <td class="sender {{ $r->status === 'delivered' ? 'delivered' : 'viewed' }}">
                                    {{ $r->declined_by ?? 'Unknown Sender' }}
            
                                </td>
                                <td class="document-type {{ $r->approval_status === 'Pending' ? 'Pending' : 'Approved' }}">
                                    Declined Document
                                </td>
                                <td class="subject {{ $r->approval_status === 'Pending' ? 'Pending' : 'Approved' }}" style="display: flex; align-items: center;">
                                    <span class="subject-text">{{ $r->document_subject ?? 'No Title' }}</span>

                                    @if($r->approval_status === 'Approved')
                                        <span class="request-purpose">
                                        - {{ $r->request_purpose ?? 'No Purpose Provided' }}
                                        </span>
                                    @endif
                                </td>

                                <td class="date {{ $r->approval_status === 'Pending' ? 'Pending' : 'Approved' }}">
                                    {{ \Carbon\Carbon::parse($r->upload_date)->format('M d H:i') }}
                                </td>
                                <td class="email-actions">
                                    <a notif-id={{ $r->request_id }} status='viewed' class='notifReqDeclined'
                                        style="text-decoration: none; color:black;">
                                        <i class="bi bi-arrow-counterclockwise" title="Restore"></i>
                                    </a>
                                    <a notif-id={{ $r->request_id }} status='deleted'
                                        class="notifReqDeclined" style="text-decoration: none; color: black;">
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
    <script src="{{ asset('js/dean/notification.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
