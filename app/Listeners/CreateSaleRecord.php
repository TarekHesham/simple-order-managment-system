<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Sale\Sale;

class CreateSaleRecord
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
        Sale::create([
            'order_id' => $event->order->id,
            'user_id'  => $event->user?->id,
        ]);
    }
}
