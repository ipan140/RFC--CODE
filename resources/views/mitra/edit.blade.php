@extends('partials.headerFooter')

@section('content')
@include('partials.sidebar')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Data Mitra</h1>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('mitra.update', $mitra->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ $mitra->nama }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ $mitra->lokasi }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $mitra->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="{{ $mitra->telepon }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('mitra.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection
