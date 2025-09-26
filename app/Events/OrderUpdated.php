<?php

namespace App\Events;

use App\Models\User;
use App\Models\Sale\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderUpdated
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Order $order, public ?User $user)
    {
        //
    }
}
