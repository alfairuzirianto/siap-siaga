<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    public const APPROVAL_PEMINJAMAN = 'Peminjaman';
    public const APPROVAL_PENGEMBALIAN = 'Pengembalian';

    public const STATUS_PENDING = 'Pending';
    public const STATUS_DISETUJUI = 'Disetujui';
    public const STATUS_DITOLAK = 'Ditolak';

    public const JENIS_APPROVAL = [
        self::APPROVAL_PEMINJAMAN,
        self::APPROVAL_PENGEMBALIAN,
    ];

    public const STATUS_APPROVAL = [
        self::STATUS_PENDING,
        self::STATUS_DISETUJUI,
        self::STATUS_DITOLAK,
    ];

    protected $fillable = [
        'jenis_approval',
        'peminjaman_id',
        'approver_id',
        'status',
        'keterangan',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
