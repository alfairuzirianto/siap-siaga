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

    public function createdPeralatan()
    {
        return $this->hasMany(Peralatan::class, 'created_by');
    }

    public function updatedPeralatan()
    {
        return $this->hasMany(Peralatan::class, 'updated_by');
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
