@extends('layout')
@section('title', 'Tambah Mahasiswa.exe')

@section('content')
<div class="toolbar"><h2>Tambah Mahasiswa</h2></div>
<form action="{{ route('mahasiswa.store') }}" method="POST" data-loading>
    @csrf
    @include('mahasiswa._form')
</form>
@endsection