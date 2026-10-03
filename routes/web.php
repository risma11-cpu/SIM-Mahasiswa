<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('mahasiswa/export', [MahasiswaController::class, 'export'])->name('mahasiswa.export');
Route::get('mahasiswa/print', [MahasiswaController::class, 'print'])->name('mahasiswa.print');
Route::resource('mahasiswa', MahasiswaController::class);