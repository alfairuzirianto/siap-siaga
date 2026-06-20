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
            ['nama_jenis' => 'Genset', 'created_at' => now()],
            ['nama_jenis' => 'UPS', 'created_at' => now()],
            ['nama_jenis' => 'Panel MCB', 'created_at' => now()],
            ['nama_jenis' => 'Panel Pengumpul', 'created_at' => now()],
            ['nama_jenis' => 'Panel Stop Kontak', 'created_at' => now()],
        ]);
    }
}
