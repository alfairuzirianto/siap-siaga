<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaAcaraDokumentasi extends Model
{
    protected $table = 'berita_acara_dokumentasi';

    protected $fillable = [
        'berita_acara_id',
        'keterangan',
        'foto',
    ];

    protected $casts = [
        'foto' => 'array',
    ];

    public function beritaAcara()
    {
        return $this->belongsTo(BeritaAcara::class, 'berita_acara_id');
    }
}
