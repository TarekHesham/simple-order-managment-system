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
        $receive->product->increment('stock', $receive->quantity);
    }

    /**
     * Handle the Receive "updated" event.
     */
    public function updated(Receive $receive): void
    {
        if ($receive->isDirty('quantity')) {
            $originalQty = $receive->getOriginal('quantity');
            $newQty = $receive->quantity;
            $product = $receive->product;

            // Subtract original quantity but not below 0
            $product->stock = max(0, $product->stock - $originalQty);

            $product->stock += $newQty;
            $product->save();
        }
    }

    /**
     * Handle the Receive "deleted" event.
     */
    public function deleted(Receive $receive): void
    {
        $product = $receive->product;
        $product->stock = max(0, $product->stock - $receive->quantity);
        $product->save();
    }
}
