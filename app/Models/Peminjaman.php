<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    public const STATUS_DIAJUKAN = 'Diajukan';
    public const STATUS_DIBATALKAN = 'Dibatalkan';
    public const STATUS_DISETUJUI = 'Disetujui';
    public const STATUS_DIPINJAM = 'Dipinjam';
    public const STATUS_DITOLAK = 'Ditolak';
    public const STATUS_DIKEMBALIKAN = 'Dikembalikan';

    public const STATUS_PEMINJAMAN = [
        self::STATUS_DIAJUKAN,
        self::STATUS_DIBATALKAN,
        self::STATUS_DISETUJUI,
        self::STATUS_DIPINJAM,
        self::STATUS_DITOLAK,
        self::STATUS_DIKEMBALIKAN,
    ];
    
    protected $fillable = [
        'nomor_peminjaman',
        'pengguna_id',
        'tujuan_keperluan',
        'tgl_rencana_pinjam',
        'tgl_rencana_kembali',
        'status',
        'tgl_realisasi_kembali',
        'approver_id',
        'keterangan_pinjam',
        'keterangan_kembali',
    ];

    protected $casts = [
        'tgl_rencana_pinjam' => 'date',
        'tgl_rencana_kembali' => 'date',
        'tgl_realisasi_kembali' => 'date',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function details()
    {
        return $this->hasMany(PeminjamanDetail::class, 'peminjaman_id');
    }

    public function beritaAcara()
    {
        return $this->hasMany(BeritaAcara::class, 'peminjaman_id');
    }
}
