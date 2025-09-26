<?php

namespace App\Observers;

use App\Models\Product\Receive;

class ReceiveObserver
{
    /**
     * Handle the Receive "created" event.
     */
    public function created(Receive $receive): void
    {
        $receive->item->increment('stock', $receive->quantity);
    }

    /**
     * Handle the Receive "updated" event.
     */
    public function updated(Receive $receive): void
    {
        if ($receive->isDirty('quantity')) {
            $receive->item->decrement('stock', $receive->getOriginal('quantity'));
            $receive->item->increment('stock', $receive->quantity);
        }
    }

    /**
     * Handle the Receive "deleted" event.
     */
    public function deleted(Receive $receive): void
    {
        $receive->item->decrement('stock', $receive->quantity);
    }
}
