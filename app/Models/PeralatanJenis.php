<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeralatanJenis extends Model
{
    protected $table = 'peralatan_jenis';
    protected $fillable = ['nama_jenis'];

    public function peralatan()
    {
        return $this->hasMany(Peralatan::class, 'peralatan_jenis_id');
    }
}
