<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\FirebaseUserRepository;
use App\Repositories\MySQLUserRepository;
use Kreait\Firebase\Database;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, function ($app) {
            if (config('services.user.repository') === 'firebase') {
                return new FirebaseUserRepository($app->make(Database::class));
            }
            return new MySQLUserRepository();
        });

        $this->app->alias(UserRepositoryInterface::class, 'user.repository');
    }

    public function boot()
    {
        //
    }
}

