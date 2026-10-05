@extends('layouts.app')
@section('judul', 'Detail Matakuliah')

@section('konten')
<h1 class="h3 mb-4">Detail Matakuliah</h1>

<div class="card">
    <div class="card-body">
        @if($mk)
            <p><strong>Kode:</strong> {{ $mk['kode'] }}</p>
            <p><strong>Nama:</strong> {{ $mk['nama'] }}</p>
            <p><strong>Jumlah SKS:</strong> <x-badge-sks :sks="$mk['sks']" /></p>
        @else
            <p class="text-danger">Matakuliah tidak ditemukan.</p>
        @endif
    </div>
</div>
<a href="{{ route('matakuliah.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
