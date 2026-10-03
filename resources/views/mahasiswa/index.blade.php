@extends('layout')
@section('title', 'Data Mahasiswa.exe')

@section('content')
<div class="toolbar">
    <h2>Data Mahasiswa</h2>
    <div class="btn-group">
        <a href="{{ route('mahasiswa.export', request()->query()) }}" class="btn">⬇ Export Excel</a>
        <a href="{{ route('mahasiswa.print', request()->query()) }}" target="_blank" class="btn">🖨 Cetak / PDF</a>
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">+ Tambah</a>
    </div>
</div>

<form method="GET" action="{{ route('mahasiswa.index') }}" class="filters">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari NPM atau nama...">
    <select name="program_studi_id">
        <option value="">Semua program studi</option>
        @foreach ($programStudis as $p)
            <option value="{{ $p->id }}" @selected(request('program_studi_id') == $p->id)>{{ $p->nama }}</option>
        @endforeach
    </select>
    <select name="semester">
        <option value="">Semua semester</option>
        @for ($i = 1; $i <= 14; $i++)
            <option value="{{ $i }}" @selected(request('semester') == $i)>Semester {{ $i }}</option>
        @endfor
    </select>
    <select name="per_page" onchange="this.form.submit()" title="Jumlah data per halaman">
        @foreach ([10, 25, 50] as $n)
            <option value="{{ $n }}" @selected($perPage === $n)>{{ $n }} / halaman</option>
        @endforeach
    </select>
    <button class="btn btn-primary">Cari</button>
    <a href="{{ route('mahasiswa.index') }}" class="btn">Reset</a>
</form>

@if ($mahasiswas->isEmpty())
    <div class="empty-state">
        <div class="icon">📂</div>
        @if (request()->hasAny(['q', 'program_studi_id', 'semester']))
            <p>Data tidak ditemukan. Coba ubah kata kunci atau reset filter.</p>
            <a href="{{ route('mahasiswa.index') }}" class="btn">Reset Filter</a>
        @else
            <p>Belum ada data mahasiswa.</p>
            <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">+ Tambah Mahasiswa</a>
        @endif
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th><th>NPM</th><th>Nama</th><th>Program Studi</th>
                    <th>Semester</th><th>JK</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mahasiswas as $m)
                <tr>
                    <td>{{ $mahasiswas->firstItem() + $loop->index }}</td>
                    <td>{{ $m->npm }}</td>
                    <td>{{ $m->nama }}</td>
                    <td>{{ $m->programStudi->nama }}</td>
                    <td>{{ $m->semester }}</td>
                    <td>{{ $m->jenis_kelamin }}</td>
                    <td class="actions">
                        <a href="{{ route('mahasiswa.show', $m) }}" class="btn btn-sm">Detail</a>
                        <a href="{{ route('mahasiswa.edit', $m) }}" class="btn btn-sm">Edit</a>
                        <button type="button" class="btn btn-sm btn-danger"
                                data-delete="{{ route('mahasiswa.destroy', $m) }}"
                                data-name="{{ $m->nama }}">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="table-info">
        <small>Menampilkan {{ $mahasiswas->firstItem() }} - {{ $mahasiswas->lastItem() }} dari {{ $mahasiswas->total() }} data</small>
        {{ $mahasiswas->links('vendor.pagination.y2k') }}
    </div>
@endif
@endsection