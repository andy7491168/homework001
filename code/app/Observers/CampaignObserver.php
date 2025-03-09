<?php

namespace App\Observers;

use App\Models\Campaign;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
class CampaignObserver
{
    /**
     * Handle the Campaign "created" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function created(Campaign $campaign)
    {
        Event::dispatch('campaign.created', $campaign);
    }

    /**
     * Handle the Campaign "updated" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function updated(Campaign $campaign)
    {
        //
    }

    /**
     * Handle the Campaign "deleted" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function deleted(Campaign $campaign)
    {
        //
    }

    /**
     * Handle the Campaign "restored" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function restored(Campaign $campaign)
    {
        //
    }

    /**
     * Handle the Campaign "force deleted" event.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return void
     */
    public function forceDeleted(Campaign $campaign)
    {
        //
    }
}
