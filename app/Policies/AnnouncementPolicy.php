<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\Mosque;
use App\Models\MosqueUser;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnnouncementPolicy
{
    /**
     * Determine whether the user can view any announcements.
     */
    public function viewAny(User $user, Mosque $mosque): bool
    {
        // Anyone can view published announcements
        return true;
    }

    /**
     * Determine whether the user can view the announcement.
     */
    public function view(User $user, Announcement $announcement, Mosque $mosque): bool
    {
        // Published announcements can be viewed by anyone
        if ($announcement->isPublished()) {
            return true;
        }

        // Check if user is an admin or committee member of the mosque
        $mosqueUser = MosqueUser::where('mosque_id', $mosque->id)
            ->where('user_id', $user->id)
            ->whereIn('type', ['admin', 'committee'])
            ->first();

        return $mosqueUser !== null;
    }

    /**
     * Determine whether the user can create announcements.
     */
    public function create(User $user, Mosque $mosque): bool
    {
        // Check if user is an admin or committee member of the mosque
        $mosqueUser = MosqueUser::where('mosque_id', $mosque->id)
            ->where('user_id', $user->id)
            ->whereIn('type', ['admin', 'committee'])
            ->first();

        return $mosqueUser !== null;
    }

    /**
     * Determine whether the user can update the announcement.
     */
    public function update(User $user, Announcement $announcement, Mosque $mosque): bool
    {
        // Check if user is an admin or committee member of the mosque
        $mosqueUser = MosqueUser::where('mosque_id', $mosque->id)
            ->where('user_id', $user->id)
            ->whereIn('type', ['admin', 'committee'])
            ->first();

        return $mosqueUser !== null;
    }

    /**
     * Determine whether the user can delete the announcement.
     */
    public function delete(User $user, Announcement $announcement, Mosque $mosque): bool
    {
        // Check if user is an admin of the mosque
        $mosqueUser = MosqueUser::where('mosque_id', $mosque->id)
            ->where('user_id', $user->id)
            ->where('type', 'admin')
            ->first();

        return $mosqueUser !== null;
    }
}
