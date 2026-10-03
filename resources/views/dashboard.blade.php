@extends('layout')
@section('title', 'Dashboard.exe')

@section('intro')
<div class="intro" id="intro">
    <span class="spark s1">✦</span><span class="spark s2">✦</span><span class="spark s3">✦</span>
    <span class="spark s4">✦</span><span class="spark s5">✦</span><span class="spark s6">✦</span>

    <div class="intro-win">
        <div class="titlebar">
            <span>SIM Mahasiswa - Setup</span>
            <span class="ctrl"><span>_</span><span>□</span><span>×</span></span>
        </div>
        <div class="win-body">
            <div class="intro-logo">🎓</div>
            <h1 class="intro-title">SIM Mahasiswa</h1>
            <p class="intro-sub">Student Management System</p>
            <div class="intro-bar"><i></i></div>
            <p class="intro-status" id="introStatus">Memuat sistem...</p>
            <small class="intro-skip">Klik di mana saja untuk melewati</small>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="toolbar">
    <h2><span id="greet">Selamat datang</span> 👋</h2>
    <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">+ Tambah Mahasiswa</a>
</div>

<div class="stats">
    <div class="stat"><b data-count="{{ $total }}">{{ $total }}</b><small>Total Mahasiswa</small></div>
    <div class="stat"><b data-count="{{ $laki }}">{{ $laki }}</b><small>Laki-laki</small></div>
    <div class="stat"><b data-count="{{ $perempuan }}">{{ $perempuan }}</b><small>Perempuan</small></div>
    <div class="stat"><b data-count="{{ $prodis->count() }}">{{ $prodis->count() }}</b><small>Program Studi</small></div>
</div>

<h3 class="section-title">Mahasiswa per Program Studi</h3>
@forelse ($prodis as $p)
    <div class="prodi-row">
        <span>{{ $p->nama }}</span>
        <div class="bar"><i style="--w: {{ $total > 0 ? round($p->mahasiswas_count / $total * 100) : 0 }}%"></i></div>
        <b>{{ $p->mahasiswas_count }}</b>
    </div>
@empty
    <p>Belum ada program studi. Jalankan <code>php artisan db:seed</code>.</p>
@endforelse

<h3 class="section-title">Mahasiswa Terbaru</h3>
@if ($terbaru->isEmpty())
    <div class="empty-state">
        <div class="icon">📂</div>
        <p>Belum ada data mahasiswa.</p>
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">+ Tambah Mahasiswa Pertama</a>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead><tr><th>NPM</th><th>Nama</th><th>Program Studi</th><th>Semester</th></tr></thead>
            <tbody>
                @foreach ($terbaru as $m)
                    <tr>
                        <td>{{ $m->npm }}</td>
                        <td><a href="{{ route('mahasiswa.show', $m) }}">{{ $m->nama }}</a></td>
                        <td>{{ $m->programStudi->nama }}</td>
                        <td>{{ $m->semester }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection