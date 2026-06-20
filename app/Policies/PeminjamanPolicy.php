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
        return $user->hasRole(User::ROLE_ADMIN, User::ROLE_PENGGUNA);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        if ($user->hasRole(User::ROLE_ADMIN, User::ROLE_SUPERVISOR))
        {
            return true;
        }

        return $user->id === $peminjaman->pengguna->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isPengguna();
    }
}
