<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->nullable()
                    ->rows(3),

                TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->prefix('$')
                    ->minValue(0),

                TextInput::make('stock')
                    ->numeric()
                    ->required()
                    ->minValue(0),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(false),
            ]);
    }
}
