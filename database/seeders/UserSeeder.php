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
                'nip' => '123456',
                'jabatan' => 'Admin',
                'unit' => 'UP3 Palembang',
                'username' => 'admin',
                'role' => User::ROLE_ADMIN,
                'email' => 'admin@test.com',
                'password' => Hash::make('12345678'),
            ], [
                'nama_lengkap' => 'Budi',
                'nip' => '123456',
                'jabatan' => 'Staff',
                'unit' => 'UP3 Palembang',
                'username' => 'budi',
                'role' => User::ROLE_PENGGUNA,
                'email' => 'budi@test.com',
                'password' => Hash::make('12345678'),
            ], [
                'nama_lengkap' => 'Reza',
                'nip' => '123456',
                'jabatan' => 'Supervisor',
                'unit' => 'UP3 Palembang',
                'username' => 'reza',
                'role' => User::ROLE_SUPERVISOR,
                'email' => 'reza@test.com',
                'password' => Hash::make('12345678'),
            ]
        ]);
    }
}
