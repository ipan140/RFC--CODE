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
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([-7.310828, 112.729189], 20);

    // Base Layers
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 30,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 30,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    var darkLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; CARTO',
        maxZoom: 30
    });

    // Custom red icon
    var redIcon = new L.Icon({
        iconUrl: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
        iconSize: [32, 32],
        iconAnchor: [16, 32]
    });

    var sensorLayer = L.layerGroup().addTo(map);

    // Data sensor dari controller
    var sensors = @json($sensors);

    sensors.forEach((sensor, index) => {
        const isActive = sensor.status.toLowerCase() === 'respon';
        const icon = isActive ? new L.Icon.Default() : redIcon;
        const dataValue = sensor.data && sensor.data.formatted ? sensor.data.formatted : 'N/A';

        const popupContent = `
            <b>Sensor #${index + 1}</b><br>
            <b>Jenis:</b> Sensor LoRa<br>
            <b>Nama:</b> ${sensor.name.toUpperCase()}<br>
            <b>Status:</b> ${sensor.status}<br>
            <b>Data:</b> ${dataValue}
        `;

        L.marker([sensor.lat, sensor.lng], { icon: icon })
            .addTo(sensorLayer)
            .bindPopup(popupContent);
    });

    // Layer control
    var baseLayers = {
        "OpenStreetMap": osm,
        "Google Streets": googleStreets,
        "Google Satellite": googleSat,
        // "Dark Mode": darkLayer
    };

    var overlayMaps = {
        "All Sensor ": sensorLayer
    };

    L.control.layers(baseLayers, overlayMaps).addTo(map);
});
</script>

@endsection
