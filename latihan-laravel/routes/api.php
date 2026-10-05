<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\MatakuliahController;
use App\Http\Controllers\Api\ProgramStudiController;
use App\Http\Middleware\PeranAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ---------- Rute publik ----------
Route::get('/status', function () {
    return response()->json([
        'sukses' => true,
        'pesan'  => 'API Pemweb II aktif',
        'waktu'  => now()->toIso8601String(),
    ]);
});

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

// ---------- Rute terlindungi (wajib token) ----------
Route::middleware('auth:sanctum')->group(function () {

    // Autentikasi
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/auth/profil', [AuthController::class, 'profil']);
    Route::put('/auth/password', [AuthController::class, 'updatePassword']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/logout-semua', [AuthController::class, 'logoutSemua']);

    // Baca: semua pengguna yang login
    Route::apiResource('mahasiswa', MahasiswaController::class)->only(['index', 'show']);
    Route::apiResource('matakuliah', MatakuliahController::class)->only(['index', 'show']);
    Route::get('/program-studi/{id}/mahasiswa', [ProgramStudiController::class, 'mahasiswa']);

    // Tulis: hanya token dengan kemampuan mahasiswa:tulis
    Route::middleware('ability:mahasiswa:tulis')->group(function () {
        Route::apiResource('mahasiswa', MahasiswaController::class)->except(['index', 'show', 'destroy']);
        Route::apiResource('matakuliah', MatakuliahController::class)->except(['index', 'show']);

        // Tugas 2: hapus mahasiswa hanya untuk peran admin
        Route::delete('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'destroy'])
            ->middleware(PeranAdmin::class);
    });
});