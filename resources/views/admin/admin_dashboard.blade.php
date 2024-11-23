@extends('layouts.admin_layout')

@section('title', 'Admin Account')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/admin_dashboard.css') }}">
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
        <div id="dashboard-section">
            <div class="dashboard-container">
                <div class="dashboard-title">
                    <h2>Overview</h2>
                    <div class="filter-container">
                        <div class="documents-search-bar">
                            <input type="text" id="search-text" class="search-text" placeholder="Search Document">
                            <i class="bi bi-search"></i>
                        </div>
                        <div class="status-filter">
                            <select id="status-filter" class="option-text">
                                <option value="" disabled selected>Select Status</option>
                                <option value="all">All</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="declined">Declined</option>
                            </select>
                        </div>
                        <div class="document-filter">
                            <select id="category-filter">
                                <option value="" disabled selected>Select a Category</option>
                                <option value="all">All</option>
                                <option value="Memorandum">Memorandum</option>
                                <option value="Audited Disbursement Voucher">Audited Disbursement Voucher</option>
                                <option value="Monthly Report Service of Personnel">Monthly Report Service of Personnel</option>
                                <option value="Claim Monitoring Sheet">Claim Monitoring Sheet</option>
                            </select>
                        </div>
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
                            <tr data-category="{{ $document->category_name }}" data-status="{{ $document->document_status }}">
                                <td>{{ $document->document_number }}</td>
                                <td contenteditable="false" class="editable-field" data-id="{{ $document->document_id }}"  data-field="document_name">
                                    {{ $document->document_name }}
                                </td>
                                <td contenteditable="false" class="editable-field" data-id="{{ $document->document_id }}" data-field="description">
                                    {{ $document->description }}
                                </td>
                                <td>{{ $document->category_name }}</td>
                                <td>
                                    <x-status-label :status="$document->document_status" />
                                </td>
                                <td contenteditable="false" class="editable-field" data-id="{{ $document->document_id }}" data-field="remark">
                                    {{ $document->remark }}
                                </td>
                                <td>{{ $document->upload_date }}</td>
                                <td>{{ $document->uploaded_by }}</td>
                                <td>
                                <i class="bi bi-pencil-square approve edit-icon" title="Edit" data-id="{{ $document->document_id }}"></i>
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
    <script>
        // Pass the route URL to JavaScript
        const adminDashboardUrl = @json(route('admin.admin_dashboard'));
        const csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/admin_dashboard.js') }}"></script>
@endsection
