@extends('partials.headerFooter')
@section('content')
@include('partials.sidebar')

<!-- <main id="main" class="main p-4"> -->
    <div class="pagetitle">
        <h1><i class="bi bi-folder-fill"></i> Daftar Proyek</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door-fill"></i> Home</a></li>
                <li class="breadcrumb-item active">Proyek</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-table"></i> Tabel Proyek</h5>
    
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
    
                <!-- Tombol Tambah Proyek -->
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#tambahProyekModal">
                    <i class="bi bi-folder-plus"></i> Tambah Proyek
                </button>
    
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle">
                        <thead class="table-success text-center">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Tanggal</th>
                                <th>Foto Proyek</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proyeks as $index => $proyek)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $proyek->nama }}</td>
                                    <td>{{ $proyek->deskripsi }}</td>
                                    <td>{{ $proyek->tanggal }}</td>
                                    <td>
                                        @if($proyek->foto_proyek)
                                            <img src="{{ asset('storage/' . $proyek->foto_proyek) }}" width="100" alt="Foto Proyek">
                                        @else
                                            <span class="text-muted">Tidak ada foto</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#showProyekModal{{ $proyek->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProyekModal{{ $proyek->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteProyekModal{{ $proyek->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
    
                                <!-- Modal Show Proyek -->
                                <div class="modal fade" id="showProyekModal{{ $proyek->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Proyek</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Nama:</strong> {{ $proyek->nama }}</p>
                                                <p><strong>Deskripsi:</strong> {{ $proyek->deskripsi }}</p>
                                                <p><strong>Tanggal:</strong> {{ $proyek->tanggal }}</p>
                                                <p><strong>Foto Proyek:</strong></p>
                                                @if($proyek->foto_proyek)
                                                    <img src="{{ asset('storage/' . $proyek->foto_proyek) }}" width="100%" >
                                                @else
                                                    <span class="text-muted">Tidak ada foto</span>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                <!-- Modal Edit Proyek -->
                                <div class="modal fade" id="editProyekModal{{ $proyek->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('proyek.update', $proyek->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Proyek</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Proyek</label>
                                                        <input type="text" name="nama" class="form-control" value="{{ $proyek->nama }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Deskripsi</label>
                                                        <textarea name="deskripsi" class="form-control" required>{{ $proyek->deskripsi }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tanggal</label>
                                                        <input type="date" name="tanggal" class="form-control" value="{{ $proyek->tanggal }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Foto Proyek</label>
                                                        <input type="file" name="foto_proyek" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
    
                                <!-- Modal Hapus Proyek -->
                                <div class="modal fade" id="deleteProyekModal{{ $proyek->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus proyek <strong>{{ $proyek->nama }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('proyek.destroy', $proyek->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- Modal Tambah Proyek -->
<div class="modal fade" id="tambahProyekModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('proyek.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Tambah Proyek</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Proyek</label>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama proyek" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi proyek" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Proyek</label>
                        <input type="file" name="foto_proyek" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection