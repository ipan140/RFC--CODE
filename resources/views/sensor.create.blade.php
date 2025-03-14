<title>RFC - App | {{$title}}</title>
  @extends('partials.headerFooter')
  @include('partials.sidebar')
@section('content')
<main id="main" class="main">

    <div class="pagetitle">
      <h1>Tambah Sensor</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Tambah Sensor</li>
        </ol>
      </nav>
    </div>
    <!-- End Page Title -->

    <section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title text-center">Form Tambah Sensor</h5>
          
          <!-- Form Start -->
          <form>
            <div class="mb-3">
              <label for="namaSensor" class="form-label fw-bold">Nama Sensor</label>
              <input type="text" class="form-control" id="namaSensor" placeholder="Masukkan Nama Sensor" required>
            </div>

            <div class="mb-3">
              <label for="tipeSensor" class="form-label fw-bold">Tipe Sensor</label>
              <select class="form-select" id="tipeSensor" required>
                <option selected disabled>Pilih Tipe Sensor</option>
                <option value="pH">pH Sensor</option>
                <option value="EC">EC Sensor</option>
                <option value="Humidity">Humidity Sensor</option>
                <option value="Temperature">Temperature Sensor</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Koordinat</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text">Lat</span>
                    <input type="text" class="form-control" id="latitude" placeholder="Masukkan Latitude" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text">Lng</span>
                    <input type="text" class="form-control" id="longitude" placeholder="Masukkan Longitude" required>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-center mt-4">
              <button type="submit" class="btn btn-primary px-4">Tambah Sensor</button>
            </div>
          </form>
          <!-- Form End -->

        </div>
      </div>

    </div>
  </div>
</section>


  </main><!-- End #main -->

@endsection