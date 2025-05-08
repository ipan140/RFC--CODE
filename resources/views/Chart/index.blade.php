@extends('partials.headerFooter')
@include('partials.sidebar')

@section('content')
<div class="pagetitle">
  <h1>Charts</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Charts</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<div class="row">
  <!-- Baris 1 Start -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Tekstur Tanah</h5>
        <div id="columnChart"></div>
        <script>
          document.addEventListener("DOMContentLoaded", () => {
            new ApexCharts(document.querySelector("#columnChart"), {
              series: [
                { name: 'Nitrogen', data: [41, 11, 16, 9, 14, 40, 13] },
                { name: 'Potassium', data: [77, 30, 46, 25, 42, 89, 37] },
                { name: 'Tempratur Tanah', data: [34, 34, 35, 37, 35, 34, 32] }
              ],
              chart: { type: 'bar', height: 350 },
              plotOptions: {
                bar: {
                  horizontal: false,
                  columnWidth: '55%',
                  endingShape: 'rounded'
                }
              },
              dataLabels: { enabled: false },
              stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
              },
              xaxis: {
                categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']
              },
              yaxis: {
                title: { text: 'Nilai Satuan' }
              },
              fill: { opacity: 1 },
              tooltip: {
                y: {
                  formatter: function (val) {
                    return " " + val + " satuan";
                  }
                }
              }
            }).render();
          });
        </script>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Humidity <span>%</span></h5>
        <div id="barChart"></div>
        <script>
          document.addEventListener("DOMContentLoaded", () => {
            new ApexCharts(document.querySelector("#barChart"), {
              series: [{ data: [40, 43, 44.8, 47, 54, 58, 69, 22, 29, 48] }],
              chart: { type: 'bar', height: 350 },
              plotOptions: {
                bar: {
                  borderRadius: 4,
                  horizontal: true
                }
              },
              dataLabels: { enabled: false },
              xaxis: {
                categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']
              }
            }).render();
          });
        </script>
      </div>
    </div>
  </div>
  <!-- Baris 1 End -->

  <!-- Baris 2 Start -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Kondisi Sensor</h5>
        <div id="pieChart"></div>
        <script>
          document.addEventListener("DOMContentLoaded", () => {
            new ApexCharts(document.querySelector("#pieChart"), {
              series: [44, 55, 13, 43, 22, 31, 32],
              chart: {
                height: 350,
                type: 'pie',
                toolbar: { show: true }
              },
              labels: ['pH1', 'Soil_Moisture1A', 'Soil_Temperature1A', 'Soil_Conductivity1A', 'Nitrogen1A', 'Phosphorus1A', 'Potassium1A']
            }).render();
          });
        </script>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Hasil Panen</h5>
        <div id="donutChart"></div>
        <script>
          document.addEventListener("DOMContentLoaded", () => {
            new ApexCharts(document.querySelector("#donutChart"), {
              series: [44, 55, 13, 43],
              chart: {
                height: 350,
                type: 'donut',
                toolbar: { show: true }
              },
              labels: ['Quartal 1', 'Quartal 2', 'Quartal 3', 'Quartal 4']
            }).render();
          });
        </script>
      </div>
    </div>
  </div>
  <!-- Baris 2 End -->

  <!-- Baris 3 Start -->
  <div class="row">
    <!-- Line Chart -->
    <div class="col-lg-8">
      <div class="card">
        <div class="filter">
          <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <li class="dropdown-header text-start"><h6>Filter</h6></li>
            <li><a class="dropdown-item" href="#">Today</a></li>
            <li><a class="dropdown-item" href="#">This Month</a></li>
            <li><a class="dropdown-item" href="#">This Year</a></li>
          </ul>
        </div>
        <div class="card-body">
          <h5 class="card-title">Reports <span>/Today</span></h5>
          <div id="reportsChart"></div>
          <script>
            document.addEventListener("DOMContentLoaded", () => {
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
                stroke: {
                  curve: 'smooth',
                  width: 2
                },
                xaxis: {
                  type: 'datetime',
                  categories: [
                    "2018-09-19T00:00:00.000Z",
                    "2018-09-19T01:30:00.000Z",
                    "2018-09-19T02:30:00.000Z",
                    "2018-09-19T03:30:00.000Z",
                    "2018-09-19T04:30:00.000Z",
                    "2018-09-19T05:30:00.000Z",
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
      </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-lg-4">
      <div class="card">
        <div class="card-body pb-0">
          <h5 class="card-title">Sensor Traffic <span>| Today</span></h5>
          <div id="trafficChart" style="min-height: 400px;" class="echart"></div>
          <script>
            document.addEventListener("DOMContentLoaded", () => {
              echarts.init(document.querySelector("#trafficChart")).setOption({
                tooltip: { trigger: 'item' },
                legend: { top: '5%', left: 'center' },
                series: [{
                  name: 'Access From',
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
            });
          </script>
        </div>
      </div>
    </div>
  </div>
  <!-- Baris 3 End -->
</div>
@endsection
@section('scripts')
<!-- Load Library -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
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
</script>