@extends('layouts.admin_layout')

@section('title', 'Approved Documents' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/approved.css') }}">
@endsection

@section('main-id','dashboard-content')

@section('content') 

    <main id="dashboard-content">
        <section class="title">
            <div class="title-content">
                <h3>Approved Documents</h3>
                <div class="date-time">
                    <i class="bi bi-calendar2-week-fill"></i>
                    <p id="current-date-time"></p>
                </div>
            </div>
        </section>

        <div id="dashboard-section">
            <div class="dashboard-container">
            <table class="table">
                <thead>
                    <tr>
                        <!-- <th>Document Number</th> -->
                        <th>Document Name</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date Uploaded</th>
                        <th>Uploaded by</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $document)
                        <tr>
                            <!-- <td>{{ $document->document_number }}</td> -->
                            <td>{{ $document->document_name }}</td>
                            <td>{{ $document->description }}</td>
                            <td>{{ $document->category_name }}</td>
                            <td>
                                <x-status-label :status="$document->document_status" />
                            </td>
                            <td>{{ $document->upload_date }}</td>
                            <td>{{ $document->uploaded_by }}</td>
                            <td class="review-icon"><i class="bi bi-arrow-clockwise return-to-pending" title="Return" data-id="{{ $document->document_id }}"></i></i>
                                <a href="{{ route('admin.documents.view_docs', $document->document_id) }}" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($documents->isEmpty())
                <p>No approved documents available.</p>
            @endif
            </div>
        </div>
    </main>
@endsection

@section('custom-js')
    <script>
        // Pass the route and CSRF token to JavaScript
        const returnToPendingUrl = "{{ route('admin.documents.review_docs') }}";
        const csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/approved.js') }}"></script>
@endsection



</body>
</html>
