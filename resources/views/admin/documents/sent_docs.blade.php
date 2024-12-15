@extends('layouts.admin_layout')

@section('title', 'Sent and Forwarded Documents')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

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
            @if ($documents->isEmpty() && $documents->isEmpty())
                <p class="no-notifications">You have no sent or forwarded documents at this time.</p>
            @else
                <table class="email-list" id="myTable">
                <thead>
                    <tr>
                        <th></th>
                        <th>Type</th>
                        <th>Receiver</th>
                        <th>Document Name</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Forwarded Documents -->
                    @foreach ($documents as $forwarded)
                        <tr class="email-item" data-file-url="{{ asset('storage/'.$forwarded['file_path']) }}"
                            data-status="{{ $forwarded['status'] }}"
                            data-message="{{ $forwarded['message'] ?? 'No message' }}"
                            data-document-name="{{ $forwarded['document_name'] ?? 'Unknown Document' }}">

                            <td class="checkbox"><input type="checkbox"></td>
                            <td class="sender">{{ $forwarded['type'] }} Document to:</td>
                            <td class="document-type">
                                <span class="receiver">
                                    {{ $forwarded['receiver_name'] ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="document-name">
                                {{ $forwarded['document_name'] ?? 'Unknown Document' }} -
                                {{ $forwarded['message'] ?? 'No message' }}
                            </td>

                            <td class="date">{{ \Carbon\Carbon::parse($forwarded['date'])->format('M d H:i') }}</td>
                            <td class="email-actions">
                                <a notif-id={{ $forwarded['id']}} status= 'deleted' type="{{$forwarded['type']}}"
                                    class = "{{ "notif".$forwarded['type'] }}" style="text-decoration: none; color:black;"><i
                                        class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('js/admin_notification.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let table = new DataTable('#myTable');
    </script>
@endsection
