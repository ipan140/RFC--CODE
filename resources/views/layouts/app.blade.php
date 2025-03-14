<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="back/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="back/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="back/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="back/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="back/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="back/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="back/vendor/simple-datatables/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Template Main CSS File -->
    <link href="back/css/style.css" rel="stylesheet">
    <!-- leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="crossorigin=""/>
</head>
<body>

@include('partials.headerFooter')
@include('partials.sidebar')

<div class="main-content">
    @yield('content')
</div>

<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
