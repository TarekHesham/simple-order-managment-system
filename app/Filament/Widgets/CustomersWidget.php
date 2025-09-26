<?php

namespace App\Filament\Widgets;

use App\Models\Customer\CustomerStatistic;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class CustomersWidget extends TableWidget
{
    protected static ?string $title = 'TOP 5 Customers';
    protected string|array|int $columnSpan = 'full';
    protected static ?int $sort = 2;

    protected function getTableTitle(): string
    {
        return 'TOP 5 Customers';
    }

    protected function getHeading(): string
    {
        return 'TOP 5 Customers';
    }

    protected function getTableQuery(): Builder
    {
        return CustomerStatistic::with('customer')
            ->orderByDesc('total_amount_paid')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('customer.name')->label('Name'),
            TextColumn::make('customer.email')->label('Email'),
            TextColumn::make('customer.phone_number')->label('Phone'),
            TextColumn::make('total_amount_paid')->label('Total Paid')->money('egp', true),
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
