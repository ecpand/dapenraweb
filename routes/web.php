<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\InformasiController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\TertanggungController;
use App\Http\Controllers\Admin\RegistrasiController;
use App\Http\Controllers\Admin\OtentikasiController;
use App\Http\Controllers\Admin\ManagementStructureController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\UmkmController;
use App\Http\Controllers\Admin\VisionMissionController;
use App\Http\Controllers\Admin\WelcomeSpeechController;
use App\Http\Controllers\User\LandingPageController;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/', function () {
//     return view('frontend.master');
// });

// about
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // jadwal
    Route::resource('jadwal', JadwalController::class);

    Route::resource('informasi', InformasiController::class);
    //Route::post('getKontak', [InformasiController::class, 'getKontak'])->name('getKontak');

    // pegawai
    Route::resource('pegawai', PegawaiController::class);
    Route::post('importdata', [PegawaiController::class, 'importdata'])->name('importdata');
    //Route::post("pegawai/importdata", "Admin\Pegawai@create")->name("admin-users-permission-create");

    // tertanggung
    Route::resource('tertanggung', TertanggungController::class);

    // registrasi
    Route::resource('registrasi', RegistrasiController::class);
    Route::post('konfirmasi', [RegistrasiController::class, 'konfirmasi'])->name('konfirmasi');
    Route::post('resetwajah', [RegistrasiController::class, 'resetwajah'])->name('resetwajah');
    Route::post('nonaktifkan', [RegistrasiController::class, 'nonaktifkan'])->name('nonaktifkan');

    // otentikasi
    Route::resource('otentikasi', OtentikasiController::class);

    // otentikasi
    // Route::resource('informasi', [InformasiControlle::class]);

    //kontak
    Route::resource('kontak', RegistrasiController::class);
});



// landing page
Route::get('/', [LandingPageController::class, 'index'])->name('index-activity');

Auth::routes();

Route::match(["GET", "POST"], '/register', function () {
    return redirect('/login');
})->name('register');

Route::resource('home', App\Http\Controllers\HomeController::class);
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::post('getKontak', [HomeController::class, 'getKontak'])->name('getKontak');
