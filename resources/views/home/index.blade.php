<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooftop Farming Center</title>
    <!-- CSS Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: white;
        }

        #services {
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'><path fill='%2328a745' fill-opacity='1' d='M0,128L48,144C96,160,192,192,288,213.3C384,235,480,245,576,234.7C672,224,768,192,864,192C960,192,1056,224,1152,202.7C1248,181,1344,107,1392,69.3L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'/></svg>");
            background-size: cover;
            background-position: bottom;
            background-repeat: no-repeat;
            position: relative;
            background-color: transparent;
        }

        /* Section Content Styling */
        .bg-services {
            padding-top: 60px;
            padding-bottom: 60px;
            color: white;
        }

        /* Container inside #services */
        #services .container {
            position: relative;
            z-index: 2;
        }

        /* Card Styling */
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

        .card-img-top {
            border-radius: 10px;
        }

        /* Button Styling */
        .btn-rfc,
        .btn-darkmode {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 1.2rem;
            font-weight: 600;
            border: 2px solid #28a745 ;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

        .btn-rfc {
            background-color: #28a745 ;
            color: white;
        }

        .btn-rfc:hover {
            background-color: #28a745;
            color: black;
            box-shadow: 0 0 15px #28a745;
        }

        .btn-darkmode {
            background-color: #28a745 ;
            color: white;
        }

        .btn-darkmode:hover {
            background-color: #28a745;
            color: black;
            box-shadow: 0 0 15px #28a745 ;
        }

        /* Dark Mode Styling */
        .darkmode {
            background-color: #222 !important;
            color: white !important;
        }

        .darkmode a,
        .darkmode p,
        .darkmode h1,
        .darkmode h2,
        .darkmode h5 {
            color: white !important;
        }

        /* Jumbotron Styling */
        .jumbotron {
            background-color: transparent;
            /* HAPUS background putih */
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            padding: 50px 5%;
            color: #28a745;
            /* TAMBAHKAN teks hijau */
        }

        .jumbotron .video-container,
        .jumbotron .content-overlay {
            flex: 1;
            max-width: 50%;
        }

        /* Pastikan heading dan paragraf tetap hijau */
        .jumbotron h1,
        .jumbotron p {
            color: #28a745 !important;
        }

        .jumbotron .content-overlay {
            text-align: left;
        }

        /* Slide Animations */
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

        /* Footer Styling */
        .footer-light {
            background-color: white !important;
            color: black !important;
            transition: background 0.3s, color 0.3s;
        }

        .footer-dark {
            background-color: #212529 !important;
            color: white !important;
            transition: background 0.3s, color 0.3s;
        }

        .contact-form {
            background-color: #ffffff;
            /* Atau warna lain seperti #f9f9f9 */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Efek bayangan */
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    @include('Header&footer.header')
    <div id="page-content" class="py-5">
        <section class="jumbotron position-relative py-5">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Bagian Teks -->
                    <div class="col-md-6 text-center text-md-start">
                        <h1 class="display-4 fw-bold text-green">Selamat Datang di Rooftop Farming Center</h1>
                        <p class="lead fs-4 text-green">Transforming rooftops into sustainable green spaces</p>
                        <button class="btn" style="background-color: #28a745; border-color: #28a745; color: #fff;" 
                                id="playVideoBtn" data-bs-toggle="modal" data-bs-target="#videoModal">
                            Tonton Video
                        </button>
                    </div>
                    <!-- Bagian Gambar -->
                    <div class="col-md-6 text-center">
                        <img src="asset/image/tentangkami.jpg" alt="About Us" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </section>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#008000" fill-opacity="1"
                d="M0,32L48,48C96,64,192,96,288,101.3C384,107,480,85,576,96C672,107,768,149,864,144C960,139,1056,85,1152,53.3C1248,21,1344,11,1392,5.3L1440,0L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
            </path>
        </svg>
        <!-- Modal Video dengan Background Hitam -->
        <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="ratio ratio-16x9">
                            <iframe id="youtube-video" src="" title="YouTube video player" frameborder="0"
                                allow="autoplay; encrypted-media" allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script untuk mengontrol video -->
        <script>
            var videoModal = document.getElementById('videoModal');
            var youtubeVideo = document.getElementById('youtube-video');
            var playVideoBtn = document.getElementById('playVideoBtn');

            // URL Video YouTube
            var videoUrl = "https://www.youtube.com/embed/YItnSlgapmM";

            // Saat tombol ditekan, set src iframe
            playVideoBtn.addEventListener("click", function () {
                youtubeVideo.src = videoUrl;
            });

            // Saat modal ditutup, reset src agar video berhenti
            videoModal.addEventListener('hidden.bs.modal', function () {
                youtubeVideo.src = "";
            });
        </script>
        <section id="about" class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <img src="{{ asset('asset/image/OIP.jpg') }}" alt="Tentang Kami"
                            class="img-fluid w-100 rounded">
                        <div class="slide-"></div>
                    </div>
                    <div class="col-md-6">
                        <h2 class="fw-bold text-green" style="font-size: 2rem;">Tentang Kami</h2>
                        <p class="text-green" style="font-size: 1.1rem; font-weight: 500;">
                            Kami adalah sebuah pusat pertanian rooftop (rooftop farming center) yang berbasis Internet
                            of Things (IoT). Kami percaya bahwa dengan menggabungkan inovasi pertanian perkotaan dan
                            teknologi IoT, kita dapat mengatasi tantangan pangan dan lingkungan yang dihadapi oleh
                            kota-kota modern.
                        </p>
                        <p class="text-green" style="font-size: 1.1rem; font-weight: 500;">
                            Dengan teknologi IoT yang terintegrasi dalam sistem pertanian rooftop kami, kami dapat
                            secara otomatis memantau dan mengontrol aspek penting seperti irigasi, nutrisi, suhu, dan
                            pencahayaan tanaman. Hal ini memungkinkan kami menciptakan kondisi pertumbuhan yang optimal,
                            meningkatkan produktivitas, dan mengurangi penggunaan sumber daya secara efisien.
                        </p>
                        <p class="text-green" style="font-size: 1.1rem; font-weight: 500;">
                            Komitmen kami tidak hanya terbatas pada pertanian berkelanjutan, tetapi juga pada edukasi
                            dan pelatihan masyarakat tentang manfaat pertanian perkotaan dan teknologi IoT. Dengan
                            demikian, kami ingin meningkatkan kesadaran akan pentingnya memperkuat ketahanan pangan dan
                            menjaga keseimbangan ekosistem di kota-kota masa depan. Bergabunglah dengan kami dalam
                            mengubah cara kita memandang pertanian dan lingkungan perkotaan!
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="py-5 bg-services">
            <div class="container">
                <h2 class="text-center mb-5" style="color: #000;">PROYEK</h2>
                <div class="row">
                    @foreach ($proyeks as $index => $proyek)
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card {{ $index % 2 == 0 ? 'slide-right' : 'slide-left' }}">
                                <img src="{{ $proyek->foto_proyek ? asset('storage/' . $proyek->foto_proyek) : asset('asset/image/default.jpg') }}"
                                    alt="{{ $proyek->nama }}" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase">{{ $proyek->nama }}</h5>
                                    <p class="card-text">
                                        {{ Str::limit($proyek->deskripsi, 150) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- mitra section -->
        <section id="mitra" class="py-5">
            <div class="container">
                <h2 class="text-center mb-5" style="color: #000;">MITRA</h2>
                <div class="row">
                    @foreach($mitras as $mitra)
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    @if($mitra->foto_mitra)
                                        <img src="{{ asset('storage/' . $mitra->foto_mitra) }}" class="img-fluid mt-2"
                                            alt="Foto Mitra">
                                    @endif
                                    <h5 class="card-title">{{ $mitra->nama }}</h5>
                                    <p class="card-text">{{ $mitra->lokasi }}</p>
                                    <p class="card-text">{{ $mitra->email }}</p>
                                    <p class="card-text">{{ $mitra->telepon }}</p>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-us py-5">
            <div class="container">
                <div class="row justify-content-center g-4"> <!-- center the column -->
                    <!-- Form -->
                    <div class="col-md-7 mx-auto"> <!-- center the form -->
                        <div class="card p-4">
                            <h2 class="text-center mb-4">Kontak</h2>
                            <form>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Your Name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" placeholder="Your Email" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" placeholder="Subject" required>
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" rows="4" placeholder="Message" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100" style="background-color: #28a745; border-color: #28a745; color: #fff;">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#28a745" fill-opacity="1"
                    d="M0,128L40,138.7C80,149,160,171,240,165.3C320,160,400,128,480,112C560,96,640,96,720,122.7C800,149,880,203,960,202.7C1040,203,1120,149,1200,133.3C1280,117,1360,139,1400,149.3L1440,160L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z">
                </path>
            </svg>
        </section>

        @extends('Header&footer.footer')
        <!-- Footer -->
        <!-- JS Bootstrap -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/script.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const themeSwitch = document.getElementById("theme-switch");
                const body = document.body;
                const navbar = document.querySelector(".navbar");
                const footer = document.querySelector("footer");

                // Cek apakah dark mode diaktifkan sebelumnya
                if (localStorage.getItem("darkmode") === "enabled") {
                    body.classList.add("darkmode");
                    navbar.classList.add("darkmode-navbar");
                    footer.classList.add("footer-dark");
                    updateThemeButton("light");
                } else {
                    footer.classList.add("footer-light"); // Default ke putih
                }

                themeSwitch.addEventListener("click", function () {
                    if (body.classList.contains("darkmode")) {
                        body.classList.remove("darkmode");
                        navbar.classList.remove("darkmode-navbar");
                        footer.classList.remove("footer-dark");
                        footer.classList.add("footer-light"); // Mode terang
                        localStorage.setItem("darkmode", "disabled");
                        updateThemeButton("dark");
                    } else {
                        body.classList.add("darkmode");
                        navbar.classList.add("darkmode-navbar");
                        footer.classList.remove("footer-light");
                        footer.classList.add("footer-dark"); // Mode gelap
                        localStorage.setItem("darkmode", "enabled");
                        updateThemeButton("light");
                    }
                });

                function updateThemeButton(mode) {
                    if (mode === "light") {
                        themeSwitch.innerHTML = "☀️"; // Matahari untuk mode terang
                        themeSwitch.style.backgroundColor = "black";
                        themeSwitch.style.color = "white";
                        themeSwitch.style.border = "2px solid black";
                    } else {
                        themeSwitch.innerHTML = "🌙"; // Bulan untuk mode gelap
                        themeSwitch.style.backgroundColor = "#28a745 ";
                        themeSwitch.style.color = "#b74b4b";
                        themeSwitch.style.border = "2px solid #28a745 ";
                    }
                }
            });
        </script>
</body>
</html>