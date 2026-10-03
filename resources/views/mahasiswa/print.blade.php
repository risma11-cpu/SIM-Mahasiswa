<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Mahasiswa</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 24px; color: #000; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        p { margin: 0 0 14px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 5px 7px; text-align: left; vertical-align: top; }
        th { background: #eee; }
        .no-print { margin-bottom: 14px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">🖨 Cetak / Simpan PDF</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <h1>Data Mahasiswa</h1>
    <p>Dicetak: {{ now()->format('d M Y, H:i') }} · Total: {{ $mahasiswas->count() }} mahasiswa</p>

    <table>
        <thead>
            <tr>
                <th>No</th><th>NPM</th><th>Nama</th><th>Email</th><th>No. HP</th>
                <th>Program Studi</th><th>Smt</th><th>JK</th><th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mahasiswas as $m)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $m->npm }}</td>
                <td>{{ $m->nama }}</td>
                <td>{{ $m->email }}</td>
                <td>{{ $m->no_hp ?? '-' }}</td>
                <td>{{ $m->programStudi->nama }}</td>
                <td>{{ $m->semester }}</td>
                <td>{{ $m->jenis_kelamin }}</td>
                <td>{{ $m->alamat }}</td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>