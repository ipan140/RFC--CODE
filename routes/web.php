<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\{
    HomeController,
    DashboardController,
    SensorController,
    SensorPHController,
    SensorPotaController,
    SensorPhosporController,
    SensorECController,
    SensorTempController,
    SensorHumidityController,
    SensorNitrogenController,
    ProfileController,
    MitraController,
    ProyekController,
    AntaresController,
    DashboardAdminController,
    DashboardUserController,
    SensorChartController,
    UserController,
    TanamanController,
    RiwayatTanamanController,
    PeriodeTanamController,
    DashboardOwnerController,
    ChartController,
    InputanHarianController,
    KategoriSampelController,
    TanamanSampelController,
    SampelController,
    DenahSensorController
};
use App\Http\Controllers\ActivityLogController;

// === Landing Page ===
Route::get('/', [HomeController::class, 'index'])->name('home');

// === Dashboard sesuai Role ===
// Redirect dashboard sesuai role setelah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        return redirect()->route("{$role}.dashboard");
    })->name('dashboard');
});

// Dashboard khusus berdasarkan role (dengan middleware role)

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    Route::resources([
        'mitra' => MitraController::class,
        'proyek' => ProyekController::class,
        'user' => UserController::class,
    ]);

    Route::get('/table', [SensorChartController::class, 'index']);
    Route::view('/riwayat_sensor', 'riwayat_sensor');
    Route::get('/chart', [ChartController::class, 'index']);

    Route::resources([
        'tanaman' => TanamanController::class,
        'sensor' => SensorController::class,
        'sensor_ph' => SensorPHController::class,
        'sensor_pota' => SensorPotaController::class,
        'sensor_phospor' => SensorPhosporController::class,
        'sensor_EC' => SensorECController::class,
        'sensor_temp' => SensorTempController::class,
        'sensor_humidity' => SensorHumidityController::class,
        'sensor_Nitrogen' => SensorNitrogenController::class,
        'riwayat_tanaman' => RiwayatTanamanController::class,
        'periode_tanam' => PeriodeTanamController::class,
        'input_harian' => InputanHarianController::class, // Tambahkan resource ini di sini
    ]);
    Route::resource('kategori_sampel', KategoriSampelController::class);
    Route::resource('sampel', SampelController::class);
    Route::get('/kategori-pengamatan/{id}', [TanamanSampelController::class, 'getKategoriPengamatan']);
    Route::get('/denah_sensor', [DenahSensorController::class, 'index'])->name('denah.sensor');
});

