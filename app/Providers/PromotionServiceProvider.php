<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\PromotionRepositoryInterface;
use App\Repositories\MySQLPromotionRepository;
class PromotionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
         // Lier PromotionRepositoryInterface à MySQLPromotionRepository
         $this->app->bind(PromotionRepositoryInterface::class, MySQLPromotionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
