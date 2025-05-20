@extends('partials.headerFooter')

@section('content')
    @include('partials.sidebar')

    {{-- <main id="main" class="main"> --}}
        <div class="pagetitle d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1><i class="bi bi-calendar-plus"></i>Tanaman</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door-fill"></i> Home</a></li>
                        <li class="breadcrumb-item active">Tanaman</li>
                    </ol>
                </nav>
            </div>
        </div>

        <section class="section">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-list-ul"></i> Daftar Tanam</h5>

                    <div class="d-flex justify-content-start align-items-center mb-3 flex-wrap gap-2">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahPeriode">
                            <i class="bi bi-plus-circle"></i> Input Harian
                        </button>

                        <form method="GET" action="{{ route('input_harian.index') }}"
                            class="d-flex align-items-center gap-2 flex-wrap">
                            <select name="filter_tanaman_id" class="form-select w-auto">
                                <option value="">-- Semua Tanaman --</option>
                                @foreach ($tanamans as $tanaman)
                                    <option value="{{ $tanaman->id }}" {{ request('filter_tanaman_id') == $tanaman->id ? 'selected' : '' }}>
                                        {{ $tanaman->nama_tanaman }}
                                    </option>
                                @endforeach
                            </select>
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
                                    <th>Nama Periode</th>
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
                                        <td>{{ $inputHarian->tanaman->nama_tanaman ?? '-' }}</td>
                                        <td>{{ $inputHarian->nama_periode }}</td>
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
                                                <img src="{{ asset('uploads/foto_periode/' . $inputHarian->foto) }}"
                                                    alt="Foto Periode" class="rounded"
                                                    style="width: 60px; height: 60px; object-fit: cover;">
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

                                    {{-- Modal Lihat --}}
                                    <div class="modal fade" id="modalLihatPeriode{{ $inputHarian->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="bi bi-eye"></i> Detail Periode Tanam</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <ul class="list-group">
                                                        <li class="list-group-item"><strong>Tanaman:</strong>
                                                            {{ $inputHarian->tanaman->nama_tanaman ?? '-' }}</li>
                                                        <li class="list-group-item"><strong>Nama Periode:</strong>
                                                            {{ $inputHarian->nama_periode }}</li>
                                                        <li class="list-group-item"><strong>Waktu:</strong>
                                                            {{ $inputHarian->waktu }}</li>
                                                        <li class="list-group-item"><strong>Pupuk:</strong>
                                                            {{ $inputHarian->pupuk }}</li>
                                                        <li class="list-group-item"><strong>Panjang Daun:</strong>
                                                            {{ $inputHarian->panjang_daun }} cm</li>
                                                        <li class="list-group-item"><strong>Lebar Daun:</strong>
                                                            {{ $inputHarian->lebar_daun }} cm</li>
                                                        <li class="list-group-item"><strong>Foto:</strong><br>
                                                            @if ($inputHarian->foto)
                                                                <img src="{{ asset('uploads/foto_periode/' . $inputHarian->foto) }}"
                                                                    alt="Foto" class="img-fluid mt-2">
                                                            @else
                                                                Tidak ada foto.
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Edit --}}
                                    <!-- Modal Edit Input Harian -->
                                    <div class="modal fade" id="editModal{{ $inputHarian->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('input_harian.update', $inputHarian->id) }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="bi bi-pencil-square"></i> Edit Input Tanaman Harian
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body row g-3">
                                                        <div class="col-md-6">
                                                            <label>Tanaman</label>
                                                            <select name="tanaman_id" class="form-select" required>
                                                                <option value="">-- Pilih Tanaman --</option>
                                                                @foreach ($tanamans as $tanaman)
                                                                    <option value="{{ $tanaman->id }}" {{ $tanaman->id == $inputHarian->tanaman_id ? 'selected' : '' }}>
                                                                        {{ $tanaman->nama_tanaman }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Nama Periode</label>
                                                            <input type="text" name="nama_periode" class="form-control"
                                                                value="{{ $inputHarian->nama_periode }}" required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Panjang Daun (cm)</label>
                                                            <input type="number" step="0.01" name="panjang_daun"
                                                                class="form-control" value="{{ $inputHarian->panjang_daun }}"
                                                                required>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Lebar Daun (cm)</label>
                                                            <input type="number" step="0.01" name="lebar_daun"
                                                                class="form-control" value="{{ $inputHarian->lebar_daun }}"
                                                                required>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label>Pupuk / Keterangan</label>
                                                            <textarea name="pupuk" class="form-control"
                                                                rows="2">{{ $inputHarian->pupuk }}</textarea>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label>Foto Baru (jika ingin mengganti)</label>
                                                            <input type="file" name="foto" class="form-control">
                                                            @if ($inputHarian->foto)
                                                                <div class="mt-2">
                                                                    <img src="{{ asset('uploads/foto_periode/' . $inputHarian->foto) }}"
                                                                        alt="Foto" style="width: 100px;">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-warning">Update</button>
                                                    </div>
                                                </form>
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
        <div class="modal fade" id="modalTambahPeriode" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('input_harian.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-calendar2-plus"></i> Tambah Periode Tanam</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-md-6">
                                <label>Tanaman</label>
                                <select name="tanaman_id" class="form-select" required>
                                    <option value="">-- Pilih Tanaman --</option>
                                    @foreach ($tanamans as $tanaman)
                                        <option value="{{ $tanaman->id }}">{{ $tanaman->nama_tanaman }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Nama Periode</label>
                                <input type="text" name="nama_periode" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Panjang Daun (cm)</label>
                                <input type="number" step="0.01" name="panjang_daun" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Lebar Daun (cm)</label>
                                <input type="number" step="0.01" name="lebar_daun" class="form-control" required>
                            </div>
                            <div class="col-md-12">
                                <label>Pupuk / Keterangan</label>
                                <textarea name="pupuk" class="form-control" rows="2"
                                    placeholder="Contoh: NPK 16:16:16 sebanyak 2g/liter"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label>Foto</label>
                                <input type="file" name="foto" class="form-control">
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