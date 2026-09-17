<!DOCTYPE html>
<html>
<head>
    <title>Detail Mahasiswa</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Detail Mahasiswa</h2>
    <p><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
    <p><strong>Nama:</strong> {{ $mahasiswa->nama }}</p>

    <h3>Daftar Matakuliah</h3>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Mata Kuliah</th>
                <th>SKS</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswa->matakuliahs as $mk)
            <tr>
                <td>{{ $mk->kode }}</td>
                <td>{{ $mk->nama }}</td>
                <td>{{ $mk->sks }}</td>
                <td>{{ $mk->pivot->nilai ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">Belum ada matakuliah yang diambil.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <br>
    <a href="{{ url('/mahasiswa-data') }}">Kembali ke Daftar Mahasiswa</a>
</body>
</html>