@extends('partials.headerFooter')

@section('content')
@include('partials.sidebar')

<div class="pagetitle">
    <h1>Profile</h1>

    @php
    $role = auth()->user()->role;
    $dashboardRoute = match($role) {
    'admin' => route('dashboard.admin'),
    'owner' => route('dashboard.owner'),
    'user' => route('dashboard.user'),
    default => '#'
    };
    @endphp

    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ $dashboardRoute }}">Home</a></li>
            <li class="breadcrumb-item">Users</li>
            <li class="breadcrumb-item active">Profile</li>
        </ol>
    </nav>
</div>

<section class="section profile">
    <div class="row">
        {{-- Kartu Profil --}}
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    @if (auth()->user()->profile_picture)
                    <img src="{{ asset('storage/profile_pictures/' . auth()->user()->profile_picture) }}" alt="Profile" class="rounded-circle" width="120" height="120">
                    @else
                    <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle" width="120" height="120">
                    @endif

                    <h2 class="mt-3">{{ auth()->user()->name }}</h2>
                    <h5 class="text-muted">{{ auth()->user()->email }}</h5>

                    {{-- Sosial Media --}}
                    @php
                    $socials = ['twitter', 'facebook', 'instagram', 'linkedin'];
                    @endphp
                    <div class="social-links mt-2">
                        @foreach ($socials as $social)
                        @if (auth()->user()->$social)
                        <a href="{{ auth()->user()->$social }}" class="{{ $social }}"><i class="bi bi-{{ $social }}"></i></a>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab Profil --}}
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body pt-3">
                    <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                        </li>
                    </ul>

                    <div class="tab-content pt-2">
                        {{-- Overview --}}
                        <div class="tab-pane fade show active profile-overview" id="profile-overview">
                            <h5 class="card-title">Profile Details</h5>
                            @php
                            $profileDetails = [
                            'Full Name' => auth()->user()->name,
                            'Phone' => auth()->user()->phone_number ?? '-',
                            'Email' => auth()->user()->email
                            ];
                            @endphp

                            @foreach ($profileDetails as $label => $value)
                            <div class="row mb-2">
                                <div class="col-lg-3 label">{{ $label }}</div>
                                <div class="col-lg-9">{{ $value }}</div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Edit Profile --}}
                        <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Profile Photo --}}
                                <div class="row mb-3">
                                    <label for="profile_picture" class="col-md-4 col-lg-3 col-form-label">Profile Photo</label>
                                    <div class="col-md-8 col-lg-9">
                                        @if(auth()->user()->profile_picture)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                                        @else
                                        <img src="{{ asset('assets/img/default-profile.png') }}" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                                        @endif
                                        <input type="file" name="profile_picture" class="form-control mt-2">
                                    </div>
                                </div>

                                {{-- Full Name --}}
                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="name" type="text" class="form-control" value="{{ old('name', auth()->user()->name) }}">
                                    </div>
                                </div>

                                {{-- Phone --}}
                                <div class="row mb-3">
                                    <label for="phone_number" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="phone_number" type="text" class="form-control" value="{{ old('phone_number', auth()->user()->phone_number) }}">
                                    </div>
                                </div>


                                {{-- Email (Read Only) --}}
                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="email" type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>

                        {{-- Change Password --}}
                        <div class="tab-pane fade pt-3" id="profile-change-password">
                            <form method="POST" action="{{ route('profile.change-password') }}">
                                @csrf

                                <div class="row mb-3">
                                    <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="current_password" type="password" class="form-control">
                                        @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="new_password" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="new_password" type="password" class="form-control">
                                        @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="new_password_confirmation" class="col-md-4 col-lg-3 col-form-label">Confirm Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="new_password_confirmation" type="password" class="form-control">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div><!-- End tab-content -->
                </div><!-- End card-body -->
            </div><!-- End card -->
        </div><!-- End col-xl-8 -->
    </div><!-- End row -->
</section>
@endsection