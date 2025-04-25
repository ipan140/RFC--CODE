@extends('partials.headerFooter')

@section('content')
@include('partials.sidebar')

<main id="main" class="main p-4">
    <div class="pagetitle">
        <h1><i class="bi bi-flower1"></i> Data Tanaman</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door-fill"></i> Home</a></li>
                <li class="breadcrumb-item active">Tanaman</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-leaf"></i> Daftar Tanaman
                </h5>

                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#tambahTanamanModal">
                    <i class="bi bi-plus-circle"></i> Tambah Tanaman
                </button>

                <!-- Modal Tambah Tanaman -->
                <div class="modal fade" id="tambahTanamanModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('tanaman.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title">Tambah Tanaman</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Tanaman</label>
                                        <input type="text" name="nama_tanaman" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Tanam</label>
                                        <input type="date" name="tanggal_tanam" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Panjang Daun (cm)</label>
                                        <input type="number" name="panjang_daun" class="form-control" step="0.01" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Lebar Daun (cm)</label>
                                        <input type="number" name="lebar_daun" class="form-control" step="0.01" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Foto</label>
                                        <input type="file" name="foto" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Tanggal Tanam</th>
                                <th>Panjang Daun</th>
                                <th>Lebar Daun</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tanamans as $index => $tanaman)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $tanaman->nama_tanaman }}</td>
                                <td>{{ $tanaman->deskripsi }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($tanaman->tanggal_tanam)->format('Y-m-d H:i') }}
                                </td>
                                <td class="text-center">{{ $tanaman->panjang_daun }} cm</td>
                                <td class="text-center">{{ $tanaman->lebar_daun }} cm</td>
                                <td class="text-center">
                                    @if ($tanaman->foto)
                                        <img src="{{ asset('storage/' . $tanaman->foto) }}" alt="Foto Tanaman" width="60">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $tanaman->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $tanaman->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $tanaman->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Detail -->
                            <div class="modal fade" id="detailModal{{ $tanaman->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Tanaman</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Nama:</strong> {{ $tanaman->nama_tanaman }}</p>
                                            <p><strong>Deskripsi:</strong> {{ $tanaman->deskripsi }}</p>
                                            <p><strong>Tanggal Tanam:</strong> 
                                                {{ \Carbon\Carbon::parse($tanaman->tanggal_tanam)->format('d-m-Y H:i') }}
                                            </p>
                                            <p><strong>Panjang Daun:</strong> {{ $tanaman->panjang_daun }} cm</p>
                                            <p><strong>Lebar Daun:</strong> {{ $tanaman->lebar_daun }} cm</p>
                                            @if ($tanaman->foto)
                                                <img src="{{ asset('storage/' . $tanaman->foto) }}" class="img-fluid" alt="Foto Tanaman">
                                            @else
                                                <p class="text-muted">Tidak ada foto</p>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $tanaman->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('tanaman.update', $tanaman->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Tanaman</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Tanaman</label>
                                                    <input type="text" name="nama_tanaman" class="form-control" value="{{ $tanaman->nama_tanaman }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea name="deskripsi" class="form-control" required>{{ $tanaman->deskripsi }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Tanggal Tanam</label>
                                                    <input type="date" name="tanggal_tanam" class="form-control" value="{{ \Carbon\Carbon::parse($tanaman->tanggal_tanam)->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Panjang Daun (cm)</label>
                                                    <input type="number" name="panjang_daun" class="form-control" value="{{ $tanaman->panjang_daun }}" step="0.01" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Lebar Daun (cm)</label>
                                                    <input type="number" name="lebar_daun" class="form-control" value="{{ $tanaman->lebar_daun }}" step="0.01" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Foto</label>
                                                    <input type="file" name="foto" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Delete -->
                            <div class="modal fade" id="deleteModal{{ $tanaman->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Yakin ingin menghapus tanaman <strong>{{ $tanaman->nama_tanaman }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('tanaman.destroy', $tanaman->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data tanaman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</main>
@endsection
