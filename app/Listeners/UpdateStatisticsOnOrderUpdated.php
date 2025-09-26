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

                $stat->total_amount_refunded = $order->customer->orders()->sum('refunded_amount');
                $stat->total_refunded_orders = $order->customer->orders()
                    ->whereIn('status', ['refunded', 'partial_refund'])
                    ->count();
                $stat->save();
            });
        }
    }
}
