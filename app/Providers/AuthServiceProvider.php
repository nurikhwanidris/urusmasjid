<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Mosque;
use App\Models\Announcement;
use App\Policies\AdminPolicy;
use App\Policies\MosquePolicy;
use App\Policies\AnnouncementPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Register policies
        Mosque::class => MosquePolicy::class,
        Announcement::class => AnnouncementPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register policies
        $this->registerPolicies();

        // Define the 'admin' gate
        Gate::define('admin', function (User $user) {
            return $user->isSystemAdmin();
        });
    }
}
