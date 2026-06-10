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
    public const ROLE_PETUGAS = 'Petugas';
    public const ROLE_SUPERVISOR = 'Supervisor';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_PETUGAS,
        self::ROLE_SUPERVISOR,
    ];

    public function hasRole(string ...$roles): bool
    {
        if (! $this->role) {
            return false;
        }

        return in_array($this->role, $roles, strict: true);
    }

    public function isAdmin()
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    public function isPetugas()
    {
        return $this->hasRole(self::ROLE_PETUGAS);
    }

    public function isSupervisor()
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

    public function createdPeminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'created_by');
    }

    public function updatedPeminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'updated_by');
    }

    public function createdPemeliharaan()
    {
        return $this->hasMany(Pemeliharaan::class, 'created_by');
    }

    public function updatedPemeliharaan()
    {
        return $this->hasMany(Pemeliharaan::class, 'updated_by');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'approver_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }
}
