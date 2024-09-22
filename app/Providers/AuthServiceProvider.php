<?php

namespace App\Providers;

use App\Services\PassportAuthentificationService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Services\Contracts\AuthentificationServiceInterface;
use Illuminate\Support\Facades\Route;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('Admin', [UserPolicy::class, 'isAdmin']);
        Gate::define('Manager', [UserPolicy::class, 'isManager']);
        Gate::define('Coach', [UserPolicy::class, 'isCoach']);
        Gate::define('CM', [UserPolicy::class, 'isCM']);
        Gate::define('Apprenant', [UserPolicy::class, 'isApprenant']);
        $this->app->bind(AuthentificationServiceInterface::class, PassportAuthentificationService::class);
        Passport::refreshTokensExpireIn(now()->addDays(30));

    }
}
