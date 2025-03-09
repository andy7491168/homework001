<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CampaignService;
class CampaignProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CampaignService::class, function ($app) {
            return new CampaignService();
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
