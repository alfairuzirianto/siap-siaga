<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaAcara extends Model
{
    protected $table = 'berita_acara';

    public const BA_PEMINJAMAN = 'Peminjaman';
    public const BA_PENGEMBALIAN = 'Pengembalian';

    public const JENIS_BA = [
        self::BA_PEMINJAMAN,
        self::BA_PENGEMBALIAN,
    ];

    protected $fillable = [
        'peminjaman_id',
        'nomor_ba',
        'jenis_ba',
        'file',
        'token',
        'is_valid',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function dokumentasi()
    {
        return $this->hasMany(BeritaAcaraDokumentasi::class, 'berita_acara_id');
    }
}
