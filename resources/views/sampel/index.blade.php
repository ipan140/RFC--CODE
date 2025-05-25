@extends('partials.headerFooter')

@section('content')
    @include('partials.sidebar')

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
                <h5 class="card-title mb-3"><i class="bi bi-list-ul"></i> Periode Tanam</h5>

                <div class="d-flex justify-content-start align-items-center mb-3 flex-wrap gap-2">
                    <form method="GET" action="{{ route('sampel.index') }}"
                        class="d-flex align-items-center gap-2 flex-wrap">

                        {{-- Tombol Export --}}
                        <a href="{{ route('sampel.export', request()->all()) }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Export Data
                        </a>

                        {{-- Filter Tanaman --}}
                        <select name="filter_tanaman_id" class="form-select w-auto">
                            <option value="">-- Semua Tanaman --</option>
                            @foreach ($tanamans as $tanaman)
                                <option value="{{ $tanaman->id }}" {{ request('filter_tanaman_id') == $tanaman->id ? 'selected' : '' }}>
                                    {{ $tanaman->nama_tanaman }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Filter Tanggal Mulai --}}
                        <input type="date" name="tanggal_mulai" class="form-control w-auto"
                            value="{{ request('tanggal_mulai') }}">

                        {{-- Filter Tanggal Akhir --}}
                        <input type="date" name="tanggal_akhir" class="form-control w-auto"
                            value="{{ request('tanggal_akhir') }}">

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel-fill"></i> Filter
                        </button>

                        {{-- Reset Filter --}}
                        <a href="{{ route('sampel.index') }}" class="btn btn-outline-secondary">
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
                                    <td>{{ $inputHarian->tanaman->nama_tanaman ?? '-' }}</td>
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
                                            <img src="{{ asset('uploads/foto_periode/' . $inputHarian->foto) }}" alt="Foto Periode"
                                                class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center">Belum ada data periode tanam.</td>
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
