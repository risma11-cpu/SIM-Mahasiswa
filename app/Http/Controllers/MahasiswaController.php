<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    private function filtered(Request $request)
    {
        return Mahasiswa::query()
            ->search($request->q)
            ->when($request->program_studi_id, fn ($q, $v) => $q->where('program_studi_id', $v))
            ->when($request->semester, fn ($q, $v) => $q->where('semester', $v));
    }

    public function index(Request $request)
    {
        $perPage = in_array((int) $request->per_page, [10, 25, 50]) ? (int) $request->per_page : 10;

        $mahasiswas = $this->filtered($request)
            ->with('programStudi')
            ->orderBy('nama')
            ->paginate($perPage)
            ->withQueryString();

        return view('mahasiswa.index', [
            'mahasiswas'    => $mahasiswas,
            'programStudis' => ProgramStudi::orderBy('nama')->get(),
            'perPage'       => $perPage,
        ]);
    }

    public function create()
    {
        return view('mahasiswa.create', ['programStudis' => ProgramStudi::orderBy('nama')->get()]);
    }

    public function store(Request $request)
    {
        Mahasiswa::create($this->validateData($request));

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('programStudi');
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', [
            'mahasiswa'     => $mahasiswa,
            'programStudis' => ProgramStudi::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $mahasiswa->update($this->validateData($request, $mahasiswa->id));

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        try {
            $mahasiswa->delete();
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus data mahasiswa.');
        }

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $rows = $this->filtered($request)->with('programStudi')->orderBy('nama')->get();
        $filename = 'data-mahasiswa-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM supaya Excel membaca UTF-8
            fputcsv($out, ['NPM', 'Nama', 'Email', 'No HP', 'Program Studi', 'Semester', 'Jenis Kelamin', 'Alamat'], ';');

            foreach ($rows as $m) {
                fputcsv($out, [
                    $m->npm, $m->nama, $m->email, $m->no_hp,
                    $m->programStudi->nama, $m->semester,
                    $m->jenis_kelamin_label, $m->alamat,
                ], ';');
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function print(Request $request)
    {
        $mahasiswas = $this->filtered($request)->with('programStudi')->orderBy('nama')->get();
        return view('mahasiswa.print', compact('mahasiswas'));
    }

    private function validateData(Request $request, $id = null): array
    {
        return $request->validate([
            'npm'              => ['required', 'digits_between:8,15', Rule::unique('mahasiswas', 'npm')->ignore($id)],
            'nama'             => ['required', 'string', 'max:100'],
            'email'            => ['required', 'email', 'max:100', Rule::unique('mahasiswas', 'email')->ignore($id)],
            'no_hp'            => ['nullable', 'regex:/^[0-9+]{9,15}$/'],
            'program_studi_id' => ['required', Rule::exists('program_studis', 'id')],
            'semester'         => ['required', 'integer', 'between:1,14'],
            'jenis_kelamin'    => ['required', Rule::in(['L', 'P'])],
            'alamat'           => ['required', 'string', 'max:500'],
        ], [
            'required'       => ':attribute wajib diisi.',
            'unique'         => ':attribute sudah terdaftar.',
            'email'          => 'Format email tidak valid.',
            'digits_between' => ':attribute harus berupa angka 8 sampai 15 digit.',
            'regex'          => 'Format :attribute tidak valid (hanya angka, 9-15 digit).',
            'in'             => 'Pilihan :attribute tidak valid.',
            'exists'         => 'Pilihan :attribute tidak valid.',
            'between'        => ':attribute harus antara :min sampai :max.',
            'integer'        => ':attribute harus berupa angka.',
            'max'            => ':attribute maksimal :max karakter.',
        ], [
            'npm' => 'NPM', 'nama' => 'Nama lengkap', 'email' => 'Email', 'no_hp' => 'Nomor HP',
            'program_studi_id' => 'Program studi', 'semester' => 'Semester',
            'jenis_kelamin' => 'Jenis kelamin', 'alamat' => 'Alamat',
        ]);
    }
}