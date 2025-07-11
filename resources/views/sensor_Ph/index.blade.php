@extends('partials.headerFooter')

@section('content')
    @include('partials.sidebar')
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <main id="main" class="main p-4"> -->
    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/"><i class="bi bi-house-door-fill"></i> Home</a>
                </li>
                <li class="breadcrumb-item active">
                    Sensor pH
                </li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3 text-dark fw-semibold">
                    <i class="bi bi-graph-up me-2"></i>Sensor pH
                </h5>

                <div class="mb-3 d-flex justify-content-start gap-2">
                    <a href="{{ route('ph.export') }}" class="btn btn-success">
                        <i class="bi bi-download me-1"></i> Export Data
                    </a>
                    {{-- <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Data
                    </button> --}}
                    @php
                        $userRole = auth()->user()->role;
                    @endphp

                    @if(in_array($userRole, ['admin', 'owner']))
                        <button type="button" class="btn btn-primary" id="btn-fetch-ph">
                            <i class="bi bi-cloud-download me-1"></i> Fetch & Simpan Data
                        </button>
                    @endif

                </div>

                @if(isset($data['ph']) && is_array($data['ph']))
                    @php
                        $sensorData = $data['ph'];
                        $sensorValue = is_array($sensorData['value'] ?? null)
                            ? ($sensorData['value']['value'] ?? '-')
                            : ($sensorData['value'] ?? '-');
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="bg-dark text-white text-center">
                                <tr>
                                    <th>Parameter</th>
                                    <th>Waktu</th>
                                    <th>Resource Index (RI)</th>
                                    <th>Data Sensor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr>
                                    <td class="fw-semibold">pH</td>
                                    <td>{{ $sensorData['time'] ?? '-' }}</td>
                                    <td>{{ $sensorData['ri'] ?? '-' }}</td>
                                    <td>{{ $sensorValue }}</td>
                                    <td class="d-flex justify-content-center gap-1">
                                        <!-- View Button -->
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalView">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <!-- Edit Button -->
                                        @php
                                            $userRole = auth()->user()->role;
                                        @endphp

                                        @if (in_array($userRole, ['admin', 'owner']))
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <!-- Delete Form -->
                                            <form action="{{ route('sensor_ph.destroy', $sensorData['ri']) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning text-center">
                        Data sensor pH tidak ditemukan atau tidak valid.
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal Tambah -->
        <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('sensor_ph.store') }}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data pH</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="value" class="form-label">Nilai pH</label>
                            <input type="number" step="any" class="form-control" name="value" required>
                        </div>
                        <div class="mb-3">
                            <label for="time" class="form-label">Waktu</label>
                            <input type="datetime-local" class="form-control" name="time" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('sensor_ph.update', $sensorData['ri']) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data pH</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="value" class="form-label">Nilai pH</label>
                            <input type="number" step="any" class="form-control" name="value" value="{{ $sensorValue }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="time" class="form-label">Waktu</label>
                            <input type="datetime-local" class="form-control" name="time"
                                value="{{ \Carbon\Carbon::parse($sensorData['time'])->format('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal View -->
        <div class="modal fade" id="modalView" tabindex="-1" aria-labelledby="modalViewLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Data pH</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nilai pH:</strong> {{ $sensorValue }}</p>
                        <p><strong>Waktu:</strong> {{ $sensorData['time'] ?? '-' }}</p>
                        <p><strong>Resource Index:</strong> {{ $sensorData['ri'] ?? '-' }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
    </main>
    <script>
        document.getElementById('btn-fetch-ph').addEventListener('click', function () {
            fetch("{{ route('sensor_ph.fetch-store') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload data setelah berhasil fetch
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Gagal memproses data.',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan!',
                        text: 'Tidak dapat terhubung ke server.',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                    console.error(error);
                });
        });
    </script>
@endsection