<?php

namespace Database\Seeders;

use App\Models\PeralatanJenis;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeralatanJenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PeralatanJenis::insert([
            ['nama_jenis' => 'Genset'],
            ['nama_jenis' => 'UPS'],
            ['nama_jenis' => 'Panel MCB'],
            ['nama_jenis' => 'Panel Pengumpul'],
            ['nama_jenis' => 'Panel Stop Kontak'],
        ]);
    }
}
