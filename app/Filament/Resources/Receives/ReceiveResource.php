<?php

namespace App\Filament\Resources\Receives;

use App\Filament\Resources\Receives\Pages\CreateReceive;
use App\Filament\Resources\Receives\Pages\EditReceive;
use App\Filament\Resources\Receives\Pages\ListReceives;
use App\Filament\Resources\Receives\Schemas\ReceiveForm;
use App\Filament\Resources\Receives\Tables\ReceivesTable;
use App\Models\Product\Receive;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReceiveResource extends Resource
{
    protected static ?string $model = Receive::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBoxArrowDown;

    protected static ?string $recordTitleAttribute = 'Receive product';

    public static function form(Schema $schema): Schema
    {
        return ReceiveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReceivesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListReceives::route('/'),
            'create' => CreateReceive::route('/create'),
            'edit'   => EditReceive::route('/{record}/edit'),
        ];
    }
}
