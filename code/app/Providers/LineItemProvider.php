<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LineItemService;
class LineItemProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LineItemService::class, function ($app) {
            return new LineItemService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
