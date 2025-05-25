@extends('partials.headerFooter')
@section('content')
@include('partials.sidebar')

<div class="pagetitle">
    <h1><i class="bi bi-calendar3"></i> Periode Tanam</h1>
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
            <h5 class="card-title"><i class="bi bi-calendar3"></i> Data Periode Tanam</h5>

            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#tambahPeriodeModal">
                <i class="bi bi-plus-circle"></i> Tambah Periode
            </button>

            {{-- Modal Tambah --}}
            <div class="modal fade" id="tambahPeriodeModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('periode_tanam.store') }}" method="POST">
                            @csrf
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">Tambah Periode Tanam</h5>
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

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>Nama Tanaman</th>
                            <th>Deskripsi</th>
                            <th>Tanggal Tanam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($periodeTanams as $index => $periode)
                        <tr>
                            <td class="text-center">{{ $periodeTanams->firstItem() + $index }}</td>
                            <td>{{ $periode->nama_tanaman }}</td>
                            <td>{{ $periode->deskripsi }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($periode->tanggal_tanam)->format('Y-m-d') }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $periode->status == 'on going' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($periode->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $periode->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $periode->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $periode->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Modal Detail --}}
                        <div class="modal fade" id="detailModal{{ $periode->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Periode Tanam</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Nama:</strong> {{ $periode->nama_tanaman }}</p>
                                        <p><strong>Deskripsi:</strong> {{ $periode->deskripsi }}</p>
                                        <p><strong>Tanggal Tanam:</strong>
                                            {{ \Carbon\Carbon::parse($periode->tanggal_tanam)->format('d-m-Y') }}
                                        </p>
                                        <p><strong>Status:</strong> {{ ucfirst($periode->status) }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="editModal{{ $periode->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('periode_tanam.update', $periode->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Periode Tanam</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Tanaman</label>
                                                <input type="text" name="nama_tanaman" class="form-control" value="{{ $periode->nama_tanaman }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Deskripsi</label>
                                                <textarea name="deskripsi" class="form-control" required>{{ $periode->deskripsi }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Tanam</label>
                                                <input type="date" name="tanggal_tanam" class="form-control" value="{{ \Carbon\Carbon::parse($periode->tanggal_tanam)->format('Y-m-d') }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-control" required>
                                                    <option value="on going" {{ $periode->status == 'on going' ? 'selected' : '' }}>On Going</option>
                                                    <option value="selesai" {{ $periode->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
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
                        <div class="modal fade" id="deleteModal{{ $periode->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Yakin ingin menghapus periode tanam <strong>{{ $periode->nama_tanaman }}</strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('periode_tanam.destroy', $periode->id) }}" method="POST">
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
                            <td colspan="6" class="text-center">Belum ada data periode tanam.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $periodeTanams->withQueryString()->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</section>
@endsection
