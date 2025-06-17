<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
    }

    /* Navbar */
    .navbar {
        position: fixed;
        width: 100%;
        padding: 1rem 5%;
        background-color: #f8f9fa;
        /* Menambahkan background untuk navbar */
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 1000;
    }

    /* Logo */
    .navbar-brand img {
        transition: 0.3s ease-in-out;
    }

    .navbar-brand img:hover {
        transform: scale(1.1);
    }

    /* Navbar Links */
    .navbar-nav {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .navbar-nav .nav-link {
        font-size: 1.4rem;
        color: #333;
        /* Mengubah warna teks menjadi hitam */
        font-weight: 500;
        transition: 0.3s ease-in-out;
        border-bottom: 2px solid transparent;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
        color: #000;
        /* Mengubah warna hover menjadi hitam */
        border-bottom: 2px solid #28a745;
        /* Mengubah warna bawah menjadi hijau */
    }

    /* RFC-App Button */
    .btn-rfc {
        padding: 8px 16px;
        background-color: #28a745;
        border-radius: 25px;
        font-size: 1.4rem;
        color: rgb(255, 255, 255);
        font-weight: 600;
        border: 2px solid #28a745;
        transition: 0.3s ease-in-out;
    }

    .btn-rfc:hover {
        background-color: #000;
        color: #fff;
    }

    .btn-rfc:hover {
        background-color: #28a745;
        color: white;
        /* Tetap hitam */
        box-shadow: 0 0 15px #28a745;
    }

    .btn-darkmode {
        width: 50px;
        /* Lebar tombol */
        height: 50px;
        /* Tinggi tombol */
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #28a745;
        border-radius: 50%;
        /* Membuat tombol bulat */
        font-size: 1.4rem;
        color: #b74b4b;
        font-weight: 600;
        border: 2px solid #28a745;
        transition: 0.3s ease-in-out;
        cursor: pointer;
    }

    /* Hover Effect */
    .btn-darkmode:hover {
        background-color: #28a745;
        color: black;
        box-shadow: 0 0 15px #28a745;
    }

    /* Responsive Navbar */
    @media (max-width: 992px) {
        .navbar {
            padding: 1rem 3%;
        }

        .navbar-nav {
            gap: 10px;
            text-align: center;
        }

        .btn-rfc {
            font-size: 1.2rem;
            padding: 6px 14px;
        }
    }

    .darkmode {
        background-color: #000 !important;
        color: #fff !important;
    }

    .darkmode a,
    .darkmode p,
    .darkmode h1,
    .darkmode h2,
    .darkmode h3,
    .darkmode h4,
    .darkmode h5,
    .darkmode h6,
    .darkmode span,
    .darkmode li,
    .darkmode div,
    .darkmode section {
        color: #fff !important;
    }

    .darkmode .navbar {
        background-color: #000 !important;
    }

    .darkmode .navbar-nav .nav-link {
        color: #fff !important;
        border-bottom-color: transparent;
    }

    .darkmode .navbar-nav .nav-link:hover,
    .darkmode .navbar-nav .nav-link.active {
        color: #28a745 !important;
        border-bottom: 2px solid #28a745;
    }

    .darkmode .btn-rfc {
        background-color: #fff !important;
        color: #000 !important;
        border-color: #fff !important;
    }

    .darkmode .btn-rfc:hover {
        background-color: #ccc !important;
        color: #000 !important;
    }

    .darkmode .btn-darkmode {
        background-color: #28a745 !important;
        color: #000 !important;
        border-color: #28a745 !important;
    }

    .darkmode .jumbotron {
        background-color: #000 !important;
        color: #fff !important;
    }


    /* Container Video */
    .video-container {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    /* Video Full Width */
    .video-container video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Overlay untuk Teks */
    .content-overlay {
        position: relative;
        z-index: 1;
        color: white;
        text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 20vh;
        padding: 20px;
    }

    /* Jumbotron Styling */
    .jumbotron {
        position: relative;
        height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-margin {
        margin-bottom: 350px;
    }

    /* Efek hover untuk card */
    .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        border-radius: 15px;
        overflow: hidden;
        background-color: white;
        color: black;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Mode Gelap untuk Card */
    .darkmode .card {
        background-color: #222 !important;
        color: white !important;
        border: 1px solid white;
    }

    .darkmode .card p,
    .darkmode .card h5,
    .darkmode .card h1,
    .darkmode .card h2 {
        color: white !important;
    }

    /* Animasi Slide */
    @keyframes slideRight {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideLeft {
        from {
            opacity: 0;
            transform: translateX(50px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .slide-right {
        animation: slideRight 1s ease-in-out;
    }

    .slide-left {
        animation: slideLeft 1s ease-in-out;
    }

    /* Responsive Grid */
    @media (max-width: 768px) {
        .card {
            margin-bottom: 20px;
        }
    }
</style>
<nav class="navbar navbar-expand-lg p-3">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img class="logoRFC me-2" src="asset/image/logorfc-fotor.png" alt="logorfc" height="50">
            <img class="logoTUS" src="asset/image/logoTUS.png" alt="logotus" height="50">
        </a>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Items -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav text-center">
                <li class="nav-item"><a class="nav-link" href="#about">Tentang Kami</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Projek</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
                <li class="nav-item"><a class="nav-link" href="#mitra">Mitra</a></li>
                <li class="nav-item">
                    <a href="/login" class="btn btn-rfc">Login</a>
                </li>
                <!-- Dark Mode Toggle -->
                <li class="nav-item">
                    <button id="theme-switch" class="btn btn-darkmode">🌙</button>
                </li>
            </ul>
        </div>
    </div>
</nav>