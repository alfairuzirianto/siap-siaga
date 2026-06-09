<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peralatan extends Model
{
    protected $table = 'peralatan';

    public const STATUS_TERSEDIA = 'Tersedia';
    public const STATUS_DIPINJAM = 'Dipinjam';

    public const STATUS_PERALATAN = [
        self::STATUS_TERSEDIA,
        self::STATUS_DIPINJAM,
    ];
    
    protected $fillable = [
        'nomor_seri',
        'peralatan_jenis_id',
        'status',
        'kapasitas',
        'satuan',
        'lokasi',
        'foto',
        'created_by',
        'updated_by',
    ];

    public function jenis()
    {
        return $this->belongsTo(PeralatanJenis::class, 'peralatan_jenis_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function peminjamanDetail()
    {
        return $this->hasMany(PeminjamanDetail::class, 'peralatan_id');
    }

    public function pemeliharaan()
    {
        return $this->hasMany(Pemeliharaan::class, 'peralatan_id');
    }
}
