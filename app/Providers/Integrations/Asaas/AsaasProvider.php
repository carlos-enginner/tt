<?php

namespace App\Providers\Integrations\Asaas;

use App\Services\Integrations\Asaas\ChargeService;
use Illuminate\Support\ServiceProvider;

class AsaasProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ChargeService::class, function ($app) {
            return new ChargeService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
