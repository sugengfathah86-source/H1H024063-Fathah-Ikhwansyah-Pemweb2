<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class MatakuliahController extends Controller
{
    // Menyimpan data minimal 5 matakuliah
    private $dataMk = [
        ['kode' => 'TKA101', 'nama' => 'Algoritma dan Pemrograman', 'sks' => 3],
        ['kode' => 'TKA102', 'nama' => 'Pendidikan Agama', 'sks' => 2],
        ['kode' => 'TKA103', 'nama' => 'Struktur Data', 'sks' => 3],
        ['kode' => 'TKA104', 'nama' => 'Kewarganegaraan', 'sks' => 2],
        ['kode' => 'TKA105', 'nama' => 'Pemrograman Web II', 'sks' => 4],
    ];

    public function index(Request $request)
    {
        $katakunci = $request->query('q', '');
        $hasil = collect($this->dataMk);

        // Filter pencarian jika ada query string
        if ($katakunci) {
            $hasil = $hasil->filter(fn($mk) => stripos($mk['nama'], $katakunci) !== false);
        }

        return view('matakuliah.index', ['daftarMk' => $hasil]);
    }

    public function show(string $kode)
    {
        // Mencari matakuliah spesifik berdasarkan kode
        $mk = collect($this->dataMk)->firstWhere('kode', $kode);
        return view('matakuliah.show', ['mk' => $mk]);
    }
}