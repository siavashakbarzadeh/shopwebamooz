<?php

namespace Jokoli\Payment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddSellerShareToHisAccount
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($seller =$event->payment->seller) $seller->update(['balance'=>$seller->balance+$event->payment->seller_share]);
    }
}
