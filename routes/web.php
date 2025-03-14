<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\ProyekController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
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

Route::post('/Register', [AuthController::class, 'login'])->name('login.process');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// });
Route::get('/dashboard', function () {
    $pH_client = Http::withHeaders([
        'X-M2M-Origin' => '44dbb85550128192:c079ec55758e79bb',
        'Content-Type' => 'application/json;ty=4',
        'Accept' => 'application/json'
    ])
        ->get('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/ph/la');
    $pH = json_decode($pH_client->body(), true);
    // dd($pH['m2m:cin']['con']);
    foreach ($pH as $key ) {
       echo (int)($key["con"]);
       // dd((float)$key["con"]);
    }
    $ec_client = Http::withHeaders([
        'X-M2M-Origin' => '44dbb85550128192:c079ec55758e79bb',
        'Content-Type' => 'application/json;ty=4',
        'Accept' => 'application/json'
    ])
        ->get('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/EC/la');
    $ec = json_decode($ec_client->body(), true);
    foreach ($ec as $key ) {

    }
    $sm_client = Http::withHeaders([
        'X-M2M-Origin' => '44dbb85550128192:c079ec55758e79bb',
        'Content-Type' => 'application/json;ty=4',
        'Accept' => 'application/json'
    ])
        ->get('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/humidity/la');
    $sm = json_decode($sm_client->body(), true);
    $st_client = Http::withHeaders([
        'X-M2M-Origin' => '44dbb85550128192:c079ec55758e79bb',
        'Content-Type' => 'application/json;ty=4',
        'Accept' => 'application/json'
    ])
        ->get('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/temp/la');
    $st = json_decode($st_client->body(), true);
    $nitro_client = Http::withHeaders([
        'X-M2M-Origin' => '44dbb85550128192:c079ec55758e79bb',
        'Content-Type' => 'application/json;ty=4',
        'Accept' => 'application/json'
    ])
        ->get('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/Nitrogen/la');
    $nit = json_decode($nitro_client->body(), true);

    $potassium_client = Http::withHeaders([
        'X-M2M-Origin' => '44dbb85550128192:c079ec55758e79bb',
        'Content-Type' => 'application/json;ty=4',
        'Accept' => 'application/json'
    ])
        ->get('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/pota/la');
    $kalium = json_decode($potassium_client->body(), true);

    $phos_client = Http::withHeaders([
        'X-M2M-Origin' => '44dbb85550128192:c079ec55758e79bb',
        'Content-Type' => 'application/json;ty=4',
        'Accept' => 'application/json'
    ])
        ->get('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/phospor/la');
    $phosporus = json_decode($phos_client->body(), true);

    return view('dashboard', [
        // "title" => "Dashboard",
        // "username" => "Admin",
        // "roles" => "Admin",
        // "image" => "pakdekan.png",
        // "nama_lengkap" => "Dr. Helmy Widyantara",
        "data_pH" => $pH,
        "data_ec" => $ec,
        "data_sm" => $sm,
        "data_st" => $st,
        "data_nit" => $nit,
        "data_kal" => $kalium,
        "data_phos" => $phosporus
    ]);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Route::get('/dashboard', function(){
//     $nama = "Pak Helmy";
//     return view('dashboard',[
//         "title" => "Tabel",
//         "username" => "Admin",
//         "roles" => "Admin",
//         "image" => "pakdekan.png",
//         "nama_lengkap" => $nama
//     ]);
// });
Route::get('/table', function(){
    $nama = "Pak Helmy";
    return view('table',[
        "title" => "Tabel",
        "username" => "Admin",
        "roles" => "Admin",
        "image" => "pakdekan.png",
        "nama_lengkap" => $nama
    ]);
});

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

Route::resource('mitra', MitraController::class);
Route::resource('proyek', ProyekController::class);
Route::resource('user', UserController::class);
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/peta', function(){
    return view('peta',[
        "title" => "Denah Sensor",
        "username" => "Admin",
        "roles" => "Admin",
        "image" => "pakdekan.png",
        "nama_lengkap" => "Dr. Helmy Widyantara"
    ]);
});
Route::resource('sensor', SensorController::class);
Route::get('/sensor', [SensorController::class, 'index'])->name('sensor.index');
Route::get('/tambah_sensor', [SensorController::class, 'create'])->name('tambah_sensor');


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
