<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public const AKSI_CREATE = 'Create';
    public const AKSI_UPDATE = 'Update';
    public const AKSI_DELETE = 'Delete';
    public const AKSI_GENERATE = 'Generate BA';

    public const AKSI_USER = [
        self::AKSI_CREATE,
        self::AKSI_UPDATE,
        self::AKSI_DELETE,
        self::AKSI_GENERATE,
    ];

    protected $fillable = [
        'user_id',
        'aksi',
        'nama_table',
        'record_id',
        'old_value',
        'new_value',
    ];

    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
