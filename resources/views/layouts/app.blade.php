<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/dean/dean_page.css') }}">
    <link rel="stylesheet" href="{{ asset ('css/dean/dean_home.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-content">
            <div class="left-header">
                <img src="{{ asset ('images/Bicol_University.png') }}" alt="Bicol University Logo" class="logo">
                <h1>BICOL <span>UNIVERSITY</span></h1>
            </div>
            <div class="search-container">
                <input type="text" id="sidebar-search" placeholder="Search">
                <i class="bi bi-search"></i>
            </div>
            <div class="profile-icon">
                <img src="images/boy-1.png" alt="Profile Icon" id="profile-icon">
            </div>
        </div>
    </header>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-content">
            <div class="logo-container">
                <img src="images/sidebar-logo.png" alt="Bicol University Logo" class="nav-logo">
            </div>
            <ul class="nav-icons">
                <li><div class="icon-container" data-target="#sidebar"><i class="bi bi-house-fill" id="home-icon"></i></div></li>
                <li><div class="icon-container" data-target="#sidebar"><i class="bi bi-file-earmark-fill" id="digitized-icon"></i></div></li>
                <li><div class="icon-container" data-target="#sidebar"><i class="bi bi-hourglass-split" id="pendings"></i></div></li>
                <li><div class="icon-container" data-target="#sidebar"><i class="bi bi-bell-fill" id="notification-icon"></i></div></li>
                <li><div class="icon-container" data-target="#sidebar"><i class="bi bi-search" id="search-icon"></i></div></li>
            </ul>
            <div class="profile-settings">
                <div class="icon-container" data-target="#sidebar"><i class="bi bi-door-open-fill"></i></div>
                <div class="icon-container" data-target="#sidebar"><img src="images/boy-1.png" alt="Profile Icon" class="profile-pic"></div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="extra-sidebar" id="sidebar">
        <div class="sidebar-content">
            <div class="sidebar-title">
                <h3>DASHBOARD</h3>
                <i class="bi bi-text-right"></i>
            </div>
            <ul>
                <li><a href="dean_page.html" id="home">Home</a></li>
                <li>
                    <a href="#" class="dropdown-toggle" id="digitized">Digitized Documents <i class="bi bi-chevron-right"></i></a>
                    <ul class="more-dropdown-menu">
                        <li><a href="dean_memorandum.html" id="memorandum">Memorandum</a></li>
                        <li><a href="dean_dean_orders.html" id="dean_order">Administrative Order</a></li>
                        <li><a href="dean_mrsp.html" id="mrsp">Monthly Report Service Personnel</a></li>
                        <li><a href="dean_cms.html" id="cms">Claim Monitoring Sheet</a></li>
                        <li><a href="dean_audit.html" id="audit">Audited Documents</a></li>
                    </ul>
                </li>
            </ul>
            <ul>
                <li><a href="dean_request.html" id="request">Request a Document</a></li>
                <li><a href="dean_notification.html" id="announcements-icon">Notifications</a></li>
                <li><a href="dean_all_documents.html" id="search">Search</a></li>
            </ul>
            <div class="profile-content">
                <ul>
                    <li><a href="sign-up.html">Logout</a></li>
                    <li><a href="dean_account.html" id="account">Profile</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; DATOS 2024 Bicol University. All Rights Reserved.</p>
        </div>
    </footer>
    <script src="{{ asset('js/prompt.js') }}"></script>
</body>
</html>
