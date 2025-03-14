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

                        <!-- Table with stripped rows -->
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
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>Nitrogen (N)</td>
                                        <td class="text-center">7</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td>Phosphorus (P)</td>
                                        <td class="text-center">9</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td>Kalium (K)</td>
                                        <td class="text-center">5</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">4</td>
                                        <td>Soil pH</td>
                                        <td class="text-center">6.85</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">5</td>
                                        <td>Electrical Conductivity (EC)</td>
                                        <td class="text-center">18</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">6</td>
                                        <td>Soil Temperature</td>
                                        <td class="text-center">30</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">7</td>
                                        <td>Humidity</td>
                                        <td class="text-center">47</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection
