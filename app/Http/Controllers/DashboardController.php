<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\ProgramStudi;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'total'     => Mahasiswa::count(),
            'laki'      => Mahasiswa::where('jenis_kelamin', 'L')->count(),
            'perempuan' => Mahasiswa::where('jenis_kelamin', 'P')->count(),
            'prodis'    => ProgramStudi::withCount('mahasiswas')->orderBy('nama')->get(),
            'terbaru'   => Mahasiswa::with('programStudi')->latest()->take(5)->get(),
        ]);
    }
}