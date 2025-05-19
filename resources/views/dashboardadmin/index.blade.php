@extends('partials.headerFooter')
@section('content')
@include('partials.sidebar')
    <div class="pagetitle">
        <h1 class="text-success">Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-success" href="/home">Home</a></li>
                <li class="breadcrumb-item active text-success">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
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

            @foreach($results as $device => $value)
            <div class="col-md-6">
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
                                    @php
                                    $displayValue = is_array($value) ? ($value['nilai'] ?? json_encode($value)) : $value;
                                    if (in_array($device, ['pota', 'Nitrogen', 'phospor'])) {
                                    $displayValue .= ' ppm';
                                    }
                                    @endphp
                                    {{ $displayValue }}
                                </h6>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </div><!-- End Sensor Cards -->

        {{-- <div class="row mt-4">
            <!-- Sensor Traffic Chart -->
            <div class="col-lg-6">
                <div class="card info-card">
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

                    <div class="card-body pb-0">
                        <h5 class="card-title">Sensor Traffic <span>| Today</span></h5>
                        <div id="trafficChart" style="min-height: 400px;" class="echart"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card info-card">
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

                    <div class="card-body pb-0">
                        <h5 class="card-title">Reports <span>| Today</span></h5>
                        <div id="reportsChart" style="min-height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div> --}}

    </section>
</main><!-- End Main -->
@endsection

@section('scripts')
<!-- Load Library -->
<!-- <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const trafficChartData = {
            !!json_encode([
                ['value' => $chartData['pH'] ?? 0, 'name' => 'pH'],
                ['value' => $chartData['EC'] ?? 0, 'name' => 'EC'],
                ['value' => $chartData['Soil Moisture'] ?? 0, 'name' => 'Soil Moisture'],
                ['value' => $chartData['Soil Temperature'] ?? 0, 'name' => 'Soil Temperature'],
                ['value' => $chartData['Nitrogen'] ?? 0, 'name' => 'Nitrogen'],
                ['value' => $chartData['Phospor'] ?? 0, 'name' => 'Phospor'],
                ['value' => $chartData['Potassium'] ?? 0, 'name' => 'Potassium']
            ], JSON_NUMERIC_CHECK) !!
        };

        const trafficChartEl = document.querySelector("#trafficChart");
        if (trafficChartEl) {
            const trafficChart = echarts.init(trafficChartEl);
            trafficChart.setOption({
                tooltip: {
                    trigger: 'item'
                },
                legend: {
                    top: '5%',
                    left: 'center'
                },
                series: [{
                    name: 'Sensor Traffic',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: 18,
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: trafficChartData
                }]
            });
        }

        const reportData = {
            pH: {
                !!json_encode($reportChartData['pH'] ?? [0, 0, 0, 0, 0, 0, 0], JSON_NUMERIC_CHECK) !!
            },
            EC: {
                !!json_encode($reportChartData['EC'] ?? [0, 0, 0, 0, 0, 0, 0], JSON_NUMERIC_CHECK) !!
            },
            SoilMoisture: {
                !!json_encode($reportChartData['Soil Moisture'] ?? [0, 0, 0, 0, 0, 0, 0], JSON_NUMERIC_CHECK) !!
            }
        };

        const reportsChartEl = document.querySelector("#reportsChart");
        if (reportsChartEl) {
            const reportsChart = new ApexCharts(reportsChartEl, {
                series: [{
                        name: 'pH',
                        data: reportData.pH
                    },
                    {
                        name: 'EC',
                        data: reportData.EC
                    },
                    {
                        name: 'Soil Moisture',
                        data: reportData.SoilMoisture
                    }
                ],
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                        show: false
                    }
                },
                markers: {
                    size: 4
                },
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
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    type: 'datetime',
                    categories: [
                        "2025-04-20T00:00:00.000Z",
                        "2025-04-20T01:30:00.000Z",
                        "2025-04-20T02:30:00.000Z",
                        "2025-04-20T03:30:00.000Z",
                        "2025-04-20T04:30:00.000Z",
                        "2025-04-20T05:30:00.000Z",
                        "2025-04-20T06:30:00.000Z"
                    ]
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    }
                }
            });
            reportsChart.render();
        }
    });
</script> -->
@endsection