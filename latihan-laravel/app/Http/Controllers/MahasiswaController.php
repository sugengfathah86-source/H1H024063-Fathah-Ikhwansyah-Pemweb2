<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    // Dipanggil dari: GET /data-mahasiswa (name: mahasiswa.index)
    public function index()
    {
        $mahasiswas = Mahasiswa::with('programStudi')->get();

        return response()->json($mahasiswas);
    }

    // Dipanggil dari: GET /data-mahasiswa/{nim} (name: mahasiswa.show)
    public function show(string $nim)
    {
        $mahasiswa = Mahasiswa::with('programStudi')->where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json(['pesan' => 'Mahasiswa tidak ditemukan'], 404);
        }

        return response()->json($mahasiswa);
    }

    // Dipanggil dari: GET /cari-mahasiswa?kata=...
    public function cari(Request $request)
    {
        $kataKunci = $request->query('kata', '');

        $mahasiswas = Mahasiswa::with('programStudi')
            ->where('nama', 'like', '%' . $kataKunci . '%')
            ->orWhere('nim', 'like', '%' . $kataKunci . '%')
            ->get();

        return response()->json($mahasiswas);
    }
}
