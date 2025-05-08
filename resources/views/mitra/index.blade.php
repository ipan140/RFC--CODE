@extends('partials.headerFooter')
@section('content')
@include('partials.sidebar')

<!-- <main id="main" class="main p-4"> -->
    <div class="pagetitle">
        <h1><i class="bi bi-people-fill"></i> Mitra yang Join</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door-fill"></i> Home</a></li>
                <li class="breadcrumb-item active">Mitra</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-table"></i> Tabel Mitra</h5>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Tombol Tambah Mitra -->
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#tambahMitraModal">
                    <i class="bi bi-person-plus-fill"></i> Tambah Mitra
                </button>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle">
                        <thead class="table-success text-center">
                            <tr>
                                <th>#</th>
                                <th>Nama Mitra</th>
                                <th>Lokasi</th>
                                <th>Email</th>
                                <th>Nomor</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mitras as $index => $mitra)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $mitra->nama }}</td>
                                    <td>{{ $mitra->lokasi }}</td>
                                    <td>{{ $mitra->email }}</td>
                                    <td>{{ $mitra->telepon }}</td>
                                    <td class="text-center">
                                        @if($mitra->foto_mitra)
                                            <img src="{{ asset('storage/' . $mitra->foto_mitra) }}" width="60" class="rounded">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#showMitraModal{{ $mitra->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editMitraModal{{ $mitra->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteMitraModal{{ $mitra->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Show Mitra -->
                                <div class="modal fade" id="showMitraModal{{ $mitra->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Mitra</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Nama Mitra:</strong> {{ $mitra->nama }}</p>
                                                <p><strong>Lokasi:</strong> {{ $mitra->lokasi }}</p>
                                                <p><strong>Email:</strong> {{ $mitra->email }}</p>
                                                <p><strong>Nomor:</strong> {{ $mitra->telepon }}</p>
                                                @if($mitra->foto_mitra)
                                                    <p><strong>Foto Mitra:</strong></p>
                                                    <img src="{{ asset('storage/' . $mitra->foto_mitra) }}" class="img-fluid rounded" style="max-width: 200px;">
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Edit Mitra -->
                                <div class="modal fade" id="editMitraModal{{ $mitra->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('mitra.update', $mitra->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Mitra</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Mitra</label>
                                                        <input type="text" name="nama" class="form-control" value="{{ $mitra->nama }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Lokasi</label>
                                                        <input type="text" name="lokasi" class="form-control" value="{{ $mitra->lokasi }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $mitra->email }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Nomor Telepon</label>
                                                        <input type="text" name="telepon" class="form-control" value="{{ $mitra->telepon }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Foto Mitra (Opsional)</label>
                                                        <input type="file" name="foto_mitra" class="form-control" accept="image/*">
                                                        @if($mitra->foto_mitra)
                                                            <small class="d-block mt-2">Foto saat ini:</small>
                                                            <img src="{{ asset('storage/' . $mitra->foto_mitra) }}" class="img-thumbnail mt-1" style="max-width: 120px;">
                                                        @endif
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

                                <!-- Modal Hapus Mitra -->
                                <div class="modal fade" id="deleteMitraModal{{ $mitra->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus mitra <strong>{{ $mitra->nama }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('mitra.destroy', $mitra->id) }}" method="POST" class="d-inline">
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

<!-- Modal Tambah Mitra -->
<div class="modal fade" id="tambahMitraModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('mitra.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mitra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="nama" class="form-control mb-3" placeholder="Nama Mitra" required>
                    <input type="text" name="lokasi" class="form-control mb-3" placeholder="Lokasi" required>
                    <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                    <input type="text" name="telepon" class="form-control mb-3" placeholder="Nomor Telepon" required>
                    <input type="file" name="foto_mitra" class="form-control" accept="image/*">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
