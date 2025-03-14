@extends('partials.headerFooter')
@include('partials.sidebar')

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Detail Mitra</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('mitra.index') }}">Mitra</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-8">

                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Mitra</h5>

                        <table class="table table-striped">
                            <tr>
                                <th>Nama Mitra</th>
                                <td>{{ $mitra->nama }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $mitra->alamat }}</td>
                            </tr>
                            <tr>
                                <th>Kontak</th>
                                <td>{{ $mitra->kontak }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $mitra->email }}</td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>{{ $mitra->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Terakhir Diperbarui</th>
                                <td>{{ $mitra->updated_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        </table>

                        <div class="text-end">
                            <a href="{{ route('mitra.index') }}" class="btn btn-secondary">Kembali</a>
                            <a href="{{ route('mitra.edit', $mitra->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

</main>
@endsection
