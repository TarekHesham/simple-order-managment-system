<?php

namespace App\Filament\Resources\Receives\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReceivesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('Product')->sortable()->searchable(),
                TextColumn::make('supplier.name')->label('Supplier')->sortable()->searchable(),
                TextColumn::make('user.name')->label('Received By')->sortable()->searchable(),
                TextColumn::make('quantity')->sortable(),
                TextColumn::make('unit_price')->money('EGP', true),
                TextColumn::make('total_amount')->money('EGP', true),
                TextColumn::make('created_at')->label('Received At')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
