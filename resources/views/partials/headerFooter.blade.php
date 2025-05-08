<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="RFC App Dashboard">
    <meta name="keywords" content="dashboard, sensor, agriculture">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - RFC App</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&family=Nunito:wght@300;400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vendor & Template CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('back/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('back/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('back/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('back/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('back/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('back/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('back/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="{{ asset('back/css/style.css') }}" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous">

    <!-- Tambahan untuk atur jarak dari header -->
    <!-- <style>
        main#main {
            margin-top: 70px; /* Sesuaikan jika header lebih tinggi atau rendah */
        }
    </style> -->
</head>

<body>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a href="#" class="logo d-flex align-items-center">
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
                    <a class="nav-link nav-icon search-bar-toggle" href="#"><i class="bi bi-search"></i></a>
                </li>

                {{-- Notifications --}}
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon" href="{{ route('logaktivitas.index') }}">
                        <i class="bi bi-bell"></i>
                        @php
                        $notifications = [
                        ['type' => 'warning', 'title' => '1 Sensor Malfunction', 'message' => 'Ph Sensor Malfunction', 'time' => '30 min. ago'],
                        ['type' => 'success', 'title' => '6 Sensors Ready to go', 'message' => '', 'time' => '2 hrs. ago']
                        ];
                        @endphp
                        @if (count($notifications))
                        <span class="badge bg-success badge-number">{{ count($notifications) }}</span>
                        @endif
                    </a>
                </li>

                {{-- Profile --}}
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->profile_picture ? asset('storage/profile_pictures/' . Auth::user()->profile_picture) : asset('back/img/default.png') }}" alt="Profile" class="rounded-circle" width="40">

                        <span class="d-none d-md-inline ms-2">Welcome, {{ Auth::user()->name ?? 'Guest' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        @auth
                        <li class="dropdown-header text-center">
                            <h6>{{ Auth::user()->name }}</h6>
                            <span>{{ Auth::user()->role }}</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person"></i> <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <i class="bi bi-question-circle"></i> <span>Need Help?</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <!-- <li>
                                <a class="dropdown-item d-flex align-items-center" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> <span>Sign Out</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </li>
                        @else
                            <li class="dropdown-header text-center">
                                <h6>Guest</h6>
                                <span>Not Logged In</span>
                            </li>
                        @endauth -->
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    {{-- Content --}}
    <main id="main" class="main">
        @yield('content')
    </main>

    <footer id="footer" class="footer">
        <div class="copyright text-success">
            &copy; Copyright 2023 <strong><span>RFC Team Telkom University Surabaya</span></strong>. All Rights Reserved
        </div>
    </footer>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('back/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('back/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('back/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('back/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('back/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('back/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('back/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('back/js/main.js') }}"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="anonymous"></script>
</body>

</html>