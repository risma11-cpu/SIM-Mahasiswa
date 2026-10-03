<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['TI',  'Teknik Informatika'],
            ['SI',  'Sistem Informasi'],
            ['TE',  'Teknik Elektro'],
            ['MN',  'Manajemen'],
            ['AK',  'Akuntansi'],
            ['DKV', 'Desain Komunikasi Visual'],
        ];

        foreach ($data as [$kode, $nama]) {
            ProgramStudi::updateOrCreate(['kode' => $kode], ['nama' => $nama]);
        }
    }
}