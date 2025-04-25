<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SensorController; 
use App\Http\Controllers\SensorPHController;
use App\Http\Controllers\SensorPotaController;
use App\Http\Controllers\SensorPhosporController;
use App\Http\Controllers\SensorECController;
use App\Http\Controllers\SensorTempController;
use App\Http\Controllers\SensorHumidityController;
use App\Http\Controllers\SensorNitrogenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\AntaresController;
use App\Http\Controllers\SensorChartController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TanamanController;
use App\Http\Controllers\RiwayatTanamanController;
use Http;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('home');
});

Route::get('/welcome', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return view('test');
});
Route::get('home', ([HomeController::class, 'index']))->name('home');

Route::get('/login', function () {
    return view('login'); // Sesuaikan dengan lokasi file login.blade.php jika menggunakan Blade
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::get('/Register', function () {
    return view('Register'); // Sesuaikan dengan lokasi file login.blade.php jika menggunakan Blade
})->name('Register');
// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// });
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    return match ($role) {
        'admin' => redirect()->route('dashboard.admin'),
        'owner' => redirect()->route('dashboard.owner'),
        'user' => redirect()->route('dashboard.user'),
        default => abort(403),
    };
})->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/table', [SensorChartController::class, 'index']);

Route::get('/riwayat_sensor', function(){
    $nama = "Pak Helmy";
    return view('riwayat_sensor',[
        "title" => "Riwayat Sensor",
        "username" => "Admin",
        "roles" => "Admin",
        "image" => "pakdekan.png",
        "nama_lengkap" => $nama
    ]);
});

Route::get('/chart', function(){
    return view('chart',[
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
Route::get('/dashboard', [AntaresController::class, 'index']);


Route::get('/peta', function(){
    return view('peta',[
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

// Route::get('/rooftop', function () {
//     // $client = Http::get('https://platform.antares.id:8443/~/antares-cse/antares-id/Duren/Soil_Moisture/la')->status();
//     $client = Http::withHeaders([
//         'X-M2M-Origin' => 'dd211a876a8f2d07:adbc47751f3dc110',
//         'Content-Type' => 'application/json;ty=4',
//         'Accept' => 'application/json'
//     ]) ->timeout(30)
//         ->get('https://platform.antares.id:8443/~/antares-cse/antares-id/RooftopITTS/pH1/la');
//     $client_decod = json_decode($client->body(), true);
//     //dd($client_decod['m2m:cin']['con']);
//     foreach ($client_decod as $key ) {
//         echo $key["con"];
//         dd($key["con"]);
//     }

//     return view('dedurian',[
//         'client' => $client
//     ]);
// });

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/logout', [AuthController::class, 'logout']);
//     Route::get('/user', [AuthController::class, 'user']);

//     Route::get('/dashboarduser', [DashboardController::class, 'index']);
//     Route::get('/table', function(){
//         return view('table', [
//             "title" => "Tabel",
//             "username" => auth()->user()->name,
//             "roles" => "Admin",
//             "image" => "pakdekan.png",
//             "nama_lengkap" => auth()->user()->name
//         ]);
//     });
// });