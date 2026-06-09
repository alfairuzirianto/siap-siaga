<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeliharaan extends Model
{
    protected $table = 'pemeliharaan';

    public const JENIS_PREVENTIF = 'Preventif';
    public const JENIS_KOREKTIF = 'Korektif';

    public const JENIS_PEMELIHARAAN = [
        self::JENIS_PREVENTIF,
        self::JENIS_KOREKTIF,
    ];

    protected $fillable = [
        'nomor_pemeliharaan',
        'peralatan_id',
        'nama_petugas',
        'jenis_pemeliharaan',
        'tanggal_pemeliharaan',
        'biaya',
        'deskripsi',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_pemeliharaan' => 'date',
        'biaya' => 'decimal:2',
    ];

    public function peralatan()
    {
        return $this->belongsTo(Peralatan::class, 'peralatan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
