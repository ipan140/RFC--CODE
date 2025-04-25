<!-- Sidebar tanpa Mitra, Proyek, dan User -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link text-success" href="{{ url('/dashboard') }}">
        <i class="bi bi-grid text-success"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- Sensor (Submenu) -->
    <li class="nav-item">
      <a class="nav-link collapsed text-success" data-bs-target="#sensor-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-thermometer text-success"></i>
        <span>Sensor</span>
        <i class="bi bi-chevron-down ms-auto text-success"></i>
      </a>
      <ul id="sensor-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li><a href="{{ url('/sensor_ph') }}" class="text-success"><i class="bi bi-droplet"></i><span>pH</span></a></li>
        <li><a href="{{ url('/sensor_pota') }}" class="text-success"><i class="bi bi-flower1"></i><span>Potasium (Pota)</span></a></li>
        <li><a href="{{ url('/sensor_phospor') }}" class="text-success"><i class="bi bi-lightning"></i><span>Fosfor (Phospor)</span></a></li>
        <li><a href="{{ url('/sensor_Nitrogen') }}" class="text-success"><i class="bi bi-tree"></i><span>Nitrogen</span></a></li>
        <li><a href="{{ url('/sensor_humidity') }}" class="text-success"><i class="bi bi-moisture"></i><span>Kelembaban (Humidity)</span></a></li>
        <li><a href="{{ url('/sensor_temp') }}" class="text-success"><i class="bi bi-thermometer"></i><span>Suhu (Temp)</span></a></li>
        <li><a href="{{ url('/sensor_EC') }}" class="text-success"><i class="bi bi-water"></i><span>Konduktivitas Elektrik (EC)</span></a></li>
      </ul>
    </li>

    <!-- Analisis -->
    <li class="nav-item">
      <a class="nav-link collapsed text-success" data-bs-target="#chart-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-bar-chart text-success"></i>
        <span>Analisis</span>
        <i class="bi bi-chevron-down ms-auto text-success"></i>
      </a>
      <ul id="chart-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li><a href="{{ url('/chart') }}" class="text-success"><i class="bi bi-bar-chart"></i><span>Charts</span></a></li>
        <li><a href="{{ url('/sensor') }}" class="text-success"><i class="bi bi-plus-circle"></i><span>Sensor</span></a></li>
        <li><a href="{{ url('/table') }}" class="text-success"><i class="bi bi-layout-text-window-reverse"></i><span>Data Sensor</span></a></li>
        <li><a href="{{ url('/riwayat_sensor') }}" class="text-success"><i class="bi bi-clock-history"></i><span>Riwayat Sensor</span></a></li>
      </ul>
    </li>

    <!-- Denah Sensor -->
    <li class="nav-item">
      <a class="nav-link text-success" href="{{ url('/peta') }}">
        <i class="bi bi-map text-success"></i>
        <span>Denah Sensor</span>
      </a>
    </li>

    <hr class="my-3">

    <!-- Logout -->
    <li class="nav-item">
      <a href="#" id="logout-button" class="nav-link text-danger">
        <i class="bi bi-power text-danger"></i>
        <span>Logout</span>
      </a>
    </li>
  </ul>
</aside>
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
            text: "Anda tidak memiliki token yang valid.",
            icon: "info",
            confirmButtonText: "OK"
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
              text: "Anda telah logout dari sistem.",
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
              icon: "error",
              confirmButtonText: "OK"
            });
          }
        })
        .catch(error => {
          console.error("Error:", error);
          Swal.fire({
            title: "Kesalahan!",
            text: "Terjadi kesalahan saat logout.",
            icon: "error",
            confirmButtonText: "OK"
          });
        });
      }
    });
  });
</script>