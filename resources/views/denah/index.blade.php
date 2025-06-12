@extends('partials.headerFooter')
@include('partials.sidebar')
@section('content')

<title>RFC - App | {{ $title }}</title>

<style>
  #map {
    width: 100%;
    height: 500px;
    padding: 10px;
  }

  .leaflet-popup-content {
    font-size: 14px;
    line-height: 1.4;
  }
</style>

<div class="pagetitle">
  <h1>Denah Sensor</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
      <li class="breadcrumb-item active">Denah Sensor</li>
    </ol>
  </nav>
</div>

<div class="card">
  <div class="card-body">
    <h5 class="card-title">Peta Lokasi Sensor</h5>
    <div id="map"></div>
  </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
  var map = L.map('map').setView([-7.310828, 112.729189], 20);

  // Tile layers
  var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  });

  var darkLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; CARTO',
    maxZoom: 30
  });

  var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    maxZoom: 30,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
  });

  var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
    maxZoom: 30,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
  });

  osm.addTo(map);

  // Custom icon merah (tidak respon)
  const iconMerah = new L.Icon({
    iconUrl: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
    iconSize: [32, 32],
    iconAnchor: [16, 32]
  });

  // Layer group untuk sensor
  var sensorLora = L.layerGroup();

  @foreach($sensors as $index => $sensor)
    var icon = "{{ $sensor['status'] }}" === "Respon" ? new L.Icon.Default() : iconMerah;

    var popupContent = `
      <b>Nomor Sensor:</b> {{ $index + 1 }}<br>
      <b>Jenis:</b> Sensor LoRa<br>
      <b>Nama:</b> {{ ucfirst($sensor['name']) }}<br>
      <b>Status:</b> {{ $sensor['status'] }}<br>
      <b>Data:</b> {!! json_encode($sensor['data']) !!}
    `;

    var marker = L.marker(
      [{{ $sensor['lat'] }}, {{ $sensor['lng'] }}],
      { icon: icon }
    ).bindPopup(popupContent, {
      autoPan: true,
      keepInView: true,
      maxWidth: 250
    });

    sensorLora.addLayer(marker);
  @endforeach

  sensorLora.addTo(map);

  // Layer controls
  var baseLayers = {
    "OpenStreetMap": osm,
    "Google Streets": googleStreets,
    "Google Satellite": googleSat,
  };

  var overlayMaps = {
    "Sensor LoRa": sensorLora
  };

  L.control.layers(baseLayers, overlayMaps).addTo(map);
</script>

@endsection
