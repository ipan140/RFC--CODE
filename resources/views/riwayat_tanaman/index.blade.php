@extends('partials.headerFooter')

@section('content')
@include('partials.sidebar')

<main id="main" class="main p-4">
    <div class="pagetitle d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1><i class="bi bi-calendar-plus"></i> Riwayat Tanam</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door-fill"></i> Home</a></li>
                    <li class="breadcrumb-item active">Riwayat Tanam</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bi bi-list-ul"></i> Daftar Riwayat Tanam</h5>
                <div class="d-flex justify-content-start align-items-center mb-3 flex-wrap gap-2">
                    {{-- Tombol Tambah --}}
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahRiwayat">
                        <i class="bi bi-plus-circle"></i> Tambah Riwayat Tanam
                    </button>

                    {{-- Filter --}}
                    <form method="GET" action="{{ route('riwayat_tanaman.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
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
                                <th>#</th>
                                <th>Nama Tanaman</th>
                                <th>Nama Periode</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayat_tanams as $riwayat)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $riwayat->tanaman->nama_tanaman ?? '-' }}</td>
                                    <td>{{ $riwayat->nama_periode }}</td>
                                    <td>{{ \Carbon\Carbon::parse($riwayat->tanggal_mulai)->format('Y-m-d') }}</td>
                                    <td>{{ $riwayat->tanggal_selesai ? \Carbon\Carbon::parse($riwayat->tanggal_selesai)->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $riwayat->status === 'Sudah' ? 'primary' : 'warning' }}">
                                            {{ $riwayat->status }}
                                        </span>
                                    </td>
                                    <td>{{ $riwayat->keterangan ?? '-' }}</td>
                                    <td class="d-flex justify-content-center gap-1 flex-wrap">
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalLihatRiwayat{{ $riwayat->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditRiwayat{{ $riwayat->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('riwayat_tanaman.destroy', $riwayat->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- Modal Lihat --}}
                                <div class="modal fade" id="modalLihatRiwayat{{ $riwayat->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="bi bi-eye"></i> Detail Riwayat Tanam</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <dl class="row">
                                                    <dt class="col-sm-4">Nama Tanaman</dt>
                                                    <dd class="col-sm-8">{{ $riwayat->tanaman->nama_tanaman ?? '-' }}</dd>
                                                    <dt class="col-sm-4">Nama Periode</dt>
                                                    <dd class="col-sm-8">{{ $riwayat->nama_periode }}</dd>
                                                    <dt class="col-sm-4">Tanggal Mulai</dt>
                                                    <dd class="col-sm-8">{{ $riwayat->tanggal_mulai }}</dd>
                                                    <dt class="col-sm-4">Tanggal Selesai</dt>
                                                    <dd class="col-sm-8">{{ $riwayat->tanggal_selesai ?? '-' }}</dd>
                                                    <dt class="col-sm-4">Status</dt>
                                                    <dd class="col-sm-8">{{ $riwayat->status }}</dd>
                                                    <dt class="col-sm-4">Keterangan</dt>
                                                    <dd class="col-sm-8">{{ $riwayat->keterangan ?? '-' }}</dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Edit --}}
                                <div class="modal fade" id="modalEditRiwayat{{ $riwayat->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('riwayat_tanaman.update', $riwayat->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Riwayat Tanam</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Pilih Tanaman</label>
                                                        <select name="tanaman_id" class="form-select" required>
                                                            @foreach ($tanamans as $tanaman)
                                                            <option value="{{ $tanaman->id }}" {{ $riwayat->tanaman_id == $tanaman->id ? 'selected' : '' }}>
                                                                {{ $tanaman->nama_tanaman }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Periode</label>
                                                        <input type="text" name="nama_periode" class="form-control" value="{{ $riwayat->nama_periode }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tanggal Mulai</label>
                                                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ $riwayat->tanggal_mulai }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tanggal Selesai</label>
                                                        <input type="date" name="tanggal_selesai" class="form-control" value="{{ $riwayat->tanggal_selesai }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="Belum" {{ $riwayat->status == 'Belum' ? 'selected' : '' }}>Belum</option>
                                                            <option value="Sudah" {{ $riwayat->status == 'Sudah' ? 'selected' : '' }}>Sudah</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Keterangan</label>
                                                        <textarea name="keterangan" class="form-control">{{ $riwayat->keterangan }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data riwayat tanam.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal Tambah --}}
    <div class="modal fade" id="modalTambahRiwayat" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('riwayat_tanaman.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-calendar2-plus"></i> Tambah Riwayat Tanam</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Tanaman</label>
                            <select name="tanaman_id" class="form-select" required>
                                <option value="">-- Pilih Tanaman --</option>
                                @foreach ($tanamans as $tanaman)
                                    <option value="{{ $tanaman->id }}">{{ $tanaman->nama_tanaman }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Periode</label>
                            <input type="text" name="nama_periode" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Belum">Belum</option>
                                <option value="Sudah">Sudah</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"></textarea>
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
</main>
@endsection