Route::get('/logaktivitas', [ActivityLogController::class, 'index'])->name('logaktivitas.index');
/*
|--------------------------------------------------------------------------
| Owner Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [DashboardOwnerController::class, 'index'])->name('dashboard');

    // Jika Owner tidak punya akses ke mitra, proyek, user, hapus bagian ini
    Route::resources([
        'mitra' => MitraController::class,
        'proyek' => ProyekController::class,
        'user' => UserController::class,
    ]);

    Route::get('/table', [SensorChartController::class, 'index']);
    Route::view('/riwayat_sensor', 'riwayat_sensor');
    Route::get('/chart', [ChartController::class, 'index']);

    Route::resources([
        'riwayat_tanaman' => RiwayatTanamanController::class,
        'periode_tanam' => PeriodeTanamController::class,
        'tanaman' => TanamanController::class,
        'sensor' => SensorController::class,
        'sensor_ph' => SensorPHController::class,
        'sensor_pota' => SensorPotaController::class,
        'sensor_phospor' => SensorPhosporController::class,
        'sensor_EC' => SensorECController::class,
        'sensor_temp' => SensorTempController::class,
        'sensor_humidity' => SensorHumidityController::class,
        'sensor_Nitrogen' => SensorNitrogenController::class,
        'input_harian' => InputanHarianController::class, // Tambahkan resource ini di sini
    ]);
    Route::resource('kategori_sampel', KategoriSampelController::class);
    Route::resource('sampel', SampelController::class);
    Route::get('/denah_sensor', [DenahSensorController::class, 'index'])->name('denah.sensor');
});
/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardUserController::class, 'index'])->name('dashboard');

    // Khusus user
    Route::resources([
        'riwayat_tanaman' => RiwayatTanamanController::class,
        'periode_tanam' => PeriodeTanamController::class,
    ]);
    Route::get('/chart', [ChartController::class, 'index']);
    // Route bersama
    Route::resources([
        'tanaman' => TanamanController::class,
        'sensor' => SensorController::class,
        'sensor_ph' => SensorPHController::class,
        'sensor_pota' => SensorPotaController::class,
        'sensor_phospor' => SensorPhosporController::class,
        'sensor_EC' => SensorECController::class,
        'sensor_temp' => SensorTempController::class,
        'sensor_humidity' => SensorHumidityController::class,
        'sensor_Nitrogen' => SensorNitrogenController::class,
        'input_harian' => InputanHarianController::class, // Tambahkan resource ini di sini
    ]);
    Route::resource('kategori_sampel', KategoriSampelController::class);
    Route::resource('sampel', SampelController::class);
    Route::get('/denah_sensor', [DenahSensorController::class, 'index'])->name('denah.sensor');
});

Route::middleware(['auth', 'role:admin,owner,user'])->group(function () {
    // === Sensor General ===
    Route::resource('sensor', SensorController::class);
    Route::get('/tambah_sensor', [SensorController::class, 'create'])->name('tambah_sensor');
    Route::get('/export/sensor', [SensorController::class, 'export'])->name('sensor.export');
    Route::post('/sensor/fetch-store', [SensorController::class, 'fetchAndStoreAll'])->name('sensor.fetchAndStore');


    // === Sensor pH ===
    Route::resource('sensor_ph', SensorPHController::class);
    Route::get('/sensor_ph/data', [SensorPHController::class, 'getData'])->name('sensor_ph.data');
    Route::get('/export/ph', [SensorPHController::class, 'export'])->name('ph.export');
    Route::get('/sensor_ph/fetch-store', [SensorPHController::class, 'fetchAndStore'])->name('sensor_ph.fetch-store');

    // === Sensor Potassium ===
    Route::resource('sensor_pota', SensorPotaController::class);
    Route::get('/sensor_pota/data', [SensorPotaController::class, 'getData'])->name('sensor_pota.data');
    Route::get('/export/pota', [SensorPotaController::class, 'export'])->name('pota.export');
    Route::get('/sensor_pota/fetch-store', [SensorPotaController::class, 'fetchAndStore'])->name('sensor_pota.fetch-store');

    // === Sensor Phosphor ===
    Route::resource('sensor_phospor', SensorPhosporController::class);
    Route::get('/sensor_phospor/data', [SensorPhosporController::class, 'getData'])->name('sensor_phospor.data');
    Route::get('/export/phospor', [SensorPhosporController::class, 'export'])->name('phospor.export');
    Route::get('/sensor_phospor/fetch-store', [SensorPhosporController::class, 'fetchAndStore'])->name('sensor_phospor.fetch-store');

    // === Sensor EC ===
    Route::resource('sensor_ec', SensorECController::class);
    Route::get('/sensor_ec/data', [SensorECController::class, 'getData'])->name('sensor_ec.data');
    Route::get('/export/ec', [SensorECController::class, 'export'])->name('ec.export');
    Route::get('/sensor_ec/fetch-store', [SensorECController::class, 'fetchAndStore'])->name('sensor_ec.fetch-store');

    // === Sensor Temperature ===
    Route::resource('sensor_temp', SensorTempController::class);
    Route::get('/sensor_temp/data', [SensorTempController::class, 'getData'])->name('sensor_temp.data');
    Route::get('/export/temp', [SensorTempController::class, 'export'])->name('temp.export');
    Route::get('/sensor_temp/fetch-store', [SensorTempController::class, 'fetchAndStore'])->name('sensor_temp.fetch-store');

    // === Sensor Humidity ===
    Route::resource('sensor_humidity', SensorHumidityController::class);
    Route::get('/sensor_humidity/data', [SensorHumidityController::class, 'getData'])->name('sensor_humidity.data');
    Route::get('/export/humidity', [SensorHumidityController::class, 'export'])->name('humidity.export');
    Route::get('/sensor_humidity/fetch-store', [SensorHumidityController::class, 'fetchAndStore'])->name('sensor_humidity.fetch-store');

    // === Sensor Nitrogen ===
    Route::resource('sensor_nitrogen', SensorNitrogenController::class);
    Route::get('/sensor_nitrogen/data', [SensorNitrogenController::class, 'getData'])->name('sensor_nitrogen.data');
    Route::get('/export/nitrogen', [SensorNitrogenController::class, 'export'])->name('nitrogen.export');
    Route::get('/sensor_nitrogen/fetch-store', [SensorNitrogenController::class, 'fetchAndStore'])->name('sensor_nitrogen.fetch-store');

    // === Riwayat dan Manajemen Tanam ===
    Route::resource('riwayat_tanaman', RiwayatTanamanController::class);
    Route::get('/riwayat-tanaman/export', [RiwayatTanamanController::class, 'export'])->name('riwayat_tanaman.export');
    Route::resource('Periode_tanam', PeriodeTanamController::class);
    Route::resource('tanaman', TanamanController::class);
    Route::resource('input_harian', InputanHarianController::class);
    Route::get('/periode_tanam/export', [PeriodeTanamController::class, 'export'])->name('periode_tanam.export');
    Route::resource('kategori_sampel', KategoriSampelController::class);
    Route::get('/sampel/export', [TanamanSampelController::class, 'export'])->name('sampel.export');
    Route::get('/sampel', [TanamanSampelController::class, 'index'])->name('sampel.index');
    Route::resource('sampel', SampelController::class);
    Route::get('/denah_sensor', [DenahSensorController::class, 'index'])->name('denah.sensor');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard/admin', [DashboardAdminController::class, 'index'])->name('dashboard.admin');
    Route::get('/dashboard/owner', [DashboardOwnerController::class, 'index'])->name('dashboard.owner');
    Route::get('/dashboard/user', [DashboardUserController::class, 'index'])->name('dashboard.user');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});
// === Data Riwayat dan Chart ===
Route::get('/table', [SensorChartController::class, 'index']);

Route::get('/riwayat_sensor', function () {
    $nama = "Pak Helmy";
    return view('riwayat_sensor', [
        "title" => "Riwayat Sensor",
        "username" => "Admin",
        "roles" => "Admin",
        "image" => "pakdekan.png",
        "nama_lengkap" => $nama
    ]);
});

Route::get('/chart', function () {
    return view('chart', [
        "title" => "Chart",
        "username" => "Admin",
        "roles" => "Admin",
        "image" => "pakdekan.png",
        "nama_lengkap" => "Dr. Helmy Widyantara"
    ]);
});


Route::resource('tanaman', TanamanController::class);
Route::resource('mitra', MitraController::class);
Route::resource('proyek', ProyekController::class);
Route::resource('user', UserController::class);
Route::resource('riwayat_tanaman', RiwayatTanamanController::class);
Route::resource('periode_tanam', PeriodeTanamController::class);

// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/sensor/{sensor}', [AntaresController::class, 'getSensorData']);
// routes/web.php
// Route::get('/dashboard', [AntaresController::class, 'index']);


Route::get('/peta', function () {
    return view('peta', [
        "title" => "Denah Sensor",
        "username" => "Admin",
        "roles" => "Admin",
        "image" => "pakdekan.png",
        "nama_lengkap" => "Dr. Helmy Widyantara"
    ]);
});
// === Sensor Umum ===
Route::resource('sensor', SensorController::class);
Route::get('/sensor', [SensorController::class, 'index'])->name('sensor.index');
Route::get('/tambah_sensor', [SensorController::class, 'create'])->name('tambah_sensor');
Route::get('/export/sensor', [SensorController::class, 'export'])->name('sensor.export');
Route::post('/sensor/fetch', [SensorController::class, 'fetch'])->name('sensor.fetch');
// === Sensor pH ===
Route::resource('sensor_ph', SensorPHController::class);
Route::get('/sensor_ph/data', [SensorPHController::class, 'getData']);
Route::get('/export/ph', [SensorPHController::class, 'export'])->name('ph.export');
Route::get('/api/fetch-store-ph', [SensorPHController::class, 'fetchAndStore']);
Route::get('/sensor-ph/fetch-store', [SensorPHController::class, 'fetchAndStore'])->name('sensor_ph.fetch-store');

// === Sensor Potassium ===
Route::resource('sensor_pota', SensorPotaController::class);
Route::get('/sensor_pota/data', [SensorPotaController::class, 'getData']);
Route::get('/export/pota', [SensorPotaController::class, 'export'])->name('pota.export');
Route::get('/api/fetch-store-pota', [SensorPotaController::class, 'fetchAndStore']);
Route::get('/sensor-pota/fetch-store', [SensorPotaController::class, 'fetchAndStore'])->name('sensor_pota.fetch-store');

// === Sensor Phosphor ===
Route::resource('sensor_phospor', SensorPhosporController::class);
Route::get('/sensor_phospor/data', [SensorPhosporController::class, 'getData']);
Route::get('/export/phospor', [SensorPhosporController::class, 'export'])->name('phospor.export');
Route::get('/api/fetch-store-phospor', [SensorPhosporController::class, 'fetchAndStore']);
Route::get('/sensor-phospor/fetch-store', [SensorPhosporController::class, 'fetchAndStore'])->name('sensor_phospor.fetch-store');


// === Sensor EC (Electrical Conductivity) ===
Route::resource('sensor_EC', SensorECController::class);
Route::get('/sensor_EC/data', [SensorECController::class, 'getData']);
Route::get('/export/EC', [SensorECController::class, 'export'])->name('EC.export');
Route::get('/api/fetch-store-EC', [SensorECController::class, 'fetchAndStore']);
Route::get('/sensor-EC/fetch-store', [SensorECController::class, 'fetchAndStore'])->name('sensor_EC.fetch-store');

// === Sensor Suhu (Temperature) ===
Route::resource('sensor_temp', SensorTempController::class);
Route::get('/sensor_temp/data', [SensorTempController::class, 'getData']);
Route::get('/export/temp', [SensorTempController::class, 'export'])->name('temp.export');
Route::get('/api/fetch-store-temp', [SensorTempController::class, 'fetchAndStore']);
Route::get('/sensor-temp/fetch-store', [SensorTempController::class, 'fetchAndStore'])->name('sensor_temp.fetch-store');


// === Sensor Kelembaban (Humidity) ===
Route::resource('sensor_humidity', SensorHumidityController::class);
Route::get('/sensor_humidity/data', [SensorHumidityController::class, 'getData']);
Route::get('/export/humidity', [SensorHumidityController::class, 'export'])->name('humidity.export');
Route::get('/api/fetch-store-humidity', [SensorHumidityController::class, 'fetchAndStore']);
Route::get('/sensor-humidity/fetch-store', [SensorHumidityController::class, 'fetchAndStore'])->name('sensor_humidity.fetch-store');

// === Sensor Nitrogen ===
Route::resource('sensor_Nitrogen', SensorNitrogenController::class);
Route::get('/sensor_Nitrogen/data', [SensorNitrogenController::class, 'getData']);
Route::get('/export/Nitrogen', [SensorNitrogenController::class, 'export'])->name('Nitrogen.export');
Route::get('/api/fetch-store-Nitrogen', [SensorNitrogenController::class, 'fetchAndStore']);
Route::get('/sensor-Nitrogen/fetch-store', [SensorNitrogenController::class, 'fetchAndStore'])->name('sensor_Nitrogen.fetch-store');


Route::resource('tanaman', TanamanController::class);
Route::view('/chart', 'chart');
// === Auth Default Laravel ===
require __DIR__ . '/auth.php';