<?php

namespace Database\Seeders;

use App\Models\Peralatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeralatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Peralatan::insert([
            [
                'nomor_seri' => 'GNS-001',
                'peralatan_jenis_id' => 1,
                'kapasitas' => 550,
                'satuan' => 'kVA',
                'lokasi' => 'Gudang A',
                'status' => Peralatan::STATUS_TERSEDIA,
                'created_by' => 1,
                'created_at' => now()
            ], [
                'nomor_seri' => 'GNS-002',
                'peralatan_jenis_id' => 1,
                'kapasitas' => 250,
                'satuan' => 'kVA',
                'lokasi' => 'Gudang A',
                'status' => Peralatan::STATUS_TERSEDIA,
                'created_by' => 1,
                'created_at' => now()
            ], [
                'nomor_seri' => 'GNS-003',
                'peralatan_jenis_id' => 1,
                'kapasitas' => 20,
                'satuan' => 'kVA',
                'lokasi' => 'Gudang A',
                'status' => Peralatan::STATUS_TERSEDIA,
                'created_by' => 1,
                'created_at' => now()
            ], [
                'nomor_seri' => 'GNS-004',
                'peralatan_jenis_id' => 1,
                'kapasitas' => 8,
                'satuan' => 'kVA',
                'lokasi' => 'Gudang A',
                'status' => Peralatan::STATUS_TERSEDIA,
                'created_by' => 1,
                'created_at' => now()
            ], [
                'nomor_seri' => 'GNS-005',
                'peralatan_jenis_id' => 1,
                'kapasitas' => 7,
                'satuan' => 'kVA',
                'lokasi' => 'Gudang A',
                'status' => Peralatan::STATUS_TERSEDIA,
                'created_by' => 1,
                'created_at' => now()
            ], [
                'nomor_seri' => 'GNS-006',
                'peralatan_jenis_id' => 1,
                'kapasitas' => 5.5,
                'satuan' => 'kW',
                'lokasi' => 'Gudang A',
                'status' => Peralatan::STATUS_TERSEDIA,
                'created_by' => 1,
                'created_at' => now()
            ], [
                'nomor_seri' => 'UPS-001',
                'peralatan_jenis_id' => 2,
                'kapasitas' => 200,
                'satuan' => 'kVA',
                'lokasi' => 'Gudang A',
                'status' => Peralatan::STATUS_TERSEDIA,
                'created_by' => 1,
                'created_at' => now()
            ], [
                'nomor_seri' => 'UPS-002',
                'peralatan_jenis_id' => 2,
                'kapasitas' => 100,
                'satuan' => 'kVA',
                'lokasi' => 'Gudang A',
                'status' => Peralatan::STATUS_TERSEDIA,
                'created_by' => 1,
                'created_at' => now()
            ], [
                'nomor_seri' => 'UPS-003',
                'peralatan_jenis_id' => 2,
                'kapasitas' => 10,
                'satuan' => 'kVA',
                'lokasi' => 'Gudang A',
                'status' => Peralatan::STATUS_TERSEDIA,
                'created_by' => 1,
                'created_at' => now()
            ], [
                'nomor_seri' => 'UPS-004',
                'peralatan_jenis_id' => 2,
                'kapasitas' => 6,
                'satuan' => 'kVA',
                'lokasi' => 'Gudang A',
                'status' => Peralatan::STATUS_TERSEDIA,
                'created_by' => 1,
                'created_at' => now()
            ],
        ]);
    }
}
