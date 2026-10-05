<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function mahasiswa(Request $request, $id)
    {
        // Pastikan program studi ada, akan menghasilkan 404 jika tidak ditemukan
        $programStudi = ProgramStudi::findOrFail($id);

        $perHalaman = min($request->integer('per_halaman', 10), 100);
        
        // Mengambil mahasiswa berdasarkan ID program studi
        $mahasiswa = Mahasiswa::with('programStudi')
            ->where('program_studi_id', $programStudi->id)
            ->paginate($perHalaman);

        return MahasiswaResource::collection($mahasiswa);
    }
}