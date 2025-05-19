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

                <div class="d-flex justify-content-start align-items-center mb-3 flex-wrap gap-2">
                    <form method="GET" action="{{ route('riwayat_tanaman.index') }}"
                        class="d-flex align-items-center gap-2 flex-wrap">
                        <a href="{{ route('riwayat_tanaman.export', request()->all()) }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Export Data
                        </a>
                        <!-- Filter Tanaman -->
                        <select name="filter_tanaman_id" class="form-select w-auto">
                            <option value="">-- Semua Tanaman --</option>
                            @foreach ($tanamans as $tanaman)
                                <option value="{{ $tanaman->id }}" {{ request('filter_tanaman_id') == $tanaman->id ? 'selected' : '' }}>
                                    {{ $tanaman->nama_tanaman }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Filter Tanggal Mulai -->
                        <input type="date" name="tanggal_mulai" class="form-control w-auto"
                            value="{{ request('tanggal_mulai') }}">

                        <!-- Filter Tanggal Akhir -->
                        <input type="date" name="tanggal_akhir" class="form-control w-auto"
                            value="{{ request('tanggal_akhir') }}">

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel-fill"></i> Filter
                        </button>
                        <a href="{{ route('riwayat_tanaman.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                    </form>
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
                            @forelse ($periode_tanams as $periode)
                                <tr>
                                    <td>{{ $loop->iteration + ($periode_tanams->currentPage() - 1) * $periode_tanams->perPage() }}</td>
                                    <td>{{ $periode->tanaman->nama_tanaman ?? '-' }}</td>
                                    <td>{{ $periode->nama_periode ?? '-' }}</td>
                                    <td>{{ $periode->waktu ?? '-' }}</td>
                                    <td>{{ $periode->pupuk ?? '-' }}</td>
                                    <td>{{ $periode->panjang_daun ?? '-' }}</td>
                                    <td>{{ $periode->lebar_daun ?? '-' }}</td>
                                    <td>{{ $periode->ph ?? '-' }}</td>
                                    <td>{{ $periode->pota ?? '-' }}</td>
                                    <td>{{ $periode->phospor ?? '-' }}</td>
                                    <td>{{ $periode->EC ?? '-' }}</td>
                                    <td>{{ $periode->Nitrogen ?? '-' }}</td>
                                    <td>{{ $periode->humidity ?? '-' }}</td>
                                    <td>{{ $periode->temp ?? '-' }}</td>
                                    <td>
                                        @if ($periode->foto)
                                            <img src="{{ asset('uploads/foto_periode/' . $periode->foto) }}" alt="Foto Periode"
                                                class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
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
                        {{ $periode_tanams->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
