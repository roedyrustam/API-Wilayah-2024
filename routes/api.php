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
use App\Http\Controllers\Api\NearbyController;
use App\Http\Controllers\Api\GeoJsonController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\KodePosController;
use App\Http\Controllers\Api\CascaderController;

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

    // Dedicated Kode Pos (Postal Code) 5-Digit Endpoints
    Route::get('/kodepos', [KodePosController::class, 'index']);
    Route::get('/kodepos/{code}', [KodePosController::class, 'show']);

    // Single-Trip Hierarchical Cascader Dropdown Endpoints
    Route::get('/cascader/provinsi/{id}', [CascaderController::class, 'provinsi']);
    Route::get('/cascader/kabupaten/{id}', [CascaderController::class, 'kabupaten']);

    // Autocomplete Search
    Route::get('/search', [SearchController::class, 'search']);

    // Proximity / Nearby Geospatial Search
    Route::get('/nearby', [NearbyController::class, 'index']);

    // GeoJSON RFC 7946 Standard Endpoints
    Route::get('/geojson/provinsi', [GeoJsonController::class, 'provinsi']);
    Route::get('/geojson/provinsi/{id}', [GeoJsonController::class, 'provinsiDetail']);
    Route::get('/geojson/kabupaten', [GeoJsonController::class, 'kabupaten']);

    // Master Data Export (CSV / JSON stream)
    Route::get('/export/{level}', [ExportController::class, 'export']);
});

// Root aliases for v1 for backward compatibility (e.g. /api/provinsi directly)
Route::get('/search', [SearchController::class, 'search']);
Route::get('/nearby', [NearbyController::class, 'index']);
Route::get('/geojson/provinsi', [GeoJsonController::class, 'provinsi']);
Route::get('/geojson/provinsi/{id}', [GeoJsonController::class, 'provinsiDetail']);
Route::get('/geojson/kabupaten', [GeoJsonController::class, 'kabupaten']);
Route::get('/export/{level}', [ExportController::class, 'export']);
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

Route::get('/kodepos', [KodePosController::class, 'index']);
Route::get('/kodepos/{code}', [KodePosController::class, 'show']);

Route::get('/cascader/provinsi/{id}', [CascaderController::class, 'provinsi']);
Route::get('/cascader/kabupaten/{id}', [CascaderController::class, 'kabupaten']);

