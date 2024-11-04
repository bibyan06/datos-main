@extends('layouts.admin_layout')

@section('title', 'Admin' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/home.css') }}">
@endsection

@section('main-id','home-section')

@section('content')   
        <section class="welcome-section">
            <div class="welcome-message">
                <h2>Welcome back, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</h2>
                <p>Streamlining document management and access for all Administrative Service Division Office personnel.</p>
            </div>
        </section>

        <section class="shortcuts">
            <div class="container square" id="documents-shortcut">
                <img src="{{ asset ('images/document-logo.png')}}" alt="Documents Logo">
                <p>See Documents Here</p>
            </div>
            <div class="container square" id="upload-shortcut">
                <img src="{{ asset ('images/upload-logo.png') }}" alt="Upload Logo">
                <p>Upload Documents Here</p>
            </div>
            <div class="container rectangle" id="notifications">
                <h4>Notifications</h4>
                <div class="notification-content">
                    <div class="notification-list">
                        <div class="notification-item">
                            <img src="{{ asset ('images/boy-2.png')}}" alt="Profile Icon" class="profile-icon-notif">
                            <div class="notification-content-item">
                                <span class="sender-name">DATOS</span>
                                <span class="document-title">New Memorandum Available</span>
                            </div>
                            <i class="bi bi-envelope-fill mail-icon"></i>
                        </div>
                        <div class="notification-item">
                            <img src="{{ asset ('images/girl-1.png') }}" alt="Profile Icon" class="profile-icon-notif">
                            <div class="notification-content-item">
                                <span class="sender-name">DATOS</span>
                                <span class="document-title">Audited Disbursement Voucher</span>
                            </div>
                            <i class="bi bi-envelope-fill mail-icon"></i>
                        </div>
                        <div class="notification-item">
                            <img src="images/boy-2.png" alt="Profile Icon" class="profile-icon-notif">
                            <div class="notification-content-item">
                                <span class="sender-name">DATOS</span>
                                <span class="document-title">Claim Monitoring Sheet</span>
                            </div>
                            <i class="bi bi-envelope-fill mail-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="title">
            <div class="title-content">
                <h3>Recent Digitized Documents</h3>
                <div class="date-time">
                    <i class="bi bi-calendar2-week-fill"></i>
                    <p id="current-date-time"></p>
                </div>
            </div>
        </section>

        <!-- Recent Documents -->
        <section class="dashboard-overview">
            @if($documents->isEmpty())
                <p>No approved documents available.</p>
            @else
                @foreach($documents as $document)
                <div class="documents-content">
                    <div class="document-card">
                        <!-- Use iframe to display the PDF -->
                        <iframe src="{{ route('document.serve', basename($document->file_path)) }}#toolbar=0" width="100%" frameborder="0"></iframe>
                        <div class="content">
                            <div class="row">
                                <div class="column left">
                                    <h3>{{ $document->document_name }}</h3>
                                    <p>{{ Str::limit($document->description, 100) }}</p>
                                </div>
                                <div class="column right">
                                    <a href="#" class="dropdown-toggle"><i class="bi bi-three-dots-vertical" style="cursor: pointer;"></i></a>
                                    <div class="dropdown-more">
                                        <a href="{{ route('admin.documents.view_docs', $document->document_id) }}" class="view-btn">View</a>
                                        <a href="{{ route('document.serve', basename($document->file_path)) }}" download>Download</a>
                                        <a href="{{ route('admin.documents.edit_docs', $document->document_id) }}">Edit</a>
                                        <a href="#" class="forward-btn" data-document-id="{{ $document->document_id }}">Forward</a>                           
                                        <a data-id="{{$document->document_id}}" class="forward-btns archive" style="cursor: pointer">Archive</a>
                                    </div>
                                </div>
                            </div>
                            <div class="upload-date">
                                <p>Date Uploaded: {{ \Carbon\Carbon::parse($document->upload_date)->format('F j, Y') }}</p>
                            </div>
                        </div>
                    </div>  
                </div>
                @endforeach
            @endif
        </section>     
    </main>
@endsection

@section('custom-js')
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endsection

</body>
</html>