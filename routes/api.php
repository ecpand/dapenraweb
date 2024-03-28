<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apk\DapenraController;
use App\Http\Controllers\Apk\DapenraLogin;
use App\Http\Controllers\Apk\DapenraRegister;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('rpensiun', [DapenraRegister::class, 'registerpensiun']);
Route::post('rtertanggung', [DapenraRegister::class, 'registertertanggung']);
Route::post('getPensiun', [DapenraRegister::class, 'getPensiun']);
Route::post('/login', [DapenraController::class, 'login']);
Route::get('getKontak', [DapenraController::class, 'getKontak']);
//Route::post('/simpanRekam',[DapenraController::class, 'simpanrekam']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/getToken', function(Request $request){
        return $request->user();
    });
    Route::post('/informasi',[DapenraController::class, 'informasi']);
    Route::post('/getOt',[DapenraController::class, 'getOt']);
    Route::post('/simpanRekam',[DapenraController::class, 'simpanrekam']);
    Route::post('/simpanEx',[DapenraController::class, 'simpanEx']);
    Route::post('/simpanOt',[DapenraController::class, 'simpanOt']);
});

//Route::middleware('auth:sanctum')->get('/sanctum-check', [DapenraController::class, 'check']);
//Route::middleware('auth:sanctum')->get('/debug', [DapenraController::class, 'debug']);
