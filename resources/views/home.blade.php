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
  <!-- Custom CSS -->
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
    background-color: transparent; /* Menghilangkan background */
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
    color: white;
    font-weight: 500;
    transition: 0.3s ease-in-out;
    border-bottom: 2px solid transparent;
}

.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
    color: #b74b4b;
    border-bottom: 2px solid #b74b4b;
}

/* RFC-App Button */
.btn-rfc {
    padding: 8px 16px;
    background-color: rgb(7, 255, 40);
    border-radius: 25px;
    font-size: 1.4rem;
    color:rgb(255, 255, 255);
    font-weight: 600;
    border: 2px solid rgb(7, 255, 40);
    transition: 0.3s ease-in-out;
}

.btn-rfc:hover {
    background-color:rgb(0, 255, 13);
    color: black;
    box-shadow: 0 0 15px rgb(0, 255, 13);
}

/* Dark Mode Toggle */
.btn-darkmode {
    padding: 8px 16px;
    background-color:  rgb(7, 255, 40);
    border-radius: 25px;
    font-size: 1.4rem;
    color: #b74b4b;
    font-weight: 600;
    border: 2px solid  rgb(7, 255, 40);
    transition: 0.3s ease-in-out;
}

.btn-darkmode:hover {
    background-color:  rgb(7, 255, 40);
    color: black;
    box-shadow: 0 0 15px  rgb(7, 255, 40);
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
    background-color: #222 !important;
    color: white !important;
  }
  
  .darkmode a, .darkmode p, .darkmode h1, .darkmode h2, .darkmode h5 {
    color: white !important;
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
    object-fit: cover; /* Pastikan video tetap proporsional */
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
    height: 20vh; /* Teks di tengah layar */
    padding: 20px;
}

/* Jumbotron Styling */
.jumbotron {
    position: relative;
    height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.3);
}
.custom-margin {
    margin-bottom: 350px;
}

/* Efek hover untuk card */
.card {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    border-radius: 15px;
    overflow: hidden;
}

