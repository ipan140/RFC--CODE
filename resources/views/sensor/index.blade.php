@extends('partials.headerFooter')
@include('partials.sidebar')

@section('content')
<!-- Judul Halaman -->
<div class="pagetitle">
    <h1><i class="bi bi-bar-chart-line-fill"></i> Data Sensor</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/"><i class="bi bi-house-door-fill"></i> Home</a>
            </li>
            <li class="breadcrumb-item active">Data Sensor</li>
        </ol>
    </nav>
</div>

<!-- Konten Utama -->
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h5 class="card-title"><i class="bi bi-table"></i> Tabel Data Sensor</h5>

                    <!-- Tombol Aksi -->
                    <div class="mb-3 d-flex flex-wrap gap-2">
                        <a href="{{ route('sensor.export') }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Export Data
                        </a>
                        @php
                        $userRole = auth()->user()->role;
                        @endphp

                        @if (in_array($userRole, ['admin', 'owner']))
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahSensorModal">
                            <i class="bi bi-plus-circle"></i> Tambah Data
                        </button>
                        <button type="button" class="btn btn-primary" id="fetchConfirmBtn">
                            <i class="bi bi-cloud-download-fill"></i> Fetch & Simpan Data
                        </button>
                        @endif

                    </div>

                    <!-- Tabel Data -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Parameter</th>
                                    <th>Waktu</th>
                                    <th>Resource Index (RI)</th>
                                    <th>Data Sensor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataSensor as $data)
                                <tr>
                                    <td>{{ $data['parameter'] ?? '-' }}</td>
                                    <td>{{ $data['time'] ?? '-' }}</td>
                                    <td>{{ $data['ri'] ?? '-' }}</td>
                                    <td>
                                        {{ isset($data['value']) ? number_format($data['value'], 2, '.', '') : '-' }}
                                    </td>
                                    <td>
                                        @if (!empty($data['id']))
                                        <a href="{{ route('sensor.show', $data['id']) }}" class="btn btn-info btn-sm" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('sensor.edit', $data['id']) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('sensor.destroy', $data['id']) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @else
                                        <span class="text-muted">Data dari API</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-muted">Tidak ada data tersedia.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div><!-- End Tabel -->

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Data Sensor -->
<div class="modal fade" id="tambahSensorModal" tabindex="-1" aria-labelledby="tambahSensorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('sensor.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Sensor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <!-- Parameter / Device -->
                    <div class="mb-3">
                        <label for="parameter" class="form-label">Parameter / Device</label>
                        <input type="text" name="parameter" id="parameter" class="form-control" value="{{ old('parameter') }}" required>
                        @error('parameter')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Value -->
                    <div class="mb-3">
                        <label for="value" class="form-label">Nilai</label>
                        <input type="number" name="value" step="0.01" class="form-control" value="{{ old('value') }}" required>
                        @error('value')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Time -->
                    <div class="mb-3">
                        <label for="time" class="form-label">Waktu</label>
                        <input type="datetime-local" name="time" class="form-control" value="{{ old('time') }}" required>
                        @error('time')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Resource Index (RI) -->
                    <div class="mb-3">
                        <label for="ri" class="form-label">Resource Index (RI)</label>
                        <input type="text" name="ri" class="form-control" value="{{ old('ri') }}" required>
                        @error('ri')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- SweetAlert Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fetchBtn = document.getElementById('fetchConfirmBtn');

        if (!fetchBtn) return;

        fetchBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Ambil data semua sensor?',
                text: "Data dari Antares akan diambil dan disimpan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, ambil!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengambil Data...',
                        text: 'Mohon tunggu sementara data diambil.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch("{{ route('sensor.fetchAndStore') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.results && Array.isArray(data.results)) {
                                const failed = data.results.filter(r => !r.success);
                                const succeeded = data.results.filter(r => r.success);

                                const message = data.results.map(r =>
                                    `${r.device.toUpperCase()}: ${r.success ? '✅' : '❌'} ${r.message}${r.value ? ' (nilai: ' + r.value + ')' : ''}`
                                ).join('\n');

                                let icon = 'success';
                                let title = 'Berhasil!';
                                if (succeeded.length === 0) {
                                    icon = 'error';
                                    title = 'Gagal!';
                                } else if (failed.length > 0) {
                                    icon = 'warning';
                                    title = 'Sebagian Gagal';
                                }

                                Swal.fire({
                                    icon: icon,
                                    title: title,
                                    html: `<pre style="text-align:left">${message}</pre>`
                                }).then(() => location.reload());
                            } else {
                                throw new Error('Format data tidak sesuai dari server.');
                            }
                        })
                        .catch(error => {
                            console.error('Fetch error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: error.message || 'Terjadi kesalahan saat mengambil data.'
                            });
                        });
                }
            });
        });
    });
</script>
@endsection