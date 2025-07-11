<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        padding-top: 80px;
        /* Untuk menghindari tumpukan karena navbar fixed */
    }

    .navbar {
        position: fixed;
        width: 100%;
        top: 0;
        padding: 1rem 5%;
        background-color: #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 1000;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .navbar-brand img {
        transition: 0.3s ease-in-out;
    }

    .navbar-brand img:hover {
        transform: scale(1.1);
    }

    .navbar-nav {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .navbar-nav .nav-link {
        font-size: 1.4rem;
        color: #333;
        font-weight: 500;
        transition: 0.3s ease-in-out;
        border-bottom: 2px solid transparent;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
        color: #000;
        border-bottom: 2px solid #28a745;
    }

    .btn-rfc {
        padding: 8px 16px;
        background-color: #28a745;
        border-radius: 25px;
        font-size: 1.4rem;
        color: #fff;
        font-weight: 600;
        border: 2px solid #28a745;
        transition: 0.3s ease-in-out;
    }

    .btn-rfc:hover {
        background-color: #28a745;
        color: #fff;
        box-shadow: 0 0 15px #28a745;
    }

    .btn-darkmode {
        width: 50px;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #28a745;
        border-radius: 50%;
        font-size: 1.4rem;
        color: #b74b4b;
        font-weight: 600;
        border: 2px solid #28a745;
        transition: 0.3s ease-in-out;
        cursor: pointer;
    }

    .btn-darkmode:hover {
        background-color: #28a745;
        color: black;
        box-shadow: 0 0 15px #28a745;
    }

    .jumbotron {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 20px 40px;
        min-height: auto;
        text-align: center;
    }

    .content-overlay {
        position: relative;
        z-index: 1;
        color: white;
        text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: auto;
        padding: 20px;
    }

    .video-container {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .video-container video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

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

    /* Responsive Styles */
    @media (max-width: 991.98px) {
        .navbar-nav {
            flex-direction: column;
            gap: 10px;
            margin-top: 1rem;
        }

        .navbar-collapse {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
        }

        .darkmode .navbar-collapse {
            background-color: #111 !important;
        }

        .navbar {
            padding: 1rem 3%;
        }

        .btn-rfc {
            font-size: 1.2rem;
            padding: 6px 14px;
        }

        .btn-darkmode {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }

        .navbar-brand img {
            height: 40px;
        }

        .jumbotron {
            flex-direction: column;
            padding-top: 20px;
            height: auto !important;
        }

        .content-overlay {
            padding-top: 10px;
            text-align: center;
            height: auto;
        }

        .card {
            margin-bottom: 20px;
        }
    }

    /* Toggle button style */
    .navbar-toggler {
        border: none;
        background: none;
        font-size: 1.5rem;
    }

    .navbar-toggler:focus {
        outline: none;
        box-shadow: none;
    }

    .darkmode .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='white' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
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
        <button class="navbar-toggler" type="button" aria-controls="navbarNav" aria-expanded="false"
            aria-label="Toggle navigation" id="navbarToggleBtn">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('navbarToggleBtn');
        const navbarCollapse = document.getElementById('navbarNav');

        toggleBtn.addEventListener('click', function () {
            const isShown = navbarCollapse.classList.contains('show');
            const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse)
                || new bootstrap.Collapse(navbarCollapse, { toggle: false });

            if (isShown) {
                bsCollapse.hide();
            } else {
                bsCollapse.show();
            }
        });

        // Tambahan: Tutup saat link di dalamnya diklik (opsional)
        document.querySelectorAll('#navbarNav .nav-link, #navbarNav .btn').forEach(function (link) {
            link.addEventListener('click', function () {
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) bsCollapse.hide();
            });
        });
    });
</script>