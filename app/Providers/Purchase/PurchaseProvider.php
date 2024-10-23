<?php

namespace App\Providers\Purchase;

use App\Services\Purchase\PurchaseOrderService;
use Illuminate\Support\ServiceProvider;

class PurchaseProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PurchaseOrderService::class, function ($app) {
            return new PurchaseOrderService();
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
