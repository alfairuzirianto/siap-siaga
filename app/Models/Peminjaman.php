<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    public const DIPINJAM = 'Sedang Dipinjam';
    public const SELESAI = 'Selesai';

    public const STATUS_PEMINJAMAN = [
        self::DIPINJAM,
        self::SELESAI,
    ];
    
    protected $fillable = [
        'nomor_peminjaman',
        'nama_pengguna',
        'nip',
        'unit',
        'jabatan',
        'tujuan_keperluan',
        'tgl_pinjam',
        'tgl_kembali',
        'status',
    ];

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_kembali' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(PeminjamanDetail::class, 'peminjaman_id');
    }

    public function beritaAcara()
    {
        return $this->hasMany(BeritaAcara::class, 'peminjaman_id');
    }
}
