@extends('layouts.admin_layout')

@section('title', 'Requested Documents' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/approved.css') }}">
@endsection

@section('main-id','dashboard-content')

@section('content') 
            <section class="title">
                <div class="title-content">
                    <h3>Requested Documents</h3>
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
                                <th>Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($documents as $document)
                                @if($document->document_status == 'Pending')
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
                                        <td>
                                        <form action="{{ route('admin.documents.approve', $document->document_id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success approve-btn">Approve</button>
                                        </form>
                                        <button type="button" class="btn btn-danger decline-btn" data-document-id="{{ $document->document_id }}">Decline</button>                                        
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.documents.view_docs', $document->document_id) }}" title="View Document">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                    </tr>
                                @endif
                            @endforeach --}}
                        </tbody>
                    </table>
                    {{-- @if($documents->isEmpty())
                        <p>No documents available for review.</p>
                    @endif --}}
                </div>
            </div>
        </main>
@endsection
   
@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
@endsection


</body>
</html>
