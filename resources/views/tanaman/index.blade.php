@extends('partials.headerFooter')

@section('content')
@include('partials.sidebar')

<div class="pagetitle">
    <h1><i class="bi bi-flower1"></i> Periode Tanam</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door-fill"></i> Home</a></li>
            <li class="breadcrumb-item active">Periode Tanam</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-leaf"></i> Periode Tanam</h5>

            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#tambahTanamanModal">
                <i class="bi bi-plus-circle"></i> Tambah Tanaman
            </button>

            {{-- Modal Tambah --}}
            <div class="modal fade" id="tambahTanamanModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('tanaman.store') }}" method="POST">
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
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="on going">On Going</option>
                                        <option value="selesai">Selesai</option>
                                    </select>
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

            {{-- Tabel Tanaman --}}
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Tanggal Tanam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tanamans as $index => $tanaman)
                        <tr>
                            <td class="text-center">{{ $tanamans->firstItem() + $index }}</td>
                            <td>{{ $tanaman->nama_tanaman }}</td>
                            <td>{{ $tanaman->deskripsi }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($tanaman->tanggal_tanam)->format('Y-m-d') }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $tanaman->status == 'on going' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($tanaman->status) }}
                                </span>
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

                        {{-- Modal Detail --}}
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
                                            {{ \Carbon\Carbon::parse($tanaman->tanggal_tanam)->format('d-m-Y') }}
                                        </p>
                                        <p><strong>Status:</strong> {{ ucfirst($tanaman->status) }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="editModal{{ $tanaman->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('tanaman.update', $tanaman->id) }}" method="POST">
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
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-control" required>
                                                    <option value="on going" {{ $tanaman->status == 'on going' ? 'selected' : '' }}>On Going</option>
                                                    <option value="selesai" {{ $tanaman->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                </select>
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

                        {{-- Modal Hapus --}}
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
                            <td colspan="6" class="text-center">Belum ada data tanaman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $tanamans->withQueryString()->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</section>
@endsection
