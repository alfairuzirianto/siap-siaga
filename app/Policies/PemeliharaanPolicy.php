<?php

namespace App\Policies;

use App\Models\Pemeliharaan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PemeliharaanPolicy
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
    public function view(User $user, Pemeliharaan $pemeliharaan): bool
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
    public function update(User $user, Pemeliharaan $pemeliharaan): bool
    {
        return $user->hasRole(User::ROLE_ADMIN, User::ROLE_PETUGAS);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pemeliharaan $pemeliharaan): bool
    {
        return $user->isAdmin();
    }
}
