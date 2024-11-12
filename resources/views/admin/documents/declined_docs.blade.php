@extends('layouts.admin_layout')

@section('title', 'Declined Documents' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/approved.css') }}">
@endsection

@section('main-id','dashboard-contents')

@section('content') 
        <section class="title">
            <div class="title-content">
                <h3>Declined Documents</h3>
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
                        <th>Document Number</th>
                        <th>Document Name</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Remark</th>
                        <th>Date Uploaded</th>
                        <th>Uploaded by</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $document)
                            <td>{{ $document->document_number }}</td>
                            <td>{{ $document->document_name }}</td>
                            <td>{{ $document->description }}</td>
                            <td>{{ $document->category_name }}</td>
                            <td>
                                <x-status-label :status="$document->document_status" />
                            </td>
                            <td>{{ $document->remark}}</td>
                            <td>{{ $document->upload_date }}</td>
                            <td>{{ $document->uploaded_by }}</td>
                            <td class="review-icon">
                                <a href="{{ route('admin.documents.view_docs', $document->document_id) }}" title="View Document">
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
    <script src="{{ asset('js/approved.js') }}"></script>
@endsection

</body>
</html>
