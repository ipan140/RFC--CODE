{{-- <title>RFC - App | {{ $title }}</title> --}}
@extends('partials.headerFooter')
@include('partials.sidebar')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1 class="text-success">Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-success" href="/home">Home</a></li>
                    <li class="breadcrumb-item active text-success">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <!-- Suhu Tanaman Card -->
                @php
                $icons = [
                    'ph' => 'bi-thermometer-half',
                    'EC' => 'bi-heart-pulse-fill',
                    'phospor' => 'bi-droplet-half',
                    'pota' => 'bi-clipboard-data-fill',
                    'Nitrogen' => 'bi-droplet',
                    'humidity' => 'bi-moisture',
                    'temp' => 'bi-thermometer'
                ];
                @endphp

                @foreach($data as $device => $value)
                    <div class="col-md-6"> {{-- ← 2 cards per row --}}
                        <div class="card info-card sales-card">
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">{{ ucfirst($device) }} <span>| Today</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi {{ $icons[$device] ?? 'bi-question-circle' }}"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>
                                            {{
                                                is_array($value)
                                                    ? (isset($value['nilai'])
                                                        ? $value['nilai']
                                                        : json_encode($value))
                                                    : $value
                                            }}
                                        </h6>
                                        <span class="text-success small pt-1 fw-bold">..%</span>
                                        <span class="text-muted small pt-2 ps-1">increase</span><br>

                                        <a href="{{ url('/dashboard?device=' . $device) }}" class="small text-primary">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- End Sensor 5 Card -->
                <div class="container my-4">
                <div class="row">
    <!-- Sensor Traffic -->
    <div class="col-12 col-md-6 mb-4">
        <div class="card info-card">
            <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start"><h6>Filter</h6></li>
                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                </ul>
            </div>

            <div class="card-body pb-0">
                <h5 class="card-title">Sensor Traffic <span>| Today</span></h5>
                <div id="trafficChart" style="min-height: 400px;" class="echart"></div>
            </div>
        </div>
    </div>

    <!-- Reports -->
    <div class="col-12 col-md-6 mb-4">
        <div class="card info-card">
            <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start"><h6>Filter</h6></li>
                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                </ul>
            </div>
            <div class="card-body pb-0">
                <h5 class="card-title">Reports <span>| Today</span></h5>
                <div id="reportsChart" style="min-height: 400px;"></div>
            </div>
        </div>
    </div>
</div>


<!-- CHART JS -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Pie Chart - Sensor Traffic
        echarts.init(document.querySelector("#trafficChart")).setOption({
            tooltip: { trigger: 'item' },
            legend: { top: '5%', left: 'center' },
            series: [{
                name: 'Access From',
                type: 'pie',
                radius: ['40%', '70%'],
                avoidLabelOverlap: false,
                label: { show: false, position: 'center' },
                emphasis: {
                    label: {
                        show: true,
                        fontSize: '18',
                        fontWeight: 'bold'
                    }
                },
                labelLine: { show: false },
                data: [
                    { value: 6.34, name: 'pH' },
                    { value: 23.9, name: 'EC' },
                    { value: 19.5, name: 'Soil Moisture' },
                    { value: 30, name: 'Soil Temperature' },
                    { value: 1.7, name: 'Nitrogen' }
                ]
            }]
        });

        // Line Chart - Reports
        new ApexCharts(document.querySelector("#reportsChart"), {
            series: [
                { name: 'pH', data: [5, 7.8, 8, 11, 5, 4, 9] },
                { name: 'EC', data: [11, 32, 45, 32, 34, 52, 41] },
                { name: 'Soil Moisture', data: [15, 11, 32, 18, 9, 24, 11] }
            ],
            chart: {
                height: 350,
                type: 'area',
                toolbar: { show: false }
            },
            markers: { size: 4 },
            colors: ['#4154f1', '#2eca6a', '#ff771d'],
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.4,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: {
                type: 'datetime',
                categories: [
                    "2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z",
                    "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z",
                    "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z",
                    "2018-09-19T06:30:00.000Z"
                ]
            },
            tooltip: {
                x: { format: 'dd/MM/yy HH:mm' }
            }
        }).render();
    });
</script>

            </div>
        </section>
    </main><!-- End #main -->
@endsection