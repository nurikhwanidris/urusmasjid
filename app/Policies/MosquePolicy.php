<?php

namespace App\Policies;

use App\Models\Mosque;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MosquePolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isSystemAdmin()) {
            return true;
        }

        return null; // Fall through to the specific policy methods
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view the list of mosques
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Mosque $mosque): bool
    {
        // Allow all authenticated users to view any mosque
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create a mosque
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Mosque $mosque): bool
    {
        // Allow all authenticated users to update any mosque
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mosque $mosque): bool
    {
        // Allow all authenticated users to delete any mosque
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Mosque $mosque): bool
    {
        // Only system admins can restore (handled by before method)
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Mosque $mosque): bool
    {
        // Only system admins can force delete (handled by before method)
        return false;
    }

    /**
     * Determine whether the user can verify the model.
     */
    public function verify(User $user, Mosque $mosque): bool
    {
        // Allow all authenticated users to verify any mosque
        return true;
    }
}
