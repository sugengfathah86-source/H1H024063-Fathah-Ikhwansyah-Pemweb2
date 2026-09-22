<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\MatakuliahController;
use App\Http\Controllers\Api\ProgramStudiController;
Route::get('/status', function () {
    return response()->json([
        'sukses' => true,
        'pesan' => 'API Pemweb II aktif',
        'waktu' => now()->toIso8601String(),
    ]);
});

// Rute Modul Utama & Tugas 3
Route::apiResource('mahasiswa', MahasiswaController::class);

// Rute Tugas 1 (Matakuliah)
Route::apiResource('matakuliah', MatakuliahController::class);

// Rute Tugas 2 (Mahasiswa per Program Studi)
Route::get('/program-studi/{id}/mahasiswa', [ProgramStudiController::class, 'mahasiswa']);
Route::get('/program-studi/{id}/mahasiswa', [ProgramStudiController::class, 'mahasiswa']);
Route::apiResource('matakuliah', MatakuliahController::class);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rute untuk mengecek status
Route::get('/status', function () {
    return response()->json([
        'sukses' => true,
        'pesan' => 'API berjalan lancar'
    ]);
});

// Daftarkan rute resource mahasiswa
Route::apiResource('mahasiswa', MahasiswaController::class);