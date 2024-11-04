@extends('layouts.admin_layout')

@section('title', 'Admin Account' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/admin_dashboard.css') }}">
@endsection

@section('main-id','dashboard-content')

@section('content') 
        <div class="reports-container">
            <div class="report">
                <h1>{{ $totalDocuments }}</h1>
                <h4>Total Documents</h4>
            </div>
            <div class="report">
                <h1>{{ $totalEmployees }}</h1>
                <h4>Total Employees</h4>
            </div>
        </div>

        <main id="dashboard-content">
        <!-- <div class="reports-container">
            <div class="report">
                <h1>{{ $claimMonitoringSheetCount }}</h1>
                <h4>Claim Monitoring Sheet</h4>
            </div>
            <div class="report">
                <h1>{{ $memorandumCount }}</h1>
                <h4>Memorandum</h4>
            </div>
            <div class="report">
                <h1>{{ $mrspCount }}</h1>
                <h4>MRSP</h4>
            </div>
            <div class="report">
                <h1>{{ $auditedDVCount }}</h1>
                <h4>Audited Disbursement Voucher</h4>
            </div>
        </div> -->
    
        <div id="dashboard-section">
            <div class="dashboard-container">
                <div class="dashboard-title">
                    <h2>Document Report</h2>
                    <div class="search">
                        <select id="category-filter">
                            <option value="" disabled selected>Select Document</option>
                            <option value="" selected>All</option>
                            <option value="Memorandum">Memorandum</option>
                            <option value="Audited Disbursement Voucher">Audited Disbursement Voucher</option>
                            <option value="Monthly Report Service of Personnel">Monthly Report Service of Personnel</option>
                            <option value="Claim Monitoring Sheet">Claim Monitoring Sheet</option>
                        </select>
                    </div>
                </div>
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
                    <tbody id="documents-table">
                        @foreach($documents as $document)
                            <tr data-category="{{ $document->category_name }}">
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
                                <td>
                                <a href="{{ route('admin.documents.view_docs', $document->document_id) }}" title="View Document">
                                    <i class="bi bi-eye"></i>
                                </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($documents->isEmpty())
                    <p>No documents available.</p>
                @endif
        </div>
    </div>
</main>
@endsection


@section('custom-js')
    <script src="{{ asset ('js/admin_dashboard.js') }}"></script>
@endsection

</body>
</html>
