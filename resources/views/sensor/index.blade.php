@extends('partials.headerFooter')
@include('partials.sidebar')

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/"><i class="bi bi-house-door-fill"></i> Home</a>
                </li>
                <li class="breadcrumb-item active">Daftar Sensor</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title"><i class="bi bi-list-ul"></i> List Sensor</h5>
                            <a href="{{ route('sensor.create') }}" class="btn btn-success">
                                <i class="bi bi-plus-lg"></i> Tambah Sensor
                            </a>
                        </div>

                        <!-- Sensor Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover align-middle">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Sensor</th>
                                        <th>Tipe</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sensors as $index => $sensor)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $sensor->nama }}</td>
                                        <td>{{ $sensor->tipe }}</td>
                                        <td>{{ $sensor->latitude }}</td>
                                        <td>{{ $sensor->longitude }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('sensor.edit', $sensor->id) }}" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit Sensor">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('sensor.destroy', $sensor->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Hapus Sensor" onclick="return confirm('Apakah Anda yakin ingin menghapus sensor ini?');">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada sensor yang terdaftar.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- End Sensor Table -->

                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection