<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Customer\CustomerStatistic;
use Illuminate\Support\Facades\DB;

class UpdateStatisticsOnOrderCreated
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
        $order = $event->order;

        DB::transaction(function () use ($order) {
            $stat = CustomerStatistic::firstOrCreate(
                ['customer_id' => $order->customer_id],
                [
                    'total_amount_paid'     => 0,
                    'total_amount_refunded' => 0,
                    'total_orders'          => 0,
                    'total_refunded_orders' => 0,
                    'total_items'           => 0,
                    'first_order_date'      => $order->created_at,
                    'last_order_date'       => $order->created_at,
                ]
            );

            $itemsCount = $order->items()->sum('quantity');

            $stat->total_amount_paid += $order->total_amount;
            $stat->total_orders      += 1;
            $stat->total_items       += $itemsCount;

            if ($stat->first_order_date === null) {
                $stat->first_order_date = $order->created_at;
            }

            $stat->last_order_date = $order->created_at;

            $stat->save();
        });
    }
}
