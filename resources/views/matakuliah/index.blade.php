@extends('layouts.app')
@section('judul', 'Daftar Matakuliah')

@section('konten')
<h1 class="h3 mb-4">Daftar Matakuliah</h1>

<!-- Formulir Fitur Pencarian -->
<form method="GET" action="{{ route('matakuliah.index') }}" class="mb-3 d-flex gap-2">
    <input type="text" name="q" class="form-control" placeholder="Cari nama matakuliah..." value="{{ request('q') }}">
    <button type="submit" class="btn btn-primary">Cari</button>
</form>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>SKS</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($daftarMk as $mk)
        <tr>
            <td>{{ $mk['kode'] }}</td>
            <td>{{ $mk['nama'] }}</td>
            <td>
                <!-- Memanggil komponen Badge -->
                <x-badge-sks :sks="$mk['sks']" />
            </td>
            <td>
                <a href="{{ route('matakuliah.show', $mk['kode']) }}" class="btn btn-sm btn-primary">Detail</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center">Data matakuliah tidak ditemukan.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection