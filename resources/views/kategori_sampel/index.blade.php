@extends('partials.headerFooter')

@section('content')
    @include('partials.sidebar')

    <div class="pagetitle d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1><i class="bi bi-list-task"></i> Kategori Sampel</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door-fill"></i> Home</a></li>
                    <li class="breadcrumb-item active">Kategori Sampel</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-fluid px-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section p-3 bg-white rounded shadow-sm mb-4">
            {{-- Filter dan Tombol Tambah --}}
            <div class="d-flex justify-content-start align-items-center flex-wrap gap-2 mb-3">
                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahKategoriModal">
                    <i class="bi bi-plus-circle"></i> Tambah Kategori
                </a>

                <form method="GET" action="{{ route('kategori_sampel.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
                    <select name="periode_tanam_id" class="form-select w-auto">
                        <option value="">-- Pilih Periode Tanam --</option>
                        @foreach ($periodeTanams as $periode)
                            <option value="{{ $periode->id }}">{{ $periode->nama_tanaman ?? 'Periode ' . $periode->id }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>
                    <a href="{{ route('kategori_sampel.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </form>
            </div>

            {{-- Tabel Data --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Periode Tanam</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoriSampels as $kategori)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $kategori->periodeTanam->nama_tanaman ?? '-' }}</td>
                                <td>{{ $kategori->nama }}</td>
                                <td>{{ $kategori->deskripsi }}</td>
                                <td>
                                    <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalLihatKategori{{ $kategori->id }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editKategoriModal"
                                       data-id="{{ $kategori->id }}" data-nama="{{ $kategori->nama }}"
                                       data-deskripsi="{{ $kategori->deskripsi }}" data-periode_tanam_id="{{ $kategori->periode_tanam_id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusKategoriModal"
                                            data-id="{{ $kategori->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Modal Tambah -->
<div class="modal fade" id="tambahKategoriModal" tabindex="-1" aria-labelledby="tambahKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kategori_sampel.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKategoriModalLabel">Tambah Kategori Sampel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Periode Tanam --}}
                    <div class="mb-3">
                        <label for="periode_tanam_id" class="form-label">Periode Tanam</label>
                       <select name="periode_tanam_id" id="periode_tanam_id" class="form-select" required>
                            <option value="">-- Pilih Periode Tanam --</option>
                            @foreach ($periodeTanams as $periode)
                                <option value="{{ $periode->id }}">{{ $periode->nama_tanaman ?? 'Periode ' . $periode->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Nama Kategori --}}
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kategori</label>
                        <input type="text" id="nama" name="nama" class="form-control" required>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <!-- Modal Edit -->
    <div class="modal fade" id="editKategoriModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editKategoriForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kategori Sampel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama" id="edit-nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="edit-deskripsi" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Periode Tanam</label>
                            <select name="periode_tanam_id" id="edit-periode_tanam" class="form-select" required>
                                <option value="">-- Pilih Periode Tanam --</option>
                                @foreach ($periodeTanams as $periodeTanam)
                                    <option value="{{ $periodeTanam->id }}">{{ $periodeTanam->nama_tanaman ?? 'Periode '.$periodeTanam->id }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="hapusKategoriModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="hapusKategoriForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Kategori Sampel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus kategori ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk modal Edit dan Hapus --}}
    <script>
        var editModal = document.getElementById('editKategoriModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var nama = button.getAttribute('data-nama');
            var deskripsi = button.getAttribute('data-deskripsi');
            var periodeTanamId = button.getAttribute('data-periode_tanam_id');

            var form = editModal.querySelector('#editKategoriForm');
            form.action = '/kategori_sampel/' + id;
            form.querySelector('#edit-nama').value = nama;
            form.querySelector('#edit-deskripsi').value = deskripsi;
            form.querySelector('#edit-periode_tanam').value = periodeTanamId;
        });

        var hapusModal = document.getElementById('hapusKategoriModal');
        hapusModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');

            var form = hapusModal.querySelector('#hapusKategoriForm');
            form.action = '/kategori_sampel/' + id;
        });
    </script>
@endsection
