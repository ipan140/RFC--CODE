@extends('partials.headerFooter')
@include('partials.sidebar')

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Edit Mitra</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('mitra.index') }}">Mitra</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-8">

                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Form Edit Mitra</h5>

                        <form action="{{ route('mitra.update', $mitra->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Mitra</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $mitra->nama) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat Mitra</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $mitra->alamat) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="kontak" class="form-label">Kontak Mitra</label>
                                <input type="text" class="form-control" id="kontak" name="kontak" value="{{ old('kontak', $mitra->kontak) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Mitra</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $mitra->email) }}" required>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('mitra.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>
@endsection
