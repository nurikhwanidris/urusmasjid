<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Mosque;
use App\Policies\AdminPolicy;
use App\Policies\MosquePolicy;
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
