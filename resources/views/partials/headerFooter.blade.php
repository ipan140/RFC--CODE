<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link href="back/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="back/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="back/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="back/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="back/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="back/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="back/vendor/simple-datatables/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Template Main CSS File -->
    <link href="back/css/style.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
          crossorigin=""/>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
            crossorigin=""></script>
</head>

<body>

    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="" class="logo d-flex align-items-center">
                <img src="{{ asset('assets/img/logorfc-fotor.png') }}" alt="RFC Logo">
                <span class="d-none d-lg-block text-success">RFC-App</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
    
        <div class="search-bar">
            <form class="search-form d-flex align-items-center" method="POST" action="#">
                @csrf
                <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div>
    
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
    
                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle" href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li>
    
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        @php
                            $notifications = [
                                ['type' => 'warning', 'title' => '1 Sensor Malfunction', 'message' => 'Ph Sensor Malfunction', 'time' => '30 min. ago'],
                                ['type' => 'success', 'title' => '6 Sensors Ready to go', 'message' => '', 'time' => '2 hrs. ago']
                            ];
                        @endphp
                        @if (count($notifications) > 0)
                            <span class="badge bg-success badge-number">{{ count($notifications) }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                        <li class="dropdown-header">
                            You have {{ count($notifications) }} new notifications
                            <a href="#"><span class="badge rounded-pill bg-success p-2 ms-2">View all</span></a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach ($notifications as $notif)
                            <li class="notification-item">
                                <i class="bi bi-exclamation-circle text-{{ $notif['type'] }}"></i>
                                <div>
                                    <h4>{{ $notif['title'] }}</h4>
                                    @if (!empty($notif['message']))
                                        <p>{{ $notif['message'] }}</p>
                                    @endif
                                    <p>{{ $notif['time'] }}</p>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        @endforeach
                        <li class="dropdown-footer">
                            <a href="#">Show all notifications</a>
                        </li>
                    </ul>
                </li>
                <!-- End Notifications -->
    
                <!-- Profile -->
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="{{ asset('back/img/' . (Auth::check() && Auth::user()->profile_picture ? Auth::user()->profile_picture : 'default.png')) }}" 
     alt="Profile" class="rounded-circle" width="40">
                            <span class="d-none d-md-inline ms-2">
                                @auth
                                    Welcome, {{ Auth::user()->name }}
                                @endauth
                            </span>
                            
                    </a>
    
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        @if(Auth::check())
                            <li class="dropdown-header text-center">
                                <h6>{{ Auth::user()->name }}</h6>
                                <span>{{ $user->role ?? 'User' }}</span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
    
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">
                                    <i class="bi bi-person"></i>
                                    <span>My Profile</span>
                                </a>
                            </li>
    
                            <li><hr class="dropdown-divider"></li>
    
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <i class="bi bi-question-circle"></i>
                                    <span>Need Help?</span>
                                </a>
                            </li>
    
                            <li><hr class="dropdown-divider"></li>
    
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Sign Out</span>
                                </a>
    
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @else
                            <li class="dropdown-header text-center">
                                <h6>Guest</h6>
                                <span>Not Logged In</span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        @endif
                    </ul>
                </li>
                <!-- End Profile -->
    
            </ul>
        </nav>
    </header>
    
<!-- Content Section -->
@yield('content')

<footer id="footer" class="footer">
    <div class="copyright text-success">
        &copy; Copyright 2023 <strong><span>RFC Team ITTelkom Surabaya</span></strong>. All Rights Reserved
    </div>
</footer>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="back/vendor/apexcharts/apexcharts.min.js"></script>
<script src="back/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="back/vendor/chart.js/chart.umd.js"></script>
<script src="back/vendor/echarts/echarts.min.js"></script>
<script src="back/vendor/quill/quill.min.js"></script>
<script src="back/vendor/simple-datatables/simple-datatables.js"></script>
<script src="back/vendor/tinymce/tinymce.min.js"></script>
<script src="back/js/main.js"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

</body>
</html>
