@extends('layout')
@section('title', 'Edit Mahasiswa.exe')

@section('content')
<div class="toolbar"><h2>Edit Mahasiswa</h2></div>
<form action="{{ route('mahasiswa.update', $mahasiswa) }}" method="POST" data-loading>
    @csrf @method('PUT')
    @include('mahasiswa._form')
</form>
@endsection