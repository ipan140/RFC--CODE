@extends('partials.headerFooter')

@section('content')
@include('partials.sidebar')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Detail Mitra</h1>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th>Nama</th>
                        <td>{{ $mitra->nama }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $mitra->lokasi }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $mitra->email }}</td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td>{{ $mitra->telepon }}</td>
                    </tr>
                </table>
                <a href="{{ route('mitra.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </section>
</main>
@endsection
