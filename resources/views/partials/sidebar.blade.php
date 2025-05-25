<!-- resources/views/partials/sidebar.blade.php -->

<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <!-- Dashboard -->
    @php
      $prefix = auth()->user()->role;
    @endphp
    <li class="nav-item">
      <a class="nav-link text-success" href="{{ route($prefix . '.dashboard') }}">
        <i class="bi bi-grid text-success me-2"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- Divider -->
    <hr class="my-3">

    @php
      $prefix = auth()->user()->role; // 'admin', 'owner', 'user'
    @endphp

    <!-- Sensor (Semua Role) -->
    <li class="nav-item">
      <a class="nav-link collapsed text-success" data-bs-toggle="collapse" href="#sensor-nav">
        <i class="bi bi-thermometer text-success me-2"></i>
        <span>Sensor</span>
        <i class="bi bi-chevron-down ms-auto text-success"></i>
      </a>
      <ul id="sensor-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ url("/$prefix/sensor_ph") }}" class="text-success">
            <i class="bi bi-droplet me-2"></i> pH
          </a>
        </li>
        <li>
          <a href="{{ url("/$prefix/sensor_pota") }}" class="text-success">
            <i class="bi bi-flower1 me-2"></i> Potasium
          </a>
        </li>
        <li>
          <a href="{{ url("/$prefix/sensor_phospor") }}" class="text-success">
            <i class="bi bi-lightning me-2"></i> Fosfor
          </a>
        </li>
        <li>
          <a href="{{ url("/$prefix/sensor_Nitrogen") }}" class="text-success">
            <i class="bi bi-tree me-2"></i> Nitrogen
          </a>
        </li>
        <li>
          <a href="{{ url("/$prefix/sensor_humidity") }}" class="text-success">
            <i class="bi bi-moisture me-2"></i> Kelembaban
          </a>
        </li>
        <li>
          <a href="{{ url("/$prefix/sensor_temp") }}" class="text-success">
            <i class="bi bi-thermometer-half me-2"></i> Suhu
          </a>
        </li>
        <li>
          <a href="{{ url("/$prefix/sensor_EC") }}" class="text-success">
            <i class="bi bi-water me-2"></i> EC
          </a>
        </li>
      </ul>
    </li>

    <!-- Analisis (Semua Role) -->
    <li class="nav-item">
      <a class="nav-link collapsed text-success" data-bs-toggle="collapse" href="#chart-nav">
        <i class="bi bi-bar-chart text-success me-2"></i>
        <span>Analisis</span>
        <i class="bi bi-chevron-down ms-auto text-success"></i>
      </a>
      <ul id="chart-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ url("/$prefix/chart") }}" class="text-success">
            <i class="bi bi-bar-chart me-2"></i> Charts
          </a>
        </li>
        <li>
          <a href="{{ url("/$prefix/sensor") }}" class="text-success">
            <i class="bi bi-plus-circle me-2"></i> Sensor
          </a>
        </li>
      </ul>
    </li>

    <!-- Tanaman (Semua Role) -->
    <li class="nav-item">
      <a class="nav-link collapsed text-success" data-bs-toggle="collapse" href="#tanaman-nav">
        <i class="bi bi-tree-fill text-success me-2"></i>
        <span>Periode Tanam</span>
        <i class="bi bi-chevron-down ms-auto text-success"></i>
      </a>
      <ul id="tanaman-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ url("/$prefix/periode_tanam") }}" class="text-success">
            <i class="bi bi-calendar-range me-2"></i> Periode Tanam
          </a>
        </li>
        <li>
          <a href="{{ url("/$prefix/tanaman") }}" class="text-success">
            <i class="bi bi-tree me-2"></i> Tanaman
          </a>
        </li>
        {{-- <li>
          <a href="{{ url("/$prefix/input_harian") }}" class="text-success">
            <i class="bi bi-calendar-range me-2"></i> Inputan Harian Tanam
          </a>
        </li> --}}
        <li>
          <a href="{{ url("/$prefix/riwayat_tanaman") }}" class="text-success">
            <i class="bi bi-info-square me-2"></i> Riwayat Tanaman
          </a>
        </li>
        {{-- <li>
          <a href="{{ url("/$prefix/kategori_sampel") }}" class="text-success">
            <i class="bi bi-box me-2"></i> Kategori Sampel
          </a>
        </li> --}}
      </ul>
    </li>

    <!-- Setting (admin & owner) -->
    @if (in_array(auth()->user()->role, ['admin', 'owner']))
      <li class="nav-item">
        <a class="nav-link collapsed text-success" data-bs-toggle="collapse" href="#setting-nav">
          <i class="bi bi-gear text-success me-2"></i>
          <span>Setting</span>
          <i class="bi bi-chevron-down ms-auto text-success"></i>
        </a>
        <ul id="setting-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ url("/$prefix/mitra") }}" class="text-success">
              <i class="bi bi-people me-2"></i> Mitra
            </a>
          </li>
          <li>
            <a href="{{ url("/$prefix/proyek") }}" class="text-success">
              <i class="bi bi-clipboard me-2"></i> Proyek
            </a>
          </li>
          <li>
            <a href="{{ url("/$prefix/user") }}" class="text-success">
              <i class="bi bi-folder-plus me-2"></i> User
            </a>
          </li>
        </ul>
      </li>
    @endif

    @php
      $role = auth()->user()->role;
      $prefix = in_array($role, ['admin', 'owner', 'user']) ? $role : '';
    @endphp

    @if(in_array(auth()->user()->role, ['admin', 'owner', 'user']))
      <li class="nav-item">
        <a class="nav-link text-success" href="{{ url("/$prefix/peta") }}">
          <i class="bi bi-map text-success me-2"></i>
          <span>Denah Sensor</span>
        </a>
      </li>
    @endif

    <!-- Divider -->
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

<!-- SweetAlert Logout Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  const logoutButton = document.getElementById("logout-button");

  if (logoutButton) {
    logoutButton.addEventListener("click", function (event) {
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
          fetch("{{ route('logout') }}", {
            method: "POST",
            headers: {
              "X-CSRF-TOKEN": "{{ csrf_token() }}",
              "Accept": "application/json"
            }
          })
          .then(response => {
            if (response.ok) {
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
              throw new Error("Gagal logout");
            }
          })
          .catch(error => {
            Swal.fire({
              title: "Kesalahan!",
              text: "Terjadi kesalahan saat memproses logout.",
              icon: "error"
            });
          });
        }
      });
    });
  }
</script>
