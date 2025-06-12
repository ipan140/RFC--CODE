@extends('partials.headerFooter')

@section('content')
    @include('partials.sidebar')

    {{-- <main id="main" class="main"> --}}
        <div class="pagetitle d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1><i class="bi bi-calendar-plus"></i> Input Harian tanaman</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door-fill"></i> Home</a></li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('tanaman.index') }}">
                                <i class="bi bi-house-door-fill"></i> Tanaman
                            </a>
                        </li>
                        <li class="breadcrumb-item active"> Input Harian tanaman</li>
                    </ol>
                </nav>
            </div>
        </div>
        <section class="section">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-list-ul"></i> Input Harian tanaman</h5>
                    <div class="d-flex justify-content-start align-items-center mb-3 flex-wrap gap-2">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahPeriode">
                            <i class="bi bi-plus-circle"></i> Input Harian tanaman
                        </button>
                        <form method="GET" action="{{ route('input_harian.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
                            <div class="input-group w-auto">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar-date"></i>
                                </span>
                                <input type="date" name="tanggal_awal" class="form-control"
                                    value="{{ request('tanggal_awal') }}" placeholder="Tanggal Awal">
                            </div>

                            <div class="input-group w-auto">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar-date-fill"></i>
                                </span>
                                <input type="date" name="tanggal_akhir" class="form-control"
                                    value="{{ request('tanggal_akhir') }}" placeholder="Tanggal Akhir">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel-fill"></i> Filter
                            </button>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>NO</th>
                                    <th>Nama Tanaman</th>
                                    <th>Kategori Sampel</th>
                                    <th>Tanggal Mulai & waktu</th>
                                    <th>Pupuk / Keterangan</th>
                                    <th>Panjang Daun (cm)</th>
                                    <th>Lebar Daun (cm)</th>
                                    <th>Temp (°C)</th>
                                    <th>Humidity (%)</th>
                                    <th>pH</th>
                                    <th>EC</th>
                                    <th>Nitrogen</th>
                                    <th>Fosfor</th>
                                    <th>Potasium</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inputHarians as $inputHarian)
                                    <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $inputHarian->periodeTanam->nama_tanaman ?? '-' }}</td>
                                    <td>{{ $inputHarian->kategoriSampel->nama ?? '-' }}</td>
                                    <td>{{ $inputHarian->waktu ?? '-' }}</td>
                                    <td>{{ $inputHarian->pupuk ?? '-' }}</td>
                                    <td>{{ $inputHarian->panjang_daun ?? '-' }}</td>
                                    <td>{{ $inputHarian->lebar_daun ?? '-' }}</td>
                                    <td>{{ $inputHarian->temp ?? '-' }}</td>
                                    <td>{{ $inputHarian->humidity ?? '-' }}</td>
                                    <td>{{ $inputHarian->ph ?? '-' }}</td>
                                    <td>{{ $inputHarian->EC ?? '-' }}</td>
                                    <td>{{ $inputHarian->Nitrogen ?? '-' }}</td>
                                    <td>{{ $inputHarian->phospor ?? '-' }}</td>
                                    <td>{{ $inputHarian->pota ?? '-' }}</td>
                                    <td>
                                        @if ($inputHarian->foto)
                                            <img src="{{ asset('storage/' . $inputHarian->foto) }}" alt="Foto Sampel" style="max-width: 200px;">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="d-flex justify-content-center gap-1 flex-wrap">
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#modalLihatPeriode{{ $inputHarian->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $inputHarian->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger btn-hapus-swal"
                                            data-id="{{ $inputHarian->id }}" data-nama="{{ $inputHarian->nama_periode }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                    <!-- Modal Lihat Detail Periode Tanam -->
                                    <div class="modal fade" id="modalLihatPeriode{{ $inputHarian->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $inputHarian->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel{{ $inputHarian->id }}">
                                                        <i class="bi bi-eye"></i> Detail Periode Tanam
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <strong>{{ $inputHarian->periodeTanam->nama_tanaman ?? '-' }}</strong>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Waktu:</strong> {{ $inputHarian->waktu }}
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Kategori Sampel:</strong> {{ $inputHarian->kategoriSampel->nama ?? '-' }}
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Pupuk / Keterangan:</strong> {{ $inputHarian->pupuk ?? '-' }}
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Panjang Daun:</strong> {{ $inputHarian->panjang_daun }} cm
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Lebar Daun:</strong> {{ $inputHarian->lebar_daun }} cm
                                                        </li>

                                                        {{-- Data Sensor --}}
                                                        <li class="list-group-item"><strong>pH:</strong> {{ $inputHarian->ph ?? 'Tidak ada' }}</li>
                                                        <li class="list-group-item"><strong>Potassium:</strong> {{ $inputHarian->pota ?? 'Tidak ada' }}</li>
                                                        <li class="list-group-item"><strong>Phosphor:</strong> {{ $inputHarian->phospor ?? 'Tidak ada' }}</li>
                                                        <li class="list-group-item"><strong>EC:</strong> {{ $inputHarian->EC ?? 'Tidak ada' }}</li>
                                                        <li class="list-group-item"><strong>Nitrogen:</strong> {{ $inputHarian->Nitrogen ?? 'Tidak ada' }}</li>
                                                        <li class="list-group-item"><strong>Kelembaban:</strong> {{ $inputHarian->humidity ?? 'Tidak ada' }}%</li>
                                                        <li class="list-group-item"><strong>Suhu:</strong> {{ $inputHarian->temp ?? 'Tidak ada' }}°C</li>

                                                        {{-- Foto --}}
                                                        <li class="list-group-item">
                                                            <strong>Foto:</strong><br>
                                                            @if ($inputHarian->foto)
                                                                <img src="{{ asset('storage/' . $inputHarian->foto) }}" alt="Foto" class="img-fluid mt-2 rounded" style="max-width: 300px;">
                                                            @else
                                                                Tidak ada foto.
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Edit Input Harian -->
                                <div class="modal fade" id="editModal{{ $inputHarian->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('input_harian.update', $inputHarian->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-pencil-square"></i> Edit Input Tanaman Harian
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body row g-3">
                                                   <div class="col-md-6">
                                                        <label>Periode Tanam</label>
                                                        @php
                                                            $selectedPeriodeId = request('periode_tanam_id');
                                                            $selectedPeriode = $periodeTanams->firstWhere('id', $selectedPeriodeId);
                                                        @endphp

                                                        <div class="mb-3">
                                                            <input 
                                                                type="text"
                                                                id="periode_tanam_display"
                                                                class="form-control"
                                                                value="{{ $selectedPeriode ? ($selectedPeriode->nama_tanaman ?? 'Periode ' . $selectedPeriode->id) : 'Belum dipilih' }}"
                                                                readonly
                                                            >
                                                            <input type="hidden" name="periode_tanam_id" value="{{ $selectedPeriodeId }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Waktu</label>
                                                        <input type="datetime-local" class="form-control"
                                                            value="{{ date('Y-m-d', strtotime($inputHarian->waktu)) . 'T00:00' }}" disabled>
                                                        <input type="hidden" name="waktu"
                                                            value="{{ date('Y-m-d', strtotime($inputHarian->waktu)) . 'T00:00' }}">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Panjang Daun (cm)</label>
                                                        <input type="number" step="0.01" name="panjang_daun" class="form-control"
                                                            value="{{ $inputHarian->panjang_daun }}" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Lebar Daun (cm)</label>
                                                        <input type="number" step="0.01" name="lebar_daun" class="form-control"
                                                            value="{{ $inputHarian->lebar_daun }}" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>pH</label>
                                                        <input type="number" step="0.01" class="form-control" 
                                                            value="{{ $inputHarian->ph }}" disabled>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Potasium (pota)</label>
                                                        <input type="number" step="0.01" class="form-control" 
                                                            value="{{ $inputHarian->pota }}" disabled>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Phospor</label>
                                                        <input type="number" step="0.01" class="form-control" 
                                                            value="{{ $inputHarian->phospor }}" disabled>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>EC</label>
                                                        <input type="number" step="0.01" class="form-control" 
                                                            value="{{ $inputHarian->EC }}" disabled>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Nitrogen</label>
                                                        <input type="number" step="0.01" class="form-control" 
                                                            value="{{ $inputHarian->Nitrogen }}" disabled>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Humidity</label>
                                                        <input type="number" step="0.01" class="form-control" 
                                                            value="{{ $inputHarian->humidity }}" disabled>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Temperature (temp)</label>
                                                        <input type="number" step="0.01" class="form-control" 
                                                            value="{{ $inputHarian->temp }}" disabled>
                                                    </div>

                                                    <div class="col-md-12">
                                                        @php
                                                            // Cek apakah ada kategori_sampel_id di URL
                                                            $selectedKategoriId = request()->get('kategori_sampel_id');

                                                            // Cari kategori berdasarkan ID yang dikirim
                                                            $selectedKategori = $kategoriSampels->firstWhere('id', $selectedKategoriId);
                                                        @endphp

                                                        <div class="mb-3">
                                                            <label for="kategori_sampel_display" class="form-label fw-bold">Kategori Sampel</label>
                                                            <input type="text"
                                                                id="kategori_sampel_display"
                                                                class="form-control"
                                                                value="{{ $selectedKategori ? ($selectedKategori->nama . ' - ' . ($selectedKategori->periodeTanam->nama_tanaman ?? '')) : 'Belum dipilih' }}"
                                                                readonly>

                                                            <input type="hidden" name="kategori_sampel_id" value="{{ $selectedKategori?->id }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label>Pupuk / Keterangan</label>
                                                        <textarea name="pupuk" class="form-control" rows="2">{{ $inputHarian->pupuk }}</textarea>
                                                    </div>

                                                    <div class="col-md-12">
                                                       <label for="foto" class="form-label">Foto Baru (jika ingin mengganti)</label>
                                                        <input type="file" name="foto" id="foto" class="form-control mb-3">

                                                        <li class="list-group-item">
                                                            <strong>Foto Saat Ini:</strong><br>
                                                            @if ($inputHarian->foto)
                                                                <img src="{{ asset('storage/' . $inputHarian->foto) }}" alt="Foto" class="img-fluid mt-2 rounded" style="max-width: 300px;">
                                                            @else
                                                                <p class="text-muted">Tidak ada foto.</p>
                                                            @endif
                                                        </li>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                </div>
                                @empty
                                    <tr>
                                        <td colspan="16" class="text-center">Belum ada data periode tanam.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $inputHarians->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </section>

        {{-- Modal Tambah --}}
        <div class="modal fade" id="modalTambahPeriode" tabindex="-1" aria-labelledby="modalTambahPeriodeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('input_harian.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahPeriodeLabel"><i class="bi bi-calendar2-plus"></i> Tambah Periode Tanam</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            @php
                                // Cek apakah ada periode_tanam_id di URL
                                $selectedPeriodeId = request()->get('periode_tanam_id');

                                // Cari periode berdasarkan ID yang dikirim
                                $selectedPeriode = $periodeTanams->firstWhere('id', $selectedPeriodeId);
                            @endphp

                            <div class="mb-3">
                                <label for="periode_tanam_display" class="form-label fw-bold">Periode Tanam</label>
                                <input type="text"
                                    id="periode_tanam_display"
                                    class="form-control"
                                    value="{{ $selectedPeriode ? ($selectedPeriode->nama_tanaman ?? 'Periode ' . $selectedPeriode->id) : 'Belum dipilih' }}"
                                    readonly>

                                <input type="hidden" name="periode_tanam_id" value="{{ $selectedPeriode?->id }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="waktu" class="form-label">Tanggal Periode</label>
                            <input type="date" name="waktu" id="waktu" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="panjang_daun" class="form-label">Panjang Daun (cm)</label>
                            <input type="number" step="0.01" name="panjang_daun" id="panjang_daun" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="lebar_daun" class="form-label">Lebar Daun (cm)</label>
                            <input type="number" step="0.01" name="lebar_daun" id="lebar_daun" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label for="pupuk" class="form-label">Pupuk / Keterangan</label>
                            <textarea name="pupuk" id="pupuk" class="form-control" rows="2" placeholder="Contoh: NPK 16:16:16 sebanyak 2g/liter"></textarea>
                        </div>
                        <div class="col-md-12">
                            @php
                                // Cek apakah ada kategori_sampel_id di URL
                                $selectedKategoriId = request()->get('kategori_sampel_id');

                                // Cari kategori berdasarkan ID yang dikirim
                                $selectedKategori = $kategoriSampels->firstWhere('id', $selectedKategoriId);
                            @endphp

                            <div class="mb-3">
                                <label for="kategori_sampel_display" class="form-label fw-bold">Kategori Sampel</label>
                                <input type="text"
                                    id="kategori_sampel_display"
                                    class="form-control"
                                    value="{{ $selectedKategori ? ($selectedKategori->nama . ' - ' . ($selectedKategori->periodeTanam->nama_tanaman ?? '')) : 'Belum dipilih' }}"
                                    readonly>

                                <input type="hidden" name="kategori_sampel_id" value="{{ $selectedKategori?->id }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah Periode</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </div>
        {{-- Hidden form untuk delete --}}
        <form id="formDelete" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </main>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-hapus-swal');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data '" + nama + "' akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('formDelete');
                            form.setAttribute('action', `/input_harian/${id}`);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif

@endsection