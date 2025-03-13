<?php

namespace App\Policies;

use App\Models\Mosque;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MosquePolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Mosque $mosque): bool
    {
        return true; // All authenticated users can view mosques
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create mosques
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Mosque $mosque): bool
    {
        // User can update if they created the mosque or are an admin of the mosque
        return $user->id === $mosque->created_by ||
               $mosque->admins()->where('user_id', $user->id)->exists() ||
               $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mosque $mosque): bool
    {
        // Only the creator or system admin can delete a mosque
        return $user->id === $mosque->created_by || $user->hasRole('admin');
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
        // Only system admins can verify mosques
        return $user->hasRole('admin');
    }
}
