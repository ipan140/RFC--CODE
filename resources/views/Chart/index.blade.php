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
      <h5 class="card-title">Data Sensor Tanaman</h5>
      <div id="columnChart"></div>
      </div>
    </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
      const series = @json($timeSeriesChart['series']);
      const categories = @json($timeSeriesChart['labels']);

      const options = {
      series: series,
      chart: {
        type: 'bar',
        height: 350
      },
      plotOptions: {
        bar: {
        horizontal: false,
        columnWidth: '50%',
        endingShape: 'rounded'
        }
      },
      dataLabels: {
        enabled: true
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      xaxis: {
        categories: categories,
        title: {
        text: 'Jenis Sensor'
        }
      },
      yaxis: {
        min: 0,
        max: 100,
        title: {
        text: 'Nilai Sensor (0–100)'
        }
      },
      fill: {
        opacity: 1
      },
      tooltip: {
        y: {
        formatter: function (val) {
          return val + " Sensor";
        }
        }
      }
      };

      const chart = new ApexCharts(document.querySelector("#columnChart"), options);
      chart.render();
    });
    </script>

    <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
      <h5 class="card-title">Sensor Values</h5>
      <div id="barChartSensors"></div>
      <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

      <script>
        document.addEventListener("DOMContentLoaded", () => {
        // chartData yang dikirim dari controller berupa array asosiatif {label: value, ...}
        const rawData = @json($chartData);

        // Karena ApexCharts butuh array label dan array data, kita ekstrak:
        const labels = Object.keys(rawData);
        const data = Object.values(rawData).map(val => {
          // Pastikan nilai number, fallback ke 0 jika null/NaN
          return (val !== null && !isNaN(val)) ? parseFloat(val) : 0;
        });

        new ApexCharts(document.querySelector("#barChartSensors"), {
          series: [{
          name: 'Data Sensor',
          data: data
          }],
          chart: {
          type: 'bar',
          height: 350
          },
          plotOptions: {
          bar: {
            borderRadius: 4,
            horizontal: true
          }
          },
          dataLabels: {
          enabled: false
          },
          xaxis: {
          categories: labels
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
        // Ambil data dari backend, asumsikan chartData adalah object { label: value, ... }
        const chartData = @json($chartData);

        const labels = Object.keys(chartData); // ['pH', 'EC', 'Soil Moisture', ...]
        const series = Object.values(chartData).map(val => val ?? 0); // pastikan tidak ada null

        new ApexCharts(document.querySelector("#pieChart"), {
          series: series,
          chart: {
          height: 350,
          type: 'pie',
          toolbar: { show: true }
          },
          labels: labels,
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
    <!-- Line Chart -->
    <div class="row">
    <!-- Line Chart Card - Left -->
    <div class="col-lg-8">
      <div class="card">
      <div class="card-body">
        <h5 class="card-title">Reports</h5>

        <div id="reportsChart"></div>

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
        document.addEventListener("DOMContentLoaded", () => {
          const series = {!! json_encode($reportSeries['series']) !!};
          const categories = {!! json_encode($reportSeries['labels']) !!};

          const options = {
          series: series,
          chart: {
            type: 'scatter', // Bisa ganti ke 'line' atau 'bar'
            height: 350,
            toolbar: { show: false }
          },
          markers: {
            size: 8,
            shape: "circle"
          },
          dataLabels: {
            enabled: true
          },
          xaxis: {
            categories: categories,
            title: { text: 'Jenis Sensor' }
          },
          yaxis: {
            min: 0,
            max: 100,
            title: { text: 'Nilai Sensor' }
          },
          tooltip: {
            y: {
            formatter: (val) => val + ' Sensor'
            }
          }
          };

          new ApexCharts(document.querySelector("#reportsChart"), options).render();
        });
        </script>
      </div>
      </div>

    </div>

    <!-- Pie Chart Card - Right -->
    <div class="col-lg-4">
      <div class="card">
      <div class="card-body pb-0">
        <h5 class="card-title">Sensor Traffic <span>| Today</span></h5>
        <div id="trafficChart" style="min-height: 400px;" class="echart"></div>
        <script>
        document.addEventListener("DOMContentLoaded", () => {
          var chartDom = document.getElementById('trafficChart');
          var myChart = echarts.init(chartDom);
          var option = {
          tooltip: { trigger: 'item' },
          legend: {
            top: '5%',
            left: 'center'
          },
          series: [{
            name: 'Sensor',
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
            @foreach($trafficChartData as $key => $value)
        {
            value: {{ $value ?? 0 }},
            name: "{{ $key }}"
            },
        @endforeach
      ]
          }]
          };
          myChart.setOption(option);
        });
        </script>
      </div>
      </div>
    </div>
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
      ], JSON_NUMERIC_CHECK)!!
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
                !!json_encode($reportChartData['pH'] ?? [0, 0, 0, 0, 0, 0, 0], JSON_NUMERIC_CHECK)!!
            },
    EC: {
      !!json_encode($reportChartData['EC'] ?? [0, 0, 0, 0, 0, 0, 0], JSON_NUMERIC_CHECK)!!
    },
    SoilMoisture: {
      !!json_encode($reportChartData['Soil Moisture'] ?? [0, 0, 0, 0, 0, 0, 0], JSON_NUMERIC_CHECK)!!
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