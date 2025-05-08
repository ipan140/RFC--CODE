@extends('partials.headerFooter')
@include('partials.sidebar')

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1><i class="bi bi-bar-chart-line-fill"></i> Data Sensor</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/"><i class="bi bi-house-door-fill"></i> Home</a>
                </li>
                <li class="breadcrumb-item active">Data Sensors</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-table"></i> Tabel Data Sensor</h5>

                        <!-- Tambahkan Tombol Tambah Sensor -->
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahSensorModal">
                                <i class="bi bi-plus-circle"></i> Tambah Sensor
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover align-middle">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Sensor</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $sensorMapping = [
                                    'ph' => ['label' => 'Soil pH', 'satuan' => ''],
                                    'pota' => ['label' => 'Kalium (K)', 'satuan' => 'ppm'],
                                    'phospor' => ['label' => 'Phosphorus (P)', 'satuan' => 'ppm'],
                                    'EC' => ['label' => 'Electrical Conductivity (EC)', 'satuan' => 'dS/m'],
                                    'Nitrogen' => ['label' => 'Nitrogen (N)', 'satuan' => 'ppm'],
                                    'humidity' => ['label' => 'Moisture (Kelembaban Tanah)', 'satuan' => '%'],
                                    'temp' => ['label' => 'Soil Temperature', 'satuan' => '°C'],
                                    ];
                                    $counter = 1;
                                    @endphp

                                    @foreach ($sensorMapping as $key => $info)
                                    @php
                                    $value = $data[$key] ?? '-';
                                    $satuan = $info['satuan'];
                                    if (is_array($value)) {
                                    $value = isset($value['nilai']) ? $value['nilai'] : json_encode($value);
                                    }
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $counter++ }}</td>
                                        <td>{{ $info['label'] }}</td>
                                        <td class="text-center">
                                            <h6>
                                                {{ $value !== '-' ? str_replace('.', ',', $value) . ($satuan ? ' ' . $satuan : '') : '-' }}
                                            </h6>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- End Table -->

                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<!-- Modal Tambah Sensor -->
<div class="modal fade" id="tambahSensorModal" tabindex="-1" aria-labelledby="tambahSensorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahSensorModalLabel">Tambah Sensor Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('sensor.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="sensor_name" class="form-label">Nama Sensor</label>
                        <input type="text" class="form-control" id="sensor_name" name="sensor_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="sensor_value" class="form-label">Nilai Sensor</label>
                        <input type="number" step="any" class="form-control" id="sensor_value" name="sensor_value" required>
                    </div>
                    <div class="mb-3">
                        <label for="sensor_date" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="sensor_date" name="sensor_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Sensor</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection