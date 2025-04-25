<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link text-success" href="{{ url('/dashboard') }}">
        <i class="bi bi-grid text-success me-2"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- Sensor Submenu -->
    <li class="nav-item">
      <a class="nav-link collapsed text-success" data-bs-toggle="collapse" href="#sensor-nav">
        <i class="bi bi-thermometer text-success me-2"></i>
        <span>Sensor</span>
        <i class="bi bi-chevron-down ms-auto text-success"></i>
      </a>
      <ul id="sensor-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li><a href="{{ url('/sensor_ph') }}" class="text-success"><i class="bi bi-droplet me-2"></i> pH</a></li>
        <li><a href="{{ url('/sensor_pota') }}" class="text-success"><i class="bi bi-flower1 me-2"></i> Potasium</a></li>
        <li><a href="{{ url('/sensor_phospor') }}" class="text-success"><i class="bi bi-lightning me-2"></i> Fosfor</a></li>
        <li><a href="{{ url('/sensor_Nitrogen') }}" class="text-success"><i class="bi bi-tree me-2"></i> Nitrogen</a></li>
        <li><a href="{{ url('/sensor_humidity') }}" class="text-success"><i class="bi bi-moisture me-2"></i> Kelembaban</a></li>
        <li><a href="{{ url('/sensor_temp') }}" class="text-success"><i class="bi bi-thermometer-half me-2"></i> Suhu</a></li>
        <li><a href="{{ url('/sensor_EC') }}" class="text-success"><i class="bi bi-water me-2"></i> Konduktivitas (EC)</a></li>
      </ul>
    </li>

    <!-- Analisis -->
    <li class="nav-item">
      <a class="nav-link collapsed text-success" data-bs-toggle="collapse" href="#chart-nav">
        <i class="bi bi-bar-chart text-success me-2"></i>
        <span>Analisis</span>
        <i class="bi bi-chevron-down ms-auto text-success"></i>
      </a>
      <ul id="chart-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li><a href="{{ url('/chart') }}" class="text-success"><i class="bi bi-bar-chart me-2"></i> Charts</a></li>
        <li><a href="{{ url('/sensor') }}" class="text-success"><i class="bi bi-plus-circle me-2"></i> Sensor</a></li>
        <li><a href="{{ url('/table') }}" class="text-success"><i class="bi bi-layout-text-window-reverse me-2"></i> Data Sensor</a></li>
        <li><a href="{{ url('/riwayat_sensor') }}" class="text-success"><i class="bi bi-clock-history me-2"></i> Riwayat Sensor</a></li>
      </ul>
    </li>

    <!-- Tanaman -->
    <li class="nav-item">
      <a class="nav-link text-success" href="{{ url('/tanaman') }}">
        <i class="bi bi-seedling text-success me-2"></i>
        <span>Tanaman</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-success" href="{{ url('/periode_tanam') }}">
        <i class="bi bi-calendar-range text-success me-2"></i>
        <span>Periode Tanam</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-success" href="{{ url('/riwayat_tanaman') }}">
        <i class="bi bi-info-square text-success me-2"></i>
        <span>Riwayat Tanaman</span>
      </a>
    </li>

    <!-- Menu Tambahan -->
    <li class="nav-item">
      <a class="nav-link text-success" href="{{ url('/mitra') }}">
        <i class="bi bi-people text-success me-2"></i>
        <span>Mitra</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-success" href="{{ url('/proyek') }}">
        <i class="bi bi-clipboard text-success me-2"></i>
        <span>Proyek</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-success" href="{{ url('/user') }}">
        <i class="bi bi-folder-plus text-success me-2"></i>
        <span>User</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-success" href="{{ url('/peta') }}">
        <i class="bi bi-map text-success me-2"></i>
        <span>Denah Sensor</span>
      </a>
    </li>

    <hr class="my-3">

    <!-- Logout -->
    <li class="nav-item">
      <a href="#" id="logout-button" class="nav-link text-danger">
        <i class="bi bi-power text-danger me-2"></i>
        <span>Logout</span>
      </a>
    </li>
  </ul>
</aside>
<!-- SweetAlert + Logout Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.getElementById("logout-button").addEventListener("click", function(event) {
    event.preventDefault();

    Swal.fire({
      title: "Apakah Anda yakin ingin logout?",
      text: "Anda harus login kembali untuk mengakses sistem.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Ya, Logout!",
      cancelButtonText: "Batal"
    }).then((result) => {
      if (result.isConfirmed) {
        let token = localStorage.getItem("api_token") || sessionStorage.getItem("api_token");

        if (!token) {
          Swal.fire({
            title: "Anda sudah logout!",
            text: "Tidak ada token valid ditemukan.",
            icon: "info"
          }).then(() => {
            window.location.href = "/login";
          });
          return;
        }

        fetch("{{ route('logout') }}", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Authorization": "Bearer " + token
            },
            body: JSON.stringify({})
          })
          .then(response => response.json())
          .then(data => {
            if (data.status === "success") {
              localStorage.removeItem("api_token");
              sessionStorage.removeItem("api_token");
              Swal.fire({
                title: "Logout Berhasil!",
                text: "Anda telah keluar dari sistem.",
                icon: "success",
                timer: 2000,
                showConfirmButton: false
              }).then(() => {
                window.location.href = "/login";
              });
            } else {
              Swal.fire({
                title: "Logout Gagal!",
                text: "Terjadi kesalahan saat logout, silakan coba lagi.",
                icon: "error"
              });
            }
          })
          .catch(error => {
            console.error("Logout error:", error);
            Swal.fire({
              title: "Kesalahan!",
              text: "Terjadi kesalahan saat memproses logout.",
              icon: "error"
            });
          });
      }
    });
  });
</script>