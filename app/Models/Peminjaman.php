<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    public const STATUS_DIAJUKAN = 'Diajukan';
    public const STATUS_DISETUJUI = 'Disetujui';
    public const STATUS_DIPINJAM = 'Dipinjam';
    public const STATUS_DITOLAK = 'Ditolak';
    public const STATUS_SELESAI = 'Selesai';

    public const STATUS_PEMINJAMAN = [
        self::STATUS_DIAJUKAN,
        self::STATUS_DISETUJUI,
        self::STATUS_DIPINJAM,
        self::STATUS_DITOLAK,
        self::STATUS_SELESAI,
    ];
    
    protected $fillable = [
        'nomor_peminjaman',
        'peminjam',
        'penyedia',
        'tujuan',
        'dari_tanggal',
        'sampai_tanggal',
        'status',
        'tanggal_kembali',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'peminjam' => 'array',
        'penyedia' => 'array',
        'dari_tanggal' => 'date',
        'sampai_tanggal' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    public function details()
    {
        return $this->hasMany(PeminjamanDetail::class, 'peminjaman_id');
    }

    public function beritaAcara()
    {
        return $this->hasMany(BeritaAcara::class, 'peminjaman_id');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'peminjaman_id');
    }
}
