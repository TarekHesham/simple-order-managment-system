<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Product\Product;

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
        $items = $event->order->items;

        $products = Product::whereIn('id', $items->pluck('product_id'))->get();

        foreach ($items as $item) {
            $product = $products->firstWhere('id', $item->product_id);

            if ($product) {
                $product->decrement('stock', $item->quantity);
            }
        }
    }
}
