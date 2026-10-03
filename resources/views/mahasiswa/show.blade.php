@extends('layout')
@section('title', 'Detail Mahasiswa.exe')

@section('content')
<div class="toolbar"><h2>Detail Mahasiswa</h2></div>

<div class="table-wrap">
    <table class="detail">
        <tr><th>NPM</th><td>{{ $mahasiswa->npm }}</td></tr>
        <tr><th>Nama Lengkap</th><td>{{ $mahasiswa->nama }}</td></tr>
        <tr><th>Email</th><td>{{ $mahasiswa->email }}</td></tr>
        <tr><th>Nomor HP</th><td>{{ $mahasiswa->no_hp ?? '-' }}</td></tr>
        <tr><th>Program Studi</th><td>{{ $mahasiswa->programStudi->nama }}</td></tr>
        <tr><th>Semester</th><td>{{ $mahasiswa->semester }}</td></tr>
        <tr><th>Jenis Kelamin</th><td>{{ $mahasiswa->jenis_kelamin_label }}</td></tr>
        <tr><th>Alamat</th><td>{{ $mahasiswa->alamat }}</td></tr>
        <tr><th>Terdaftar</th><td>{{ $mahasiswa->created_at->format('d M Y, H:i') }}</td></tr>
    </table>
</div>

<p class="btn-group">
    <a href="{{ route('mahasiswa.edit', $mahasiswa) }}" class="btn btn-primary">Edit</a>
    <button type="button" class="btn btn-danger"
            data-delete="{{ route('mahasiswa.destroy', $mahasiswa) }}"
            data-name="{{ $mahasiswa->nama }}">Hapus</button>
    <a href="{{ route('mahasiswa.index') }}" class="btn">Kembali</a>
</p>
@endsection