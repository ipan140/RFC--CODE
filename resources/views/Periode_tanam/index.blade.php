@extends('partials.headerFooter')

@section('content')
@include('partials.sidebar')

<!-- <main id="main" class="main p-4"> -->
    <div class="pagetitle d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1><i class="bi bi-calendar-plus"></i> Tanaman</h1>
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
                        <i class="bi bi-plus-circle"></i> Tambah Tanaman
                    </button>

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
                            @forelse ($periode_tanams as $periode)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $periode->tanaman->nama_tanaman ?? '-' }}</td>
                                <td>{{ $periode->nama_periode }}</td>
                                <td>{{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $periode->pupuk ?? '-' }}</td>
                                <td>{{ $periode->panjang_daun ?? '-' }}</td>
                                <td>{{ $periode->lebar_daun ?? '-' }}</td>
                                <td>{{ $periode->temp ?? '-' }}</td>
                                <td>{{ $periode->humidity ?? '-' }}</td>
                                <td>{{ $periode->ph ?? '-' }}</td>
                                <td>{{ $periode->EC ?? '-' }}</td>
                                <td>{{ $periode->Nitrogen ?? '-' }}</td>
                                <td>{{ $periode->phospor ?? '-' }}</td>
                                <td>{{ $periode->pota ?? '-' }}</td>
                                <td>
                                    @if ($periode->foto)
                                    <img src="{{ asset('uploads/foto_periode/' . $periode->foto) }}" alt="Foto Periode"
                                        class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="d-flex justify-content-center gap-1 flex-wrap">
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalLihatPeriode{{ $periode->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $periode->id }}">
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
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <dl class="row">
                                                <dt class="col-sm-4">Nama Tanaman</dt>
                                                <dd class="col-sm-8">{{ $periode->tanaman->nama_tanaman ?? '-' }}</dd>

                                                <dt class="col-sm-4">Nama Periode</dt>
                                                <dd class="col-sm-8">{{ $periode->nama_periode }}</dd>

                                                <dt class="col-sm-4">Tanggal Mulai & Waktu</dt>
                                                <dd class="col-sm-8">{{ $periode->created_at }}</dd>

                                                <dt class="col-sm-4">Panjang Daun</dt>
                                                <dd class="col-sm-8">{{ $periode->panjang_daun ?? '-' }} cm</dd>

                                                <dt class="col-sm-4">Lebar Daun</dt>
                                                <dd class="col-sm-8">{{ $periode->lebar_daun ?? '-' }} cm</dd>

                                                <dt class="col-sm-4">Temp (°C)</dt>
                                                <dd class="col-sm-8">{{ $periode->temp ?? '-' }}</dd>

                                                <dt class="col-sm-4">Humidity (%)</dt>
                                                <dd class="col-sm-8">{{ $periode->humidity ?? '-' }}</dd>

                                                <dt class="col-sm-4">pH</dt>
                                                <dd class="col-sm-8">{{ $periode->ph ?? '-' }}</dd>

                                                <dt class="col-sm-4">EC</dt>
                                                <dd class="col-sm-8">{{ $periode->EC ?? '-' }}</dd>

                                                <dt class="col-sm-4">Nitrogen</dt>
                                                <dd class="col-sm-8">{{ $periode->Nitrogen ?? '-' }}</dd>

                                                <dt class="col-sm-4">Fosfor</dt>
                                                <dd class="col-sm-8">{{ $periode->phospor ?? '-' }}</dd>

                                                <dt class="col-sm-4">Kalium</dt>
                                                <dd class="col-sm-8">{{ $periode->pota ?? '-' }}</dd>

                                                <dt class="col-sm-4">Pupuk / Keterangan</dt>
                                                <dd class="col-sm-8">{{ $periode->pupuk ?? '-' }}</dd>

                                                <dt class="col-sm-4">Foto</dt>
                                                <dd class="col-sm-8">
                                                    @if ($periode->foto)
                                                    <img src="{{ asset('uploads/foto_periode/' . $periode->foto) }}"
                                                        style="width: 150px; height: 150px; object-fit: cover;">
                                                    @else
                                                    <span>-</span>
                                                    @endif
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{-- Modal Edit --}}
                            <div class="modal fade" id="editModal{{ $periode->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('periode_tanam.update', $periode->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Periode Tanam</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body row g-3">
                                                <div class="col-md-6">
                                                    <label>Tanaman</label>
                                                    <select name="tanaman_id" class="form-select" required>
                                                        @foreach ($tanamans as $tanaman)
                                                        <option value="{{ $tanaman->id }}" {{ $periode->tanaman_id == $tanaman->id ? 'selected' : '' }}>
                                                            {{ $tanaman->nama_tanaman }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Nama Periode</label>
                                                    <input type="text" name="nama_periode" class="form-control" value="{{ $periode->nama_periode }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Panjang Daun (cm)</label>
                                                    <input type="number" name="panjang_daun" step="0.01" class="form-control" value="{{ $periode->panjang_daun }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Lebar Daun (cm)</label>
                                                    <input type="number" name="lebar_daun" step="0.01" class="form-control" value="{{ $periode->lebar_daun }}" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Pupuk / Keterangan</label>
                                                    <textarea name="pupuk" class="form-control" rows="2">{{ $periode->pupuk }}</textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Foto (opsional)</label>
                                                    <input type="file" name="foto" class="form-control">
                                                    @if ($periode->foto)
                                                    <small class="text-muted">Foto saat ini: {{ $periode->foto }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
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
            </div>
        </div>
    </section>

    {{-- Modal Tambah --}}
    <div class="modal fade" id="modalTambahPeriode" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('periode_tanam.store') }}" method="POST" enctype="multipart/form-data">
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
                            <textarea name="pupuk" class="form-control" rows="2" placeholder="Contoh: NPK 16:16:16 sebanyak 2g/liter"></textarea>
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
</main>
@endsection