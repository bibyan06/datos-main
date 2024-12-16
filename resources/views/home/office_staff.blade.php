@extends('layouts.office_staff_layout')

@section('title', 'Office Staff' )

@section('custom-css')
    <link rel="stylesheet" href="{{ asset ('css/os/staff_home.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('main-id','home-section')

@section('content')
        <section class="welcome-section">
            <div class="welcome-message">
                <h2>Welcome back, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</h2>
                <p>A Web-Based Platform for storing and retrieving Digitized Documents for Administrative Services Division Office</p>
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
                        @forelse ($forwarded as $r)
                            <div class="notification-item">
                                <img src="{{ asset('images/boy-1.png') }}" alt="Profile Icon" class="profile-icon-notif">
                                <div class="notification-content-item">
                                    <div class="content-wrapper">
                                        <span class="document-title">
                                            {{ $r->document->document_name ?? 'No Title' }}
                                        </span>
                                        <span class="notification-type">
                                            ({{ $r->type }})
                                        </span>
                                        <i class="date">
                                            {{ \Carbon\Carbon::parse($r->forwarded_date)->format('M d, H:i') }}
                                        </i>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @forelse ($declined as $r)
                            <div class="notification-item">
                                <img src="{{ asset('images/boy-1.png') }}" alt="Profile Icon" class="profile-icon-notif">
                                    <div class="notification-content-item">
                                        <div class="content-wrapper">
                                            <span class="document-title">
                                                {{ $r->document_name ?? 'No Title' }}
                                            </span>
                                            <span class="notification-type">
                                                ({{ $r->type }})
                                            </span>
                                            <i class="date">
                                                {{ \Carbon\Carbon::parse($r->declined_date)->format('M d, H:i') }}
                                            </i>
                                        </div>
                                    </div>
                                </div>
                        @empty
                        <div class="notification-item">
                        <div class="notification-content-item">
                            <span class="document-title" style="color: red;">No Notifications Available</span>
                        </div>
                    </div>
                @endforelse
            @endforelse
        </div>
    </div>
</div>
</section>

     <!-- Recent Documents -->
     <section class="dashboard-section">
            <div class="dashboard-container">
                <div class="title-content">
                    <h3>Digitized Documents</h3>
                    <div class="date-time">
                        <i class="bi bi-calendar2-week-fill"></i>
                        <p id="current-date-time"></p>
                    </div>
                </div>
                <div class="documents" id="documents-list">
                    @forelse($documents as $document)
                    <div class="document" data-id="{{ $document->document_id }}" data-name="{{ $document->document_name }}">                            
                        <div class="file-container">
                            <div class="document-card">
                                <div id="pdf-preview-container" style="width: 100%; height: 500px; overflow: hidden;">
                                    <canvas id="pdf-preview-{{ $document->document_id }}" style="width: 100%; height: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                            <div class="document-description">
                                <div class="row">
                                    <div class="column-left">
                                        <h3>{{ $document->document_name }}</h3>
                                    </div>
                                    <input type="text" hidden
                                        value="{{ \Carbon\Carbon::parse($document->updated_date)->format('F') }}">

                                    <div class="column-right">
                                        <a href="#" class="dropdown-toggle"><i class="bi bi-three-dots-vertical"></i></a>
                                        <div class="dropdown-more">
                                            <a href="{{ route('office_staff.documents.os_view_docs', $document->document_id) }}"
                                                class="view-btn">View</a>
                                            <a href="{{ route('document.serve', basename($document->file_path)) }}"
                                                download>Download</a>
                                            <a href="{{ route('office_staff.documents.edit_docs', $document->document_id) }}">Edit</a>
                                            <a href="#" class="forward-btn" data-document-id="{{ $document->document_id }}">Forward</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="other-details">
                                     <p>{{ $document->description }}</p>
                                    <p> Date Uploaded:{{ \Carbon\Carbon::parse($document->upload_date)->format('F d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p id="hidden">No documents available at the moment.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
@endsection

@section('custom-js')
    <script src="{{ asset('js/os/staff_home.js') }}"></script>
    <script>
         const adminDashboardUrl = "{{ route('office_staff.os_dashboard') }}";
         const adminUploadUrl = "{{ route('office_staff.os_upload_document') }}";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script>
        @foreach ($documents as $document)
        (function() {
            var url = "{{ route('document.serve', basename($document->file_path)) }}";
            var canvas = document.getElementById('pdf-preview-{{ $document->document_id }}');

            if (canvas) {
                pdfjsLib.getDocument(url).promise.then(function(pdf) {
                    pdf.getPage(1).then(function(page) {
                        var scale = 1.5; // Adjust this scale factor if needed
                        var viewport = page.getViewport({ scale: scale });

                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        var context = canvas.getContext('2d');
                        page.render({
                            canvasContext: context,
                            viewport: viewport
                        });
                    });
                }).catch(function(error) {
                    console.error("Error loading PDF:", error);
                });
            }
        })();
        @endforeach
    </script>
@endsection

</body>
</html>