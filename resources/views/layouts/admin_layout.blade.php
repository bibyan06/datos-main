<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @yield('custom-css')

    <link rel="stylesheet" href="{{ asset('css/admin_page.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css\bootstrap.min.css')}}"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <img src="{{ asset('images/Bicol_University.png') }}" alt="Bicol University Logo" class="logo">
                <h1>BICOL <span>UNIVERSITY</span></h1>
            </div>
            <div class="search-container">
                <input type="text" id="sidebar-search" placeholder="Search">
                <i class="bi bi-search"></i>
            </div>
            <div class="profile-icon" id="profile-icon-container">
                <h2>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                <img src="{{ asset('images/girl-1.png') }}" alt="Profile Icon" id="profile-icon">
                <div class="profile-dropdown" id="profile-dropdown">
                    <a href="{{ route('admin.admin_account') }}"><i class="bi bi-person-circle" id="account-icon"></i> Account</a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-left logout" id="logout-icon"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </header>

    <nav class="navbar">
        <div class="navbar-content">
            <div class="logo-container">
                <img src="{{ asset('images/sidebar-logo.png') }}" alt="Bicol University Logo" class="nav-logo">
            </div>
            <ul class="nav-icons">
                <li><div class="icon-container" data-target="#sidebar"><i class="bi bi-house-fill" id="home-icon" title="Home"></i></div></li>
                <li><div class="icon-container" data-target="#sidebar"><i class="bi bi-grid-1x2-fill"  id="dashboard-icon" title="Overview"></i></div></li>
                <li><div class="icon-container" data-target="#sidebar"><i class="bi bi-table" id="digitized-icon" title="Digitized Documents"></i></div> </li>
                <li><div class="icon-container" data-target="#sidebar"><i class="bi bi-people-fill"  id="employees-icon" title="Employee"></i></div></li>
                <li>
                    <div class="icon-container" data-target="#sidebar" title="Review">
                        <i class="bi bi-hourglass-split" id="pendings"></i>
                        @if (isset($pendingCount) && $pendingCount > 0)
                            <span class="badge badge-pill badge-danger" id="pending-count">{{ $pendingCount }}</span>
                        @endif
                    </div>
                </li>

                <li>
                    <div class="icon-container" data-target="#sidebar"><i class="bi bi-send-check-fill"
                            id="sent" title="Sent"></i></div>
                </li>
                <li>
                    <div class="icon-container" data-target="#sidebar" title="Notifications">
                        <i class="bi bi-bell-fill" id="notification-count"></i>
                        {{-- @if (isset($notificationCount) && $notificationCount > 0) --}}
                            <span class="badge badge-pill badge-danger"
                                id="notification-counts"></span>
                        {{-- @endif --}}
                    </div>
                </li>
                <li>
                    <div class="icon-container" data-target="#sidebar"><i class="bi bi-cloud-arrow-up-fill"
                            id="upload-icon" title="Upload"></i></div>
                </li>
                <li>
                    <div class="icon-container" data-target="#sidebar"><i class="bi bi-search" id="search-icon" title="Search"></i>
                    </div>
                </li>
                <li><div class="icon-container" data-target="#sidebar"><i class="bi bi-archive-fill" id="archive" title="Archive"></i></div></li>
                <li><div class="icon-container" data-target="#sidebar"><i class="bi bi-trash3-fill" id="trash" title="Trash"></i></div></li>
            </ul>
            <div class="profile-settings">
                <div class="profile-settings">
                    <div class="icon-container" data-target="#sidebar"><i class="bi bi-door-open-fill" title="Logout"></i></div>
                    <div class="icon-container" data-target="#sidebar"><img src="{{ asset('images/girl-1.png') }}"
                            alt="Profile Icon" class="profile-pic" title="Account"></div>
                </div>

            </div>
        </div>
    </nav>

    <div class="extra-sidebar" id="sidebar">
        <div class="sidebar-content">
            <div class="sidebar-title">
                <h3>DASHBOARD</h3>
                <i class="bi bi-text-right"></i>
            </div>
            <!--
                <div class="search-container">
                    <input type="text" id="sidebar-search" placeholder="Search">
                    <i class="bi bi-search"></i>
                </div>
                -->
            <ul>
                <li><a href="{{ route('home.admin') }}" id="home">Home</a></li>

                <li><a href="{{ route('admin.admin_dashboard') }}" id="report">Overview</a></li>
                <li>
                    <a href="#" class="dropdown-toggle" id="digitized">Digitized Documents <i
                            class="bi bi-chevron-right"></i></a>
                    <ul class="more-dropdown-menu">
                        <li><a href="{{ route('admin.documents.memorandum') }}" id="memorandum"> Memorandum</a></li>
                        <!-- <li><a href="{{ route('admin.documents.admin_order') }}" id="admin_order">Administrative Order</a></li> -->
                        <li><a href="{{ route('admin.documents.mrsp') }}" id="mrsp"> Monthly Report Service
                                Personnel</a></li>
                        <li><a href="{{ route('admin.documents.cms') }}" id="cms"> Claim Monitoring Sheet</a>
                        </li>
                        <li><a href="{{ route('admin.documents.audited_dv') }}"> Audited Documents</a></li>
                    </ul>
                </li>
            </ul>

            <ul>
                <li>
                    <a href="#" class="emp-dropdown-toggle" id="digitized">Employee <i
                            class="bi bi-chevron-right"></i></a>
                    <ul class="emp-dropdown-menu">
                        <li><a href="{{ route('admin.office_staff') }}" id="memorandum"> Office Staff</a></li>
                        <li><a href="{{ route('admin.college_dean') }}" id="admin_order">College Dean</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="pending-dropdown-toggle" id="digitized">Review <i
                            class="bi bi-chevron-right"></i></a>
                    <ul class="pending-dropdown">
                        <li><a href="{{ route('admin.documents.approved_docs') }}" id="approval">Approved</a></li>
                        <li><a href="{{ route('admin.documents.declined_docs') }}" id="decline">Declined</a></li>
                        <li>
                            <a href="{{ route('admin.documents.review_docs') }}" id="request">
                                Pendings
                                @if(isset($documentPendingCount) && $documentPendingCount > 0)
                                    <span class="badge badge-pill badge-danger" id="pending-counts">{{ $documentPendingCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.documents.request_docs') }}" id="request">
                                Request
                                @if(isset($requestPendingCount) && $requestPendingCount > 0)
                                    <span class="badge badge-pill badge-danger" id="pending-counts">{{ $requestPendingCount }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

                <li><a href="{{ route('admin.documents.sent_docs') }}" id="sent"> Sent </a></li>
                <li><a href="{{ route('admin.admin_notification') }}" id="announcements-icon"> Notifications</a>
                </li>
                <li><a href="{{ route('admin.admin_upload_document') }}" id="upload">Upload</a></li>
                <li><a href="{{ route('admin.admin_search') }}" id="search">Search</a></li>
                <li><a href="#" class="archives-dropdown-toggle" id="announcements-icon">Archive <i class="bi bi-chevron-right"></i></a>
                    <ul class="archives-dropdown-menu">
                        <li><a href="{{ route ('admin.archive_notif') }}" id="notification_archived"> Notification</a></li>
                        <li><a href="{{ route ('admin.archive_docs') }}" id="notification_archived"> Document</a></li>
                    </ul>
                </li>
                <li><a href="{{ route ('admin.trash') }}" id="trash">Trash</a></li>
            </ul>
            <div class="profile-content">
                <ul>
                    <li><a href="{{ route('logout') }}"> Logout</a></li>
                    <li><a href="{{ route('admin.admin_account') }}" id="account">Profile</a></li>
                </ul>
            </div>
        </div>
    </div>

    <main id="@yield('main-id', 'default-main')">
        <!-- Section for blade-specific content -->
        @yield('content')
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; DATOS Bicol University. All Rights Reserved 2025.</p>
        </div>
    </footer>

    <script src="{{ asset('js/admin_page.js') }}"></script>
    <script src="{{ asset('js/prompt.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('custom-js')
</body>

</html>
