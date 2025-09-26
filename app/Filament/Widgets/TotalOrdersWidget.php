<?php

namespace App\Filament\Widgets;

use App\Models\Sale\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalOrdersWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $orders = Order::whereMonth('created_at', now()->month)->get();
        $revenue = $orders->sum('total_amount') - $orders->sum('refunded_amount');
        $totalOrders = $orders->whereIn('status', ['completed', 'partial_refund'])->count();

        return [
            Stat::make('Total Orders', $totalOrders)
                ->description('sales for this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Revenue', 'EGP ' . number_format($revenue, 2))
                ->description('sales for this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
