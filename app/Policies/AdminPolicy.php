<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can access admin features.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function access(User $user): bool
    {
        return $user->isSystemAdmin();
    }
}
