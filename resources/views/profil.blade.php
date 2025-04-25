@extends('partials.headerFooter')

@section('content')
@include('partials.sidebar')

<main id="main" class="main">
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
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
                        <h2>{{ auth()->user()->name }}</h2>
                        <h3>{{ auth()->user()->job_title ?? '-' }}</h3>
                        <div class="social-links mt-2">
                            @foreach (['twitter', 'facebook', 'instagram', 'linkedin'] as $social)
                                @if (auth()->user()->$social)
                                    <a href="{{ auth()->user()->$social }}" class="{{ $social }}">
                                        <i class="bi bi-{{ $social }}"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

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
                            {{-- Profile Overview --}}
                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <h5 class="card-title">About</h5>
                                <p class="small fst-italic">{{ auth()->user()->about ?? '-' }}</p>

                                <h5 class="card-title">Profile Details</h5>
                                @php
                                    $profileDetails = [
                                        'Full Name' => auth()->user()->name,
                                        'Company' => auth()->user()->company ?? '-',
                                        'Job' => auth()->user()->job_title ?? '-',
                                        'Country' => auth()->user()->country ?? '-',
                                        'Phone' => auth()->user()->phone ?? '-',
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
                                <form method="POST" action="{{ route('profile.update') }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="row mb-3">
                                        <label for="name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="name" type="text" class="form-control" value="{{ old('name', auth()->user()->name) }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="job_title" class="col-md-4 col-lg-3 col-form-label">Job</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="job_title" type="text" class="form-control" value="{{ old('job_title', auth()->user()->job_title) }}">
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
                        </div><!-- End Tab Content -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
