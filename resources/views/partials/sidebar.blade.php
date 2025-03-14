<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link text-success" href="/dashboard">
        <i class="bi bi-grid text-success"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed text-success" data-bs-target="#sensor-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-thermometer text-success"></i>
        <span>Sensor</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="sensor-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="/sensor" class="text-success">
            <i class="bi bi-plus-circle text-success"></i>
            <span>Sensor</span>
          </a>
        </li>
        <li>
          <a href="/table" class="text-success">
            <i class="bi bi-layout-text-window-reverse text-success"></i>
            <span>Data Sensor</span>
          </a>
        </li>
        <li>
          <a href="/riwayat_sensor" class="text-success">
            <i class="bi bi-clock-history text-success"></i>
            <span>Riwayat Sensor</span>
          </a>
        </li>
      </ul>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed text-success" data-bs-target="#chart-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-bar-chart text-success"></i>
        <span>Analisis</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="chart-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="/chart" class="text-success">
            <i class="bi bi-bar-chart text-success"></i>
            <span>Charts</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Mitra dipindah menjadi menu utama -->
    <li class="nav-item">
      <a class="nav-link text-success" href="/mitra">
        <i class="bi bi-people text-success"></i>
        <span>Mitra</span>
      </a>
    </li>

    <!-- Proyek dipindah menjadi menu utama -->
    <li class="nav-item">
      <a class="nav-link text-success" href="/proyek">
        <i class="bi bi-clipboard text-success"></i>
        <span>Proyek</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link text-success" href="/user">
        <i class="bi bi-folder-plus text-success"></i>
        <span>User</span>
      </a>
    </li>

    <!-- Denah Sensor tetap setelah Proyek -->
    <li class="nav-item">
      <a class="nav-link text-success" href="/peta">
        <i class="bi bi-map text-success"></i>
        <span>Denah Sensor</span>
      </a>
    </li>

    <hr class="my-3"> <!-- Garis pemisah sebelum logout -->

    <li class="nav-item">
      <a href="#" id="logout-button" class="nav-link text-danger">
        <i class="bi bi-power text-danger"></i>
        <span>Logout</span>
      </a>
    </li>
  </ul> 
</aside><!-- End Sidebar-->

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

<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<!-- <script>
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
</script> -->