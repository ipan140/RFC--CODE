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
      const categories = @json($timeSeriesChart['labels']);
      const rawSeries = @json($timeSeriesChart['series'][0]['data']);

      const options = {
      series: [{
        name: 'Nilai Sensor',
        data: rawSeries
      }],
      chart: {
        type: 'bar',
        height: 350
      },
      plotOptions: {
        bar: {
        horizontal: false,
        columnWidth: '60%',
        endingShape: 'rounded',
        distributed: true // 💡 warna berbeda untuk tiap bar
        }
      },
      colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#3F51B5', '#F44336', '#4CAF50'],
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
        const rawData = @json($chartData);

        const labels = Object.keys(rawData);
        const data = Object.values(rawData).map(val => {
          return (val !== null && !isNaN(val)) ? parseFloat(val) : 0;
        });

        const options = {
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
            horizontal: true,
            distributed: true // 💡 beda warna tiap bar
          }
          },
          colors: ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#3F51B5', '#F44336', '#4CAF50'],
          dataLabels: {
          enabled: true
          },
          xaxis: {
          categories: labels,
          title: {
            text: 'Jenis Sensor'
          }
          },
          tooltip: {
          y: {
            formatter: function (val) {
            return val + " Sensor";
            }
          }
          }
        };

        new ApexCharts(document.querySelector("#barChartSensors"), options).render();
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
      <h5 class="card-title">Hasil Panen per Kategori Sampel</h5>
      <div id="donutChart"></div>
      </div>
    </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
      const labels = @json($labels);
      const series = @json($series);

      if (labels.length && series.length) {
      const chart = new ApexCharts(document.querySelector("#donutChart"), {
        series: series,
        chart: {
        type: 'donut',
        height: 350,
        toolbar: { show: true }
        },
        labels: labels
      });

      chart.render();
      } else {
      document.querySelector("#donutChart").innerHTML = "<p class='text-center text-muted'>Data belum tersedia</p>";
      }
    });
    </script>
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

          // Peta warna khusus per nama sensor
          const sensorColors = {
          "pH": "#008FFB",               // Biru
          "Potassium": "#00E396",        // Hijau
          "Phospor": "#FEB019",          // Kuning
          "EC": "#FF4560",               // Merah muda
          "Nitrogen": "#775DD0",         // Ungu
          "Kelembaban": "#3F51B5",       // Biru tua
          "Temperatur Tanah": "#F44336"  // Merah
          };

          // Ambil warna dari mapping berdasarkan nama sensor
          const colors = series.map(item => sensorColors[item.name] || '#999');

          const options = {
          series: series,
          chart: {
            type: 'scatter',
            height: 350,
            toolbar: {
            show: true,
            tools: {
              download: true,
              selection: false,
              zoom: false,
              zoomin: false,
              zoomout: false,
              pan: false,
              reset: false,
            },
            export: {
              csv: {
              filename: "laporan-sensor",
              headerCategory: "Jenis Sensor",
              headerValue: "Nilai",
              },
              svg: { filename: "laporan-sensor" },
              png: { filename: "laporan-sensor" },
            }
            }
          },
          colors: colors,
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
          },
          legend: {
            show: true,
            position: 'bottom'
          }
          };

          new ApexCharts(document.querySelector("#reportsChart"), options).render();
        });
        </script>
      </div>
      </div>
    </div>

    <!-- Pie Chart Card - Right -->
    <div class="col-lg-4 position-relative">
  <div class="card">
    <div class="card-body pb-0">
      <h5 class="card-title">Sensor Traffic <span>| Today</span></h5>

      <!-- Dropdown Export Icon -->
      <div class="dropdown position-absolute" style="top: 20px; right: 20px;">
        <button class="btn btn-light dropdown-toggle" type="button" id="exportMenu" data-bs-toggle="dropdown" aria-expanded="false">
          &#9776; <!-- Hamburger Icon -->
        </button>
        <ul class="dropdown-menu" aria-labelledby="exportMenu">
          <li><a class="dropdown-item" href="#" id="download-svg">Download SVG</a></li>
          <li><a class="dropdown-item" href="#" id="download-png">Download PNG</a></li>
          <li><a class="dropdown-item" href="#" id="download-csv">Download CSV</a></li>
        </ul>
      </div>

      <div id="trafficChart" style="min-height: 400px;"></div>

      <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
      <script>
        document.addEventListener("DOMContentLoaded", () => {
          const chartData = [
            @foreach($trafficChartData as $key => $value)
              { name: "{{ $key }}", y: {{ $value ?? 0 }} },
            @endforeach
          ];

          const chart = new ApexCharts(document.querySelector("#trafficChart"), {
            chart: {
              type: 'donut',
              height: 400,
              id: 'sensorChart'
            },
            series: chartData.map(item => item.y),
            labels: chartData.map(item => item.name),
            legend: {
              position: 'bottom'
            }
          });

          chart.render();

          // Download PNG
          document.getElementById('download-png').addEventListener('click', () => {
            chart.dataURI().then(({ imgURI }) => {
              const link = document.createElement('a');
              link.href = imgURI;
              link.download = 'laporan-sensor.png';
              link.click();
            });
          });

          // Download SVG
          document.getElementById('download-svg').addEventListener('click', () => {
            chart.dataURI().then(({ svgURI }) => {
              const link = document.createElement('a');
              link.href = svgURI;
              link.download = 'laporan-sensor.svg';
              link.click();
            });
          });

          // Download CSV
          document.getElementById('download-csv').addEventListener('click', () => {
            let csv = 'Jenis Sensor,Nilai\n';
            chartData.forEach(row => {
              csv += `${row.name},${row.y}\n`;
            });
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'laporan-sensor.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
          });
        });
      </script>
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