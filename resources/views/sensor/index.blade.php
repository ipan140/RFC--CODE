@extends('partials.headerFooter')

@section('content')
    @include('partials.sidebar')

    <div class="pagetitle">
        <h1><i class="bi bi-bar-chart-line-fill"></i> Data Sensor</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door-fill"></i> Home</a></li>
                <li class="breadcrumb-item active">Data Sensor</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <h5 class="card-title"><i class="bi bi-table"></i> Tabel Data Sensor</h5>

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
                                            <td>{{ isset($data['time']) ? \Carbon\Carbon::parse($data['time'])->format('d-m-Y H:i:s') : '-' }}</td>
                                            <td>{{ $data['ri'] ?? '-' }}</td>
                                            <td>{{ isset($data['value']) ? number_format($data['value'], 2, '.', '') : '-' }}</td>
                                            <td>
                                                @if (!empty($data['id']))
                                                    <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#showModal{{ $data['id'] }}" title="Lihat">
                                                        <i class="bi bi-eye"></i>
                                                    </a>

                                                    @if (in_array($userRole, ['admin', 'owner']))
                                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#editModal{{ $data['id'] }}" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </button>
                                                        <form action="{{ route('sensor.destroy', $data['id']) }}" method="POST"
                                                            class="d-inline delete-form"
                                                            onsubmit="return confirm('Yakin ingin menghapus data?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
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
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-3">
                            {{ $dataSensor->links() }}
                        </div>

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
                        <h5 class="modal-title" id="tambahSensorModalLabel">Tambah Data Sensor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="parameter" class="form-label">Parameter</label>
                            <input type="text" id="parameter" name="parameter" class="form-control" required value="{{ old('parameter') }}">
                        </div>
                        <div class="mb-3">
                            <label for="value" class="form-label">Nilai</label>
                            <input type="number" step="0.01" id="value" name="value" class="form-control" required value="{{ old('value') }}">
                        </div>
                        <div class="mb-3">
                            <label for="waktu" class="form-label">Waktu</label>
                            <input type="datetime-local" id="waktu" name="waktu" class="form-control" required value="{{ old('waktu') }}">
                        </div>
                        <div class="mb-3">
                            <label for="ri" class="form-label">Resource Index (RI)</label>
                            <input type="text" id="ri" name="ri" class="form-control" required value="{{ old('ri') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @foreach ($dataSensor as $data)
        @if (!empty($data['id']))
            <!-- Modal Edit Data Sensor -->
            <div class="modal fade" id="editModal{{ $data['id'] }}" tabindex="-1" aria-labelledby="editModalLabel{{ $data['id'] }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('sensor.update', $data['id']) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel{{ $data['id'] }}">Edit Data Sensor</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="parameter_{{ $data['id'] }}" class="form-label">Parameter</label>
                                    <input type="text" id="parameter_{{ $data['id'] }}" name="parameter" class="form-control"
                                        value="{{ old('parameter', $data['parameter']) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="value_{{ $data['id'] }}" class="form-label">Nilai</label>
                                    <input type="number" step="0.01" id="value_{{ $data['id'] }}" name="value" class="form-control"
                                        value="{{ old('value', $data['value']) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="waktu_{{ $data['id'] }}" class="form-label">Waktu</label>
                                    <input type="datetime-local" id="waktu_{{ $data['id'] }}" name="waktu" class="form-control"
                                        value="{{ old('waktu', \Carbon\Carbon::parse($data['time'])->format('Y-m-d\TH:i')) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ri_{{ $data['id'] }}" class="form-label">RI</label>
                                    <input type="text" id="ri_{{ $data['id'] }}" name="ri" class="form-control" value="{{ old('ri', $data['ri']) }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Show Data Sensor -->
            <div class="modal fade" id="showModal{{ $data['id'] }}" tabindex="-1" aria-labelledby="showModalLabel{{ $data['id'] }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="showModalLabel{{ $data['id'] }}">Detail Data Sensor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="list-group list-group-flush text-start">
                                <li class="list-group-item"><strong>Parameter:</strong> {{ $data['parameter'] }}</li>
                                <li class="list-group-item"><strong>Nilai:</strong> {{ number_format($data['value'], 2) }}</li>
                                <li class="list-group-item"><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($data['time'])->format('d-m-Y H:i:s') }}</li>
                                <li class="list-group-item"><strong>Resource Index:</strong> {{ $data['ri'] }}</li>
                                <li class="list-group-item"><strong>Sumber:</strong> {{ isset($data['created_at']) ? 'Database' : 'API' }}</li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- SweetAlert Script (fetch & store data) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fetchBtn = document.getElementById('fetchConfirmBtn');
            if (!fetchBtn) return;
            fetchBtn.addEventListener('click', function () {
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
                            didOpen: () => Swal.showLoading()
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
                                Swal.fire({ icon, title, html: `<pre style="text-align:left">${message}</pre>` })
                                    .then(() => location.reload());
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
