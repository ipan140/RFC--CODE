@extends('partials.headerFooter')

@section('content')
    @include('partials.sidebar')

    <div class="pagetitle d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1><i class="bi bi-list-task"></i> Sampel tanaman</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door-fill"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tanaman.index') }}"><i class="bi bi-house-door-fill"></i> Tanaman</a></li>
                    <li class="breadcrumb-item active"> Sampel Tanaman</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-fluid px-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="section p-3 bg-white rounded shadow-sm mb-4">
            <h5 class="card-title mb-3"><i class="bi bi-list-ul"></i>  Sampel tanaman</h5>

            <div class="d-flex justify-content-start align-items-center flex-wrap gap-2 mb-3">
                {{-- <a href="{{ route('kategori_sampel.create', ['periode_tanam_id' => request('periode_tanam_id')]) }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Tambah Kategori
                </a> --}}
                <form method="GET" action="{{ route('kategori_sampel.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
                    <select name="periode_tanam_id" class="form-select w-auto">
                        <option value="">-- Pilih Periode Tanam --</option>
                        @foreach ($periodeTanams as $periode)
                            <option value="{{ $periode->id }}" {{ request('periode_tanam_id') == $periode->id ? 'selected' : '' }}>
                                {{ $periode->nama_tanaman ?? 'Periode ' . $periode->id }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Periode Tanam</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoriSampels as $kategori)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $kategori->periodeTanam->nama_tanaman ?? '-' }}</td>
                                <td>{{ $kategori->nama }}</td>
                                <td>{{ $kategori->deskripsi }}</td>
                                <td class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('input_harian.index', ['kategori_sampel_id' => $kategori->id, 'periode_tanam_id' => $kategori->periode_tanam_id]) }}"
                                    class="btn btn-warning btn-sm text-dark mb-1">
                                        <i class="bi bi-pencil-square"></i> Input Harian
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
