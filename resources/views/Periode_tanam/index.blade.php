@extends('partials.headerFooter')

@section('content')
@include('partials.sidebar')

<main id="main" class="main p-4">
    <div class="pagetitle d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1><i class="bi bi-calendar-plus"></i> Periode Tanam</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door-fill"></i> Home</a></li>
                    <li class="breadcrumb-item active">Periode Tanam</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bi bi-list-ul"></i> Daftar Periode Tanam</h5>
                <div class="d-flex justify-content-start align-items-center mb-3 flex-wrap gap-2">
                    {{-- Tombol Tambah --}}
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahPeriode">
                        <i class="bi bi-plus-circle"></i> Tambah Tanaman
                    </button>

                    {{-- Filter --}}
                    <form method="GET" action="{{ route('periode_tanam.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
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
                            @forelse ($periode_tanams as $periode)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $periode->tanaman->nama_tanaman ?? '-' }}</td>
                                <td>{{ $periode->nama_periode }}</td>
                                <td>{{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('Y-m-d') }}</td>
                                <td>{{ $periode->tanggal_selesai ? \Carbon\Carbon::parse($periode->tanggal_selesai)->format('Y-m-d') : '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $periode->status === 'Sudah' ? 'primary' : 'warning' }}">
                                        {{ $periode->status }}
                                    </span>
                                </td>
                                <td>{{ $periode->keterangan ?? '-' }}</td>
                                <td class="d-flex justify-content-center gap-1 flex-wrap">
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalLihatPeriode{{ $periode->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditPeriode{{ $periode->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="{{ route('periode_tanam.destroy', $periode->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Modal Lihat --}}
                            <div class="modal fade" id="modalLihatPeriode{{ $periode->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><i class="bi bi-eye"></i> Detail Periode Tanam</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <dl class="row">
                                                <dt class="col-sm-4">Nama Tanaman</dt>
                                                <dd class="col-sm-8">{{ $periode->tanaman->nama_tanaman ?? '-' }}</dd>
                                                <dt class="col-sm-4">Nama Periode</dt>
                                                <dd class="col-sm-8">{{ $periode->nama_periode }}</dd>
                                                <dt class="col-sm-4">Tanggal Mulai</dt>
                                                <dd class="col-sm-8">{{ $periode->tanggal_mulai }}</dd>
                                                <dt class="col-sm-4">Tanggal Selesai</dt>
                                                <dd class="col-sm-8">{{ $periode->tanggal_selesai ?? '-' }}</dd>
                                                <dt class="col-sm-4">Status</dt>
                                                <dd class="col-sm-8">{{ $periode->status }}</dd>
                                                <dt class="col-sm-4">Keterangan</dt>
                                                <dd class="col-sm-8">{{ $periode->keterangan ?? '-' }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Edit --}}
                            <div class="modal fade" id="modalEditPeriode{{ $periode->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('periode_tanam.update', $periode->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Periode Tanam</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Pilih Tanaman</label>
                                                    <select name="tanaman_id" class="form-select" required>
                                                        @foreach ($tanamans as $tanaman)
                                                        <option value="{{ $tanaman->id }}" {{ $periode->tanaman_id == $tanaman->id ? 'selected' : '' }}>
                                                            {{ $tanaman->nama_tanaman }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Periode</label>
                                                    <input type="text" name="nama_periode" class="form-control" value="{{ $periode->nama_periode }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Tanggal Mulai</label>
                                                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ $periode->tanggal_mulai }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Tanggal Selesai</label>
                                                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ $periode->tanggal_selesai }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="Belum" {{ $periode->status == 'Belum' ? 'selected' : '' }}>Belum</option>
                                                        <option value="Sudah" {{ $periode->status == 'Sudah' ? 'selected' : '' }}>Sudah</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Keterangan</label>
                                                    <textarea name="keterangan" class="form-control">{{ $periode->keterangan }}</textarea>
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
                                <td colspan="8" class="text-center">Belum ada data periode tanam.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal Tambah --}}
    <div class="modal fade" id="modalTambahPeriode" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('periode_tanam.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-calendar2-plus"></i> Tambah Periode Tanam</h5>
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