<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PeminjamanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(User::ROLE_ADMIN, User::ROLE_PETUGAS);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        return $user->hasRole(User::ROLE_ADMIN, User::ROLE_PETUGAS);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(User::ROLE_ADMIN, User::ROLE_PETUGAS);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Peminjaman $peminjaman): bool
    {
        return $user->hasRole(User::ROLE_ADMIN, User::ROLE_PETUGAS);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Peminjaman $peminjaman): bool
    {
        return $user->isAdmin();
    }
}
