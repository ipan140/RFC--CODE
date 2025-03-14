@extends('layouts.app')

@section('title', 'Tambah Mitra')

@section('content')
<div class="container py-3">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('mitra.index') }}">Mitra</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Mitra</li>
        </ol>
    </nav>

    <!-- Header -->
    <h5 class="mb-3"><i class="bi bi-person-plus"></i> Tambah Mitra</h5>

    <!-- Card Form -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <strong>Formulir Tambah Mitra</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('mitra.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <!-- Nama Mitra -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Mitra</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" placeholder="Masukkan nama mitra" value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Lokasi -->
                        <div class="mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control @error('lokasi') is-invalid @enderror" 
                                   id="lokasi" name="lokasi" placeholder="Masukkan lokasi" value="{{ old('lokasi') }}" required>
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control @error('telepon') is-invalid @enderror" 
                                   id="telepon" name="telepon" placeholder="Masukkan nomor telepon" value="{{ old('telepon') }}" required>
                            @error('telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="text-end">
                            <a href="{{ route('mitra.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
