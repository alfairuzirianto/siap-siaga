<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'nama_lengkap' => 'Reza Syahputra',
                'nip' => '92141015ZY',
                'jabatan' => 'Ph. Asman Jaringan dan Konstruksi',
                'unit' => 'UP3 Palembang',
                'username' => 'reza',
                'email' => 'reza@test.com',
                'password' => Hash::make('12345678'),
                'created_at' => now(),
            ],
        ]);
    }
}
