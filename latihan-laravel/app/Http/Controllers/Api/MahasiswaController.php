<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Http\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $kueri = Mahasiswa::query()->with('programStudi');

        // Pencarian berdasarkan kata kunci
        if ($request->filled('cari')) {
            $kataKunci = $request->query('cari');
            $kueri->where(function ($sub) use ($kataKunci) {
                $sub->where('nama', 'like', '%' . $kataKunci . '%')
                    ->orWhere('nim', 'like', '%' . $kataKunci . '%');
            });
        }

        // Filter berdasarkan angkatan
        if ($request->filled('angkatan')) {
            $kueri->where('angkatan', $request->integer('angkatan'));
        }

        // Filter berdasarkan program studi
        if ($request->filled('program_studi_id')) {
            $kueri->where('program_studi_id', $request->integer('program_studi_id'));
        }

        // Pengurutan (Sorting)
        $urutan = $request->query('urut', 'nama');
        $arah = $request->query('arah', 'asc');
        $kolomDiizinkan = ['nama', 'nim', 'angkatan', 'ipk'];

        if (in_array($urutan, $kolomDiizinkan, true)) {
            $kueri->orderBy($urutan, $arah === 'desc' ? 'desc' : 'asc');
        }

        // IMPLEMENTASI TUGAS 3: Parameter fields
        if ($request->filled('fields')) {
            $fields = explode(',', $request->query('fields'));
            
            // Kolom yang valid untuk meminimalisir SQL Error
            $kolomValid = ['id', 'program_studi_id', 'nim', 'nama', 'email', 'angkatan', 'ipk', 'aktif', 'created_at', 'updated_at'];
            $kolomDipilih = array_intersect($fields, $kolomValid);
            
            // Wajib menyertakan id dan program_studi_id agar relasi 'programStudi' tidak error saat dimuat
            if (!in_array('id', $kolomDipilih)) {
                $kolomDipilih[] = 'id';
            }
            if (!in_array('program_studi_id', $kolomDipilih)) {
                $kolomDipilih[] = 'program_studi_id';
            }

            $kueri->select($kolomDipilih);
        }

        // Pagination dan Return
        $perHalaman = min($request->integer('per_halaman', 10), 100);
        return MahasiswaResource::collection($kueri->paginate($perHalaman));
    }

    public function store(StoreMahasiswaRequest $request): JsonResponse
    {
        $mahasiswa = Mahasiswa::create($request->validated());
        $mahasiswa->load('programStudi');

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data mahasiswa berhasil dibuat',
            'data' => new MahasiswaResource($mahasiswa),
        ], 201);
    }

    public function show(Mahasiswa $mahasiswa): JsonResponse
    {
        $mahasiswa->load('programStudi');

        return response()->json([
            'sukses' => true,
            'data' => new MahasiswaResource($mahasiswa),
        ]);
    }

    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa): JsonResponse
    {
        $mahasiswa->update($request->validated());
        $mahasiswa->load('programStudi');

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data mahasiswa berhasil diperbarui',
            'data' => new MahasiswaResource($mahasiswa),
        ]);
    }

    public function destroy(Mahasiswa $mahasiswa): JsonResponse
    {
        $mahasiswa->delete();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data mahasiswa berhasil dihapus',
        ]);
    }
}