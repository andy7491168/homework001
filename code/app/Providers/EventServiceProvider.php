<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Observers\CampaignObserver;
use App\Observers\LineItemObserver;
use App\Listeners\CampaignCreatedListener;
use App\Listeners\CampaignUpdatedListener;
use App\Listeners\LineItemCreatedListener;
use App\Listeners\LineItemUpdatedListener;
use App\Models\Campaign;
use App\Models\LineItem;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'campaign.created' => [
            CampaignCreatedListener::class,
        ],
        'campaign.updated' => [
            CampaignUpdatedListener::class,
        ],
        'lineItem.created' => [
            LineItemCreatedListener::class,
        ],
        'lineItem.updated' => [
            LineItemUpdatedListener::class,
        ],
        
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Campaign::observe(CampaignObserver::class);
        LineItem::observe(LineItemObserver::class);
    }
}
