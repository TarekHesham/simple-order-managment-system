<?php

namespace App\Listeners;

use App\Events\OrderUpdated;
use App\Models\Customer\CustomerStatistic;
use Illuminate\Support\Facades\DB;

class UpdateStatisticsOnOrderUpdated
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
    public function handle(OrderUpdated $event): void
    {
        $order = $event->order;

        if ($order->wasChanged(['refunded_amount', 'status'])) {
            DB::transaction(function () use ($order) {
                $stat = CustomerStatistic::firstOrCreate(['customer_id' => $order->customer_id]);
                $aggregates = $order->customer->orders()
                    ->selectRaw('
                        SUM(refunded_amount) as total_refunded,
                        SUM(total_amount) as total_paid,
                        COUNT(CASE WHEN status = "refunded" THEN 1 END) as refunded_orders
                    ')
                    ->first();

                $stat->total_amount_refunded = $aggregates->total_refunded ?? 0;
                $stat->total_refunded_orders = $aggregates->refunded_orders ?? 0;
                $stat->total_amount_paid     = ($aggregates->total_paid ?? 0) - ($aggregates->total_refunded ?? 0);
                $stat->save();
            });
        }
    }
}
