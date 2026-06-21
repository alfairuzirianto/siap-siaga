<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    public const PINJAM_DIAJUKAN = 'Diajukan Peminjaman';
    public const PINJAM_DIBATALKAN = 'Peminjaman Dibatalkan';
    public const PINJAM_DISETUJUI = 'Peminjaman Disetujui';
    public const PINJAM_DITOLAK = 'Peminjaman Ditolak';
    public const DIPINJAM = 'Sedang Dipinjam';
    public const KEMBALI_DIAJUKAN = 'Diajukan Pengembalian';
    public const KEMBALI_DISETUJUI = 'Pengembalian Disetujui';
    public const KEMBALI_DITOLAK = 'Pengembalian Ditolak';
    public const SELESAI = 'Selesai';

    public const STATUS_PEMINJAMAN = [
        self::PINJAM_DIAJUKAN,
        self::PINJAM_DIBATALKAN,
        self::PINJAM_DISETUJUI,
        self::PINJAM_DITOLAK,
        self::DIPINJAM,
    ];

    public const STATUS_PENGEMBALIAN = [
        self::KEMBALI_DIAJUKAN,
        self::KEMBALI_DISETUJUI,
        self::KEMBALI_DITOLAK,
        self::SELESAI,
    ];
    
    protected $fillable = [
        'nomor_peminjaman',
        'pengguna_id',
        'tujuan_keperluan',
        'tgl_rencana_pinjam',
        'tgl_rencana_kembali',
        'status',
        'tgl_realisasi_kembali',
        'approver_pinjam',
        'peminjaman_approved_at',
        'keterangan_pinjam',
        'approver_kembali',
        'keterangan_kembali',
        'pengembalian_approved_at',
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

    public function approverPinjam()
    {
        return $this->belongsTo(User::class, 'approver_pinjam');
    }

    public function approverKembali()
    {
        return $this->belongsTo(User::class, 'approver_kembali');
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
