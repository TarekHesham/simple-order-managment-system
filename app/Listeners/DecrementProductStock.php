<?php

namespace App\Listeners;

use App\Events\OrderCreated;

class DecrementProductStock
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        foreach ($event->items as $item) {
            $item->product->decrement('stock', $item->quantity);
        }
    }
}
