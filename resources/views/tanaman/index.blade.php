@extends('partials.headerFooter')

@section('content')
    @include('partials.sidebar')

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

    <!-- Tabel Daftar Periode Tanam -->
    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="bi bi-list-ul"></i> Daftar Periode Tanam</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Tanaman</th>
                                <th>Status</th>
                                <th>Jumlah Sampel</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($periodes as $periode)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $periode->nama_tanaman ?? '-' }}</td>
                                    <td>
                                        @php
                                            $status = strtolower($periode->status);
                                        @endphp
                                        @if ($status === 'on going')
                                            <span class="badge bg-success">On going</span>
                                        @elseif ($status === 'selesai')
                                            <span class="badge bg-secondary">Selesai</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Tidak diketahui</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $periode->kategori_sampels_count ?? 0 }} Sampel
                                        </span>
                                    </td>
                                    <td>
                                        {{-- <a href="{{ route('sampel.index', ['periode_tanam_id' => $periode->id]) }}" class="btn btn-info btn-sm mb-1">
                                            <i class="bi bi-eye-fill"></i> Lihat Sampel
                                        </a> --}}
                                        <a href="{{ route('kategori_sampel.index', ['periode_tanam_id' => $periode->id]) }}" class="btn btn-success btn-sm mb-1">
                                            <i class="bi bi-plus-circle-fill"></i> Tambah Sampel
                                        </a>
                                        <a href="{{ route('input_harian.index', ['periode_tanam_id' => $periode->id]) }}" class="btn btn-primary btn-sm mb-1">
                                            <i class="bi bi-journal-plus"></i> Input Harian
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data periode tanam.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($inputHarians->hasPages())
                    <div class="mt-3">
                        {{ $inputHarians->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
