@extends('partials.headerFooter')

@section('content')
    @include('partials.sidebar')

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

                <div class="d-flex flex-wrap align-items-end gap-3 mb-4">
                <form method="GET" action="{{ route('riwayat_tanaman.index') }}" class="row g-3 align-items-end flex-grow-1">

                    <div class="col-md-3">
                        <label for="filter_periode_id">Periode Tanam</label>
                        <select name="filter_periode_id" id="filter_periode_id" class="form-select" required>
                            <option value="" disabled {{ request('filter_periode_id') ? '' : 'selected' }}>-- Pilih Periode Tanam --</option>
                            @foreach ($periodeTanams as $periode)
                                <option value="{{ $periode->id }}" {{ request('filter_periode_id') == $periode->id ? 'selected' : '' }}>
                                    {{ $periode->nama_tanaman ?? 'Periode ' . $periode->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="kategori_sampel_id">Kategori Sampel</label>
                        <select name="kategori_sampel_id" id="kategori_sampel_id" class="form-select">
                            <option value="">-- Pilih Kategori Sampel --</option>
                            @foreach ($kategoriSampels as $kategoriSampel)
                                <option value="{{ $kategoriSampel->id }}"
                                    {{ request('kategori_sampel_id') == $kategoriSampel->id ? 'selected' : '' }}>
                                    {{ $kategoriSampel->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="tanggal_awal">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control"
                            value="{{ request('tanggal_awal') }}">
                    </div>

                    <div class="col-md-2">
                        <label for="tanggal_akhir">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                            value="{{ request('tanggal_akhir') }}">
                    </div>

                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel-fill"></i> Filter
                        </button>
                    </div>
                </form>

                <a href="{{ route('riwayat_tanaman.export', request()->all()) }}" class="btn btn-success h-100 d-flex align-items-center">
                    <i class="bi bi-download me-1"></i> Export Data
                </a>
            </div>


                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Tanaman</th>
                                <th>Nama Periode</th>
                                <th>Waktu</th>
                                <th>Pupuk</th>
                                <th>Panjang Daun</th>
                                <th>Lebar Daun</th>
                                <th>pH</th>
                                <th>Potasium</th>
                                <th>Phospor</th>
                                <th>EC</th>
                                <th>Nitrogen</th>
                                <th>Humidity</th>
                                <th>Suhu</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inputHarians as $inputHarian)
                                <tr>
                                    <td>{{ $loop->iteration + ($inputHarians->currentPage() - 1) * $inputHarians->perPage() }}</td>
                                    <td>{{ $inputHarian->periode->nama_tanaman ?? '-' }}</td>
                                    <td>{{ $inputHarian->kategoriSampel->nama ?? '-' }}</td>
                                    <td>{{ $inputHarian->waktu ?? '-' }}</td>
                                    <td>{{ $inputHarian->pupuk ?? '-' }}</td>
                                    <td>{{ $inputHarian->panjang_daun ?? '-' }}</td>
                                    <td>{{ $inputHarian->lebar_daun ?? '-' }}</td>
                                    <td>{{ $inputHarian->ph ?? '-' }}</td>
                                    <td>{{ $inputHarian->pota ?? '-' }}</td>
                                    <td>{{ $inputHarian->phospor ?? '-' }}</td>
                                    <td>{{ $inputHarian->EC ?? '-' }}</td>
                                    <td>{{ $inputHarian->Nitrogen ?? '-' }}</td>
                                    <td>{{ $inputHarian->humidity ?? '-' }}</td>
                                    <td>{{ $inputHarian->temp ?? '-' }}</td>
                                    <td>
                                        @if ($inputHarian->foto)
                                            <img src="{{ asset('storage/' . $inputHarian->foto) }}" alt="Foto Sampel" style="max-width: 200px;">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="15" class="text-center">Belum ada data periode tanam.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $inputHarians->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