.card:hover {
    transform: scale(1.05);
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
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
  </style>
</head>
<body>
  <!-- Navbar -->
  @include('Header&footer.header')
  @extends('Header&footer.footer')
  <div id="page-content">
    <header class="jumbotron jumbotron-fluid position-relative">
        <div class="video-container">
            <video autoplay muted loop playsinline>
                <source src="asset/video/profil.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="container position-relative h-50 d-flex align-items-center justify-content-center text-center text-white">
          <div class="custom-margin">
              <h1 class="display-4 fw-bold">Selamat Datang di Rooftop Farming Center</h1>
              <p class="lead fs-4">Transforming rooftops into sustainable green spaces</p>
          </div>
      </div>
    </header>
    <section id="about" class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h2>Tentang kami</h2>
          <p>Kami adalah sebuah pusat pertanian rooftop (rooftop farming center) yang berbasis Internet of Things (IoT). Kami percaya bahwa dengan menggabungkan inovasi pertanian perkotaan dan teknologi IoT, kita dapat mengatasi tantangan pangan dan lingkungan yang dihadapi oleh kota-kota modern.</p>
          <p>Dengan teknologi IoT yang terintegrasi dalam sistem pertanian rooftop kami, kami dapat secara otomatis memantau dan mengontrol aspek penting seperti irigasi, nutrisi, suhu, dan pencahayaan tanaman. Hal ini memungkinkan kami menciptakan kondisi pertumbuhan yang optimal, meningkatkan produktivitas, dan mengurangi penggunaan sumber daya secara efisien.</p>
          <p>Komitmen kami tidak hanya terbatas pada pertanian berkelanjutan, tetapi juga pada edukasi dan pelatihan masyarakat tentang manfaat pertanian perkotaan dan teknologi IoT. Dengan demikian, kami ingin meningkatkan kesadaran akan pentingnya memperkuat ketahanan pangan dan menjaga keseimbangan ekosistem di kota-kota masa depan. Bergabunglah dengan kami dalam mengubah cara kita memandang pertanian dan lingkungan perkotaan!</p>
        </div>
        <div class="col-md-6">
          <img src="asset/image/tentangkami.jpg" alt="About Us" class="img-fluid">
          <div class="slide-"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="py-5">
    <div class="container">
      <h2 class="text-center mb-5">PROJEK</h2>
    <div class="container mt-5">
        <div class="row">
        <!-- Budi Daya Tanaman Anggur -->
        <div class="col-md-6 col-lg-3">
            <div class="card mb-4 slide-right">
                <img src="asset/image/pohonanggur.jpg" alt="Budi Daya Anggur" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title text-uppercase">Budi Daya Tanaman Anggur</h5>
                    <p class="card-text">
                        Anggur ditanam pada rooftop dengan teknologi IoT, memungkinkan penyiraman dan perawatan otomatis.
                    </p>
                </div>
            </div>
        </div>

        <!-- Smart Green House Melon -->
        <div class="col-md-6 col-lg-3">
            <div class="card mb-4 slide-left">
                <img src="asset/image/goldenmelon.jpg" alt="Smart Green House Melon" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title text-uppercase">Smart Green House untuk Melon</h5>
                    <p class="card-text">
                        Melon ditanam di Smart Green House berbasis IoT. "Golden Melon" adalah varietas unggulan yang kami kembangkan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Budi Daya Pohon Jeruk & Pepaya -->
        <div class="col-md-6 col-lg-3">
            <div class="card mb-4 slide-right">
                <img src="asset/image/pohonjeruk.jpg" alt="Budi Daya Jeruk & Pepaya" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title text-uppercase">Budidaya Pohon Jeruk & Pepaya</h5>
                    <p class="card-text">
                        Tanaman jeruk dan pepaya dibudidayakan di Smart Rooftop Kampus Telkom Surabaya untuk penelitian dan inovasi IoT.
                    </p>
                </div>
            </div>
        </div>

        <!-- Budi Daya Pohon Pisang & Mangga -->
        <div class="col-md-6 col-lg-3">
            <div class="card mb-4 slide-left">
                <img src="asset/image/pohonpisang.jpg" alt="Budi Daya Pisang & Mangga" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title text-uppercase">Budidaya Pohon Pisang & Mangga</h5>
                    <p class="card-text">
                        Pohon pisang tumbuh di dekat "Smart Pond", kolam ikan otomatis berbasis IoT. Pohon mangga juga mulai berbuah dengan baik.
                    </p>
                </div>
            </div>
        </div>
    </div>
      </div>
    </div>
  </section>

  
  <!-- mitra section -->
  <section id="mitra" class="py-5">
    <div class="container">
      <h2>MITRA</h2>
      <div class="row">
        <div class="col-md-4">
          <div class="card mb-4">
            <div class="video-container2">
              <video autoplay muted loop>
                <source src="asset/video/puspalebo.mp4" type="video/mp4">
                Your browser does not support the video tag.
              </video>
          </div>
            <div class="card-body">
              <h5 class="card-title">PUSPA LEBO</h5>
              <p class="card-text">UPT PENGEMBANGAN AGRIBISNIS TANAMAN PANGAN DAN HORTIKULTURA
                AGRO WISATA PUSPA LEBO</p>
                <p>CP: 081234951713</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4">
            <div class="video-container2">
            <video autoplay muted loop>
              <source src="asset/video/DKPPSurabaya.mp4" type="video/mp4">
              Your browser does not support the video tag.
            </video>
          </div>
            <div class="card-body">
              <h5 class="card-title">DKPP Surabaya</h5>
                <P class="card-text">Pagesangan II/56, Kec. Jambangan, Surabaya 60233</p>
                <p class="card-text">(031) 828 2328</p>
                <p class="card-text">dinaskppsby@surabaya.go.id</p>
                081 388 111 588</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4">
            <div class="video-container2">
              <video autoplay muted loop>
                <source src="asset/video/DDurian.mp4" type="video/mp4">
                Your browser does not support the video tag.
              </video>
              </div>
            <div class="card-body">
              <h5 class="card-title">D'Durian Park</h5>
              <P class="card-text">Jl. Cemorosewu, Segunung, Wonosalam, Kec. Wonosalam, Kabupaten Jombang, Jawa Timur 61476, Indonesia</p>
                <p class="card-text">0822-2941-9828</p>
                <p class="card-text">dedurian.park@gmail.com</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="contact-us py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 border p-4 rounded shadow">
                <div class="row">
                  
                    <!-- Image Section -->
                    <div class="col-md-4 d-flex align-items-center justify-content-center">
                        <img src="asset/image/goldenmelon.jpg" alt="Email Icon" class="img-fluid" style="max-width: 500px;">
                    </div>
                    
                    <!-- Form Section -->
                    <div class="col-md-8">
                        <h2 class="text-center mb-4">Contact Us</h2>
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter your name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="4" placeholder="Your message..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="card">
            <div class="card-body">
              <h5 class="card-title">Kunjungi Kami</h5>
              <p class="card-text">Jl. Ketintang no.156<br>Surabaya, 60231<br>Jawa Timur, Indonesia</p>
            </div>
          </div>
        </div>
  </div>
  <!-- Footer -->
  <!-- JS Bootstrap -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/script.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
        const themeSwitch = document.getElementById("theme-switch");
        const body = document.body;

        // Cek apakah pengguna sebelumnya mengaktifkan dark mode
        if (localStorage.getItem("darkmode") === "enabled") {
            body.classList.add("darkmode");
            themeSwitch.innerHTML = "☀️"; // Ubah ikon ke matahari
            themeSwitch.style.backgroundColor = "black"; // Ubah warna tombol ke hitam
            themeSwitch.style.color = "white";
            themeSwitch.style.border = "2px solid white";
        }

        themeSwitch.addEventListener("click", function () {
            if (body.classList.contains("darkmode")) {
                body.classList.remove("darkmode");
                themeSwitch.innerHTML = "🌙"; // Kembali ke ikon bulan
                themeSwitch.style.backgroundColor = "rgb(7, 255, 40)"; // Warna normal
                themeSwitch.style.color = "#b74b4b";
                themeSwitch.style.border = "2px solid rgb(7, 255, 40)";
                localStorage.setItem("darkmode", "disabled");
            } else {
                body.classList.add("darkmode");
                themeSwitch.innerHTML = "☀️"; // Ubah ikon ke matahari
                themeSwitch.style.backgroundColor = "black"; // Ubah warna tombol ke hitam
                themeSwitch.style.color = "white";
                themeSwitch.style.border = "2px solid white";
                localStorage.setItem("darkmode", "enabled");
            }
        });
    });
</script>

</body>
</html>
