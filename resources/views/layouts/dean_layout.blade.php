<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/dean/dean_page.css') }}">

    @yield('custom-css')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
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
                <img src="{{ asset('images/boy-1.png') }}" alt="Profile Icon" id="profile-icon">
                <div class="profile-dropdown" id="profile-dropdown">
                    <a href="{{ route('dean.dean_account') }}"><i class="bi bi-person-circle" id="account-icon">
                        </i>Account</a>
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-left" id="logout-icon"></i> Logout
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
                <li>
                    <div class="icon-container" data-target="#sidebar"><i class="bi bi-house-fill" id="home-icon"></i>
                    </div>
                </li>
                <li>
                    <div class="icon-container" data-target="#sidebar"><i class="bi bi-table" id="digitized-icon"></i></div>
                </li>
                <li>
                    <div class="icon-container" data-target="#sidebar"><i class="bi bi-folder-fill" id="pendings"></i></div>
                </li>
                <li>
                    <div class="icon-container" data-target="#sidebar"><i class="bi bi-folder-symlink-fill" id=""></i></div>
                </li>
                <li>
                    <div class="icon-container" data-target="#sidebar">
                        <i class="bi bi-bell-fill" id="notification-icon"></i>
                        {{-- @if (isset($notificationCount) && $notificationCount > 0) --}}
                            <span class="badge badge-pill badge-danger"
                                id="notification-counts"></span>
                        {{-- @endif --}}
                    </div>
                </li>

                <li>
                    <div class="icon-container" data-target="#sidebar"><i class="bi bi-search" id="search-icon"></i>
                    </div>
                </li>
                <li><div class="icon-container" data-target="#sidebar"><i class="bi bi-archive-fill" id="archive"></i></div></li>
                <li><div class="icon-container" data-target="#sidebar"><i class="bi bi-trash3-fill" id="trash"></i></div></li>
            </ul>
            <div class="profile-settings">
                <div class="icon-container" data-target="#sidebar"><i class="bi bi-door-open-fill"></i></div>
                <div class="icon-container" data-target="#sidebar"><img src="{{ asset('images/boy-1.png') }}"
                        alt="Profile Icon" class="profile-pic"></div>
            </div>
        </div>
    </nav>

    <div class="extra-sidebar" id="sidebar">
        <div class="sidebar-content">
            <div class="sidebar-title">
                <h3>DASHBOARD</h3>
                <i class="bi bi-text-right"></i>
            </div>
            <ul>
                <li><a href="{{ route('home.dean') }}" id="home">Home</a></li>

                <li>
                    <a href="#" class="dropdown-toggle" id="digitized">Digitized Documents <i
                            class="bi bi-chevron-right"></i></a>

                    <ul class="more-dropdown-menu">
                        <li><a href="{{ route('dean.documents.memorandum') }}" id="memorandum">Memorandum</a></li>
                        <!-- <li><a href="{{ route('dean.documents.admin_order') }}" id="admin_order">Administrative Order</a></li>
                        <li><a href="{{ route('dean.documents.mrsp') }}" id="mrsp">Monthly Report Service
                                Personnel</a></li>
                        <li><a href="{{ route('dean.documents.cms') }}" id="cms">Claim Monitoring Sheet</a></li>
                        <li><a href="{{ route('dean.documents.audited_dv') }}" id="audit">Audited Documents</a> -->
                        </li>
                    
                    </ul>

                </li>

            </ul>
            <ul>
                <li><a href="{{ route('dean.documents.dean_request') }}" id="request">Request a Document</a></li>
                <li><a href="{{route ('dean.documents.dean_requested_docs')}}" id="requested">Requested Document</a></li>

                <li><a href="{{ route('dean.documents.dean_notification') }}"
                        id="announcements-icon">Notifications</a></li>

                <li><a href="{{ route('dean.documents.dean_search') }}" id="search">Search</a></li>
                <li><a href="{{ route ('dean.dean_archive') }}" id="archive">Archive</a></li>
                <li><a href="{{ route ('dean.dean_trash') }}" id="trash">Trash</a></li>
            </ul>
            <div class="profile-content">
                <ul>
                    <li><a href="{{ route('logout') }}">Logout</a></li>
                    <li><a href="{{ route('dean.dean_account') }}" id="account">Profile</a></li>
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

    <script src="{{ asset('js/dean/dean_page.js') }}"></script>
    <script src="{{ asset('js/prompt.js') }}"></script>
    @yield('custom-js')
</body>

</html>
