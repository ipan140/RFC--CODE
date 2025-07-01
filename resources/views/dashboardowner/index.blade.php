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

    <style>
        .sensor-card {
            background: #e8f5e9;
            border-radius: 16px;
            padding: 20px;
            color: #1b5e20;
            transition: all 0.3s ease-in-out;
        }

        .sensor-card:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .sensor-card .card-icon {
            background-color: #c8e6c9;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #2e7d32;
        }

        .sensor-card h5.card-title span {
            color: #66bb6a;
        }

        .sensor-card h6 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 0;
        }

        .sensor-card .filter {
            display: none;
        }
    </style>
    {{-- Informasi Mitra, Proyek, dan User Terhubung --}}
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="sensor-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">User Mitra <span>| Total</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $jumlahUserMitra }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="sensor-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Proyek Terhubung <span>| Total</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon">
                            <i class="bi bi-diagram-3-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $jumlahProyek }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="sensor-card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">User Terhubung <span>| Total</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $jumlahUser }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End Row --}}
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
                <div class="col-md-6 mb-4">
                    <div class="sensor-card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ ucfirst($device) }} <span>| Today</span></h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon">
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
                                        {{ str_replace('.', ',', $displayValue) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div><!-- End Row -->
    </section>
    </main><!-- End Main -->
    @if(session('login_success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                text: 'Selamat datang di dashboard.',
                showConfirmButton: false,
                timer: 2000
            });
        });
    </script>
@endif

@endsection