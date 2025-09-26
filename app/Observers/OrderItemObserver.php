<?php

namespace App\Observers;

use App\Models\Sale\OrderItem;

class OrderItemObserver
{
    /**
     * Handle the Sale new order item "created" event.
     */
    public function created(OrderItem $order_item): void
    {
        $order_item->product->decrement('stock', $order_item->quantity);
    }

    /**
     * Handle the Sale new order item "updated" event.
     */
    public function updated(OrderItem $order_item): void
    {
        if ($order_item->isDirty('quantity')) {
            $order_item->product->increment('stock', $order_item->getOriginal('quantity'));

            $order_item->product->decrement('stock', $order_item->quantity);
        }
    }

    /**
     * Handle the Sale new order item "deleted" event.
     */
    public function deleted(OrderItem $order_item): void
    {
        $order_item->product->increment('stock', $order_item->quantity);
    }
}
