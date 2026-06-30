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
                'nama_lengkap' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@test.com',
                'password' => Hash::make('12345678'),
                'created_at' => now(),
            ],
        ]);
    }
}
