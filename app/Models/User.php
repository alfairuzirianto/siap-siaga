<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_lengkap',
        'nip',
        'jabatan',
        'unit',
        'username',
        'role',
        'is_active',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public const ROLE_ADMIN = 'Admin';
    public const ROLE_PENGGUNA = 'Pengguna';
    public const ROLE_SUPERVISOR = 'Supervisor';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_PENGGUNA,
        self::ROLE_SUPERVISOR,
    ];

    public function hasRole(string ...$roles): bool
    {
        if (! $this->role) {
            return false;
        }

        return in_array($this->role, $roles, strict: true);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    public function isPengguna(): bool
    {
        return $this->hasRole(self::ROLE_PENGGUNA);
    }

    public function isSupervisor(): bool
    {
        return $this->hasRole(self::ROLE_SUPERVISOR);
    }

    public function createdPeralatan()
    {
        return $this->hasMany(Peralatan::class, 'created_by');
    }

    public function updatedPeralatan()
    {
        return $this->hasMany(Peralatan::class, 'updated_by');
    }

    public function pinjam()
    {
        return $this->hasMany(Peminjaman::class, 'pengguna_id');
    }

    public function validasiPinjam()
    {
        return $this->hasMany(Peminjaman::class, 'approver_pinjam');
    }

    public function validasiKembali()
    {
        return $this->hasMany(Peminjaman::class, 'approver_kembali');
    }

    public function createdPemeliharaan()
    {
        return $this->hasMany(Pemeliharaan::class, 'created_by');
    }

    public function updatedPemeliharaan()
    {
        return $this->hasMany(Pemeliharaan::class, 'updated_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }
}
