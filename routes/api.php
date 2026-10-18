<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\Api\ProvinsiController;
use App\Http\Controllers\Api\KabupatenController;
use App\Http\Controllers\Api\KecamatanController;
use App\Http\Controllers\Api\KelurahanController;
use App\Http\Controllers\Api\SearchController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| API Wilayah Indonesia 2026 Routes
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    // Provinsi
    Route::get('/provinsi', [ProvinsiController::class, 'index']);
    Route::get('/provinsi/{id}', [ProvinsiController::class, 'show']);
    Route::get('/provinsi/{id}/kabupaten', [ProvinsiController::class, 'kabupaten']);

    // Kabupaten / Kota
    Route::get('/kabupaten', [KabupatenController::class, 'index']);
    Route::get('/kabupaten/{id}', [KabupatenController::class, 'show']);
    Route::get('/kabupaten/{id}/kecamatan', [KabupatenController::class, 'kecamatan']);

    // Kecamatan
    Route::get('/kecamatan', [KecamatanController::class, 'index']);
    Route::get('/kecamatan/{id}', [KecamatanController::class, 'show']);
    Route::get('/kecamatan/{id}/kelurahan', [KecamatanController::class, 'kelurahan']);

    // Kelurahan / Desa
    Route::get('/kelurahan', [KelurahanController::class, 'index']);
    Route::get('/kelurahan/{id}', [KelurahanController::class, 'show']);

    // Autocomplete Search
    Route::get('/search', [SearchController::class, 'search']);
});

// Root alias for v1 for backward compatibility (e.g. /api/provinsi directly)
Route::get('/search', [SearchController::class, 'search']);
Route::get('/provinsi', [ProvinsiController::class, 'index']);
Route::get('/provinsi/{id}', [ProvinsiController::class, 'show']);
Route::get('/provinsi/{id}/kabupaten', [ProvinsiController::class, 'kabupaten']);

Route::get('/kabupaten', [KabupatenController::class, 'index']);
Route::get('/kabupaten/{id}', [KabupatenController::class, 'show']);
Route::get('/kabupaten/{id}/kecamatan', [KabupatenController::class, 'kecamatan']);

Route::get('/kecamatan', [KecamatanController::class, 'index']);
Route::get('/kecamatan/{id}', [KecamatanController::class, 'show']);
Route::get('/kecamatan/{id}/kelurahan', [KecamatanController::class, 'kelurahan']);

Route::get('/kelurahan', [KelurahanController::class, 'index']);
Route::get('/kelurahan/{id}', [KelurahanController::class, 'show']);

