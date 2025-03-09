<?php

namespace App\Observers;

use App\Models\LineItem;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log; 
class LineItemObserver
{
    /**
     * Handle the LineItem "created" event.
     *
     * @param  \App\Models\LineItem  $lineItem
     * @return void
     */
    public function created(LineItem $lineItem)
    {
        Event::dispatch('lineItem.created', $lineItem);
    }

    /**
     * Handle the LineItem "updated" event.
     *
     * @param  \App\Models\LineItem  $lineItem
     * @return void
     */
    public function updated(LineItem $lineItem)
    {
        Event::dispatch('lineItem.updated', $lineItem);
    }

    /**
     * Handle the LineItem "deleted" event.
     *
     * @param  \App\Models\LineItem  $lineItem
     * @return void
     */
    public function deleted(LineItem $lineItem)
    {
        //
    }

    /**
     * Handle the LineItem "restored" event.
     *
     * @param  \App\Models\LineItem  $lineItem
     * @return void
     */
    public function restored(LineItem $lineItem)
    {
        //
    }

    /**
     * Handle the LineItem "force deleted" event.
     *
     * @param  \App\Models\LineItem  $lineItem
     * @return void
     */
    public function forceDeleted(LineItem $lineItem)
    {
        //
    }
}
